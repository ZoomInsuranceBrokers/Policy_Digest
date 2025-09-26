<?php

namespace App\Http\Controllers;

use App\Models\CompanyMaster;
use App\Models\PolicyPortfolio;
use Illuminate\Http\Request;
use App\Models\EndorsementCopy;
use App\Models\Claims;

class PortfolioController extends Controller
{

    public function userPortfolio()
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $policies = [];
        $endorsements = [];
        if ($companyId) {
            $policies = PolicyPortfolio::where('company_id', $companyId)->get();
            $endorsements = EndorsementCopy::whereIn('policy_portfolio_id', $policies->pluck('id'))->get();
        }
        return view('user.portfolio', compact('policies', 'endorsements'));
    }

    public function userViewEndorsements($policyId)
    {
        $user = auth()->user();
        $policy = PolicyPortfolio::where('id', $policyId)
                                ->where('company_id', $user->company_id)
                                ->firstOrFail();
        $endorsements = EndorsementCopy::where('policy_portfolio_id', $policyId)->get();
        return view('user.endorsements', compact('policy', 'endorsements'));
    }

    public function userClaims()
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $claims = [];
        $policies = [];
        if ($companyId) {
            $claims = Claims::where('company_id', $companyId)
                           ->with(['policyPortfolio'])
                           ->orderBy('created_at', 'desc')
                           ->get();
            $policies = PolicyPortfolio::where('company_id', $companyId)->get();
        }
        return view('user.claims', compact('claims', 'policies'));
    }

    public function createClaim($type = 'regular')
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $policies = [];
        $selectedPolicy = null;
        if ($companyId) {
            $policies = PolicyPortfolio::where('company_id', $companyId)->get();
            $policyId = request('policy_id');
            if ($policyId) {
                $selectedPolicy = PolicyPortfolio::with('company')->where('company_id', $companyId)->where('id', $policyId)->first();
            }
        }

        $isMarine = ($type === 'marine') ? 1 : 0;
        return view('user.create_claim', compact('policies', 'isMarine', 'type', 'selectedPolicy'));
    }

    public function storeClaim(Request $request)
    {
        $user = auth()->user();

        $validationRules = [
            'policy_portfolio_id' => 'nullable|exists:policy_portfolio,id',
            'is_marine' => 'required|boolean',
            'policy_number' => 'nullable|string|max:255',
            'policy_period' => 'nullable|string|max:255',
            'estimated_loss_amount' => 'nullable|numeric|min:0',
            'date_of_loss' => 'nullable|date',
        ];

        // Add specific validation rules based on claim type
        if ($request->is_marine == 1) {
            // Marine claims validation
            $validationRules = array_merge($validationRules, [
                'name_of_insured' => 'nullable|string|max:255',
                'consignor_name_address' => 'nullable|string',
                'consignee_name_address' => 'nullable|string',
                'invoice_no' => 'nullable|string|max:255',
                'invoice_date' => 'nullable|date',
                'invoice_value' => 'nullable|numeric|min:0',
                'lr_gr_airway_bl_no' => 'nullable|string|max:255',
                'lr_gr_airway_bl_date' => 'nullable|date',
                'transporter_name' => 'nullable|string|max:255',
                'driver_name' => 'nullable|string|max:255',
                'driver_phone' => 'nullable|string|max:20',
                'vehicle_container_no' => 'nullable|string|max:255',
                'consignment_received_date' => 'nullable|date',
                'place_of_loss' => 'nullable|string|max:255',
                'nature_of_loss' => 'nullable|string|max:255',
                'survey_address' => 'nullable|string',
                'spoc_name' => 'nullable|string|max:255',
                'spoc_phone' => 'nullable|string|max:20',
                'item_commodity_description' => 'nullable|string',
            ]);
        } else {
            // Regular claims validation
            $validationRules = array_merge($validationRules, [
                'insured_name' => 'nullable|string|max:255',
                'policy_type' => 'nullable|string|max:255',
                'brief_description_of_loss' => 'nullable|string',
                'details_of_affected_items' => 'nullable|string',
                'complete_loss_location' => 'nullable|string',
                'contact_person_name' => 'nullable|string|max:255',
                'contact_person_phone' => 'nullable|string|max:20',
                'contact_person_email' => 'nullable|email|max:255',
            ]);
        }

        $request->validate($validationRules);

        $claimData = $request->all();
        $claimData['company_id'] = $user->company_id;

        Claims::create($claimData);

        return redirect()->route('user.claims')->with('success', 'Claim submitted successfully!');
    }

    public function ajaxBulkRow(Request $request, $companyId)
    {
        $data = $request->all();
        // Convert date fields to YYYY-MM-DD if needed (dd/mm/yyyy, dd-mm-yyyy, yyyy-mm-dd)
        foreach (['Start Date', 'Expiry Date'] as $dateField) {
            if (!empty($data[$dateField])) {
                $dateStr = $data[$dateField];
                $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d'];
                foreach ($formats as $fmt) {
                    $dt = \DateTime::createFromFormat($fmt, $dateStr);
                    if ($dt) {
                        $data[$dateField] = $dt->format('Y-m-d');
                        break;
                    }
                }
            }
        }
        $validator = \Validator::make($data, [
            'Insured Name' => 'required|string|max:255',
            'Product Name' => 'required|string|max:255',
            'Policy Number' => 'required|string|max:255',
            'Start Date' => 'required|date',
            'Expiry Date' => 'required|date|after_or_equal:Start Date',
            'Sum Insured' => 'required|numeric|min:0',
            'PBST' => 'nullable|string|max:255',
            'Gross Premium' => 'required|numeric|min:0',
            'Insurance Company Name' => 'required|string|max:255',
            'Cash Deposit' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => implode('; ', $validator->errors()->all()),
            ]);
        }
        try {
            PolicyPortfolio::create([
                'company_id' => $companyId,
                'insured_name' => $data['Insured Name'],
                'product_name' => $data['Product Name'],
                'policy_number' => $data['Policy Number'],
                'start_date' => $data['Start Date'],
                'expiry_date' => $data['Expiry Date'],
                'sum_insured' => $data['Sum Insured'],
                'pbst' => $data['PBST'],
                'gross_premium' => $data['Gross Premium'],
                'insurance_company_name' => $data['Insurance Company Name'],
                'cash_deposit' => $data['Cash Deposit'],
                'policy_copy' => null,
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateEndorsement(Request $request, $endorsementId)
    {
        $endorsement = EndorsementCopy::findOrFail($endorsementId);
        $request->validate([
            'remark' => 'nullable|string|max:255',
        ]);
        $endorsement->remark = $request->remark;
        $endorsement->save();
        return back()->with('success', 'Remark updated.');
    }

    public function uploadEndorsementCopy(Request $request, $policyId)
    {
        $request->validate([
            'document' => 'required',
            'document.*' => 'file|mimes:pdf,jpg,jpeg,png',
        ]);
        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                $path = $file->store('endorsement_copies', 'public');
                // Store original filename in remark column
                $originalFilename = $file->getClientOriginalName();
                EndorsementCopy::create([
                    'policy_portfolio_id' => $policyId,
                    'document' => $path,
                    'remark' => $originalFilename,
                ]);
            }
        }
        return back()->with('success', 'Endorsement copy/copies uploaded successfully.');
    }

    public function deleteEndorsement($endorsementId)
    {
        $endorsement = EndorsementCopy::findOrFail($endorsementId);
        $endorsement->delete();
        return back()->with('success', 'Endorsement copy deleted.');
    }

    public function uploadPolicyCopy(Request $request, $policyId)
    {
        $request->validate([
            'policy_copy' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);
        $policy = PolicyPortfolio::findOrFail($policyId);
        $path = $request->file('policy_copy')->store('policy_copies', 'public');
        $policy->policy_copy = $path;
        $policy->save();
        return back()->with('success', 'Policy copy uploaded successfully.');
    }

    public function viewEndorsements($policyId)
    {
        $policy = PolicyPortfolio::findOrFail($policyId);
        $endorsements = EndorsementCopy::where('policy_portfolio_id', $policyId)->get();
        return view('admin.endorsements', compact('policy', 'endorsements'));
    }

    public function storeSinglePolicy(Request $request, $companyId)
    {
        $request->validate([
            'insured_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'policy_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:start_date',
            'sum_insured' => 'required|numeric|min:0',
            'pbst' => 'nullable|string|max:255',
            'gross_premium' => 'required|numeric|min:0',
            'insurance_company_name' => 'required|string|max:255',
            'cash_deposit' => 'nullable|string|max:255',
            'policy_copy' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $company = CompanyMaster::findOrFail($companyId);
        $policyCopyPath = $request->file('policy_copy')->store('policy_copies', 'public');

        $policy = PolicyPortfolio::create([
            'company_id' => $companyId,
            'insured_name' => $request->insured_name,
            'product_name' => $request->product_name,
            'policy_number' => $request->policy_number,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'sum_insured' => $request->sum_insured,
            'pbst' => $request->pbst,
            'gross_premium' => $request->gross_premium,
            'insurance_company_name' => $request->insurance_company_name,
            'cash_deposit' => $request->cash_deposit,
            'policy_copy' => $policyCopyPath,
        ]);

        return redirect()->route('admin.portfolio.policies', $companyId)->with('success', 'Policy created successfully.');
    }

    public function index()
    {
        $companies = CompanyMaster::where('is_delete', 0)->get();
        return view('admin.portfolio', compact('companies'));
    }

    public function showPolicies($companyId)
    {
        $company = CompanyMaster::findOrFail($companyId);
        $policies = PolicyPortfolio::where('company_id', $companyId)->get();
        return view('admin.policies', compact('company', 'policies'));
    }

    public function createPolicyForm($companyId)
    {
        $company = CompanyMaster::findOrFail($companyId);
        return view('admin.create_policy', compact('company'));
    }

    public function bulkUpload(Request $request, $companyId)
    {
        dd($request->all());
        $request->validate([
            'bulk_csv' => 'required|file|mimes:csv,txt',
        ]);

        $requiredColumns = [
            'Insured Name',
            'Product Name',
            'Policy Number',
            'Start Date',
            'Expiry Date',
            'Sum Insured',
            'PBST',
            'Gross Premium',
            'Insurance Company Name',
            'Cash Deposit'
        ];
        $company = CompanyMaster::findOrFail($companyId);
        $file = $request->file('bulk_csv');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $missing = array_diff($requiredColumns, $header);
        $results = [];
        $logRows = [];
        if (count($missing) > 0) {
            fclose($handle);
            $msg = 'Missing columns: ' . implode(', ', $missing);
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => $msg]);
            }
            return back()->withErrors(['bulk_csv' => $msg]);
        }
        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $data = array_combine($header, $row);
            // Convert date fields to yyyy-mm-dd using DateTime for all common formats
            foreach (['Start Date', 'Expiry Date'] as $dateField) {
                if (!empty($data[$dateField])) {
                    $dateStr = $data[$dateField];
                    $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d'];
                    foreach ($formats as $fmt) {
                        $dt = \DateTime::createFromFormat($fmt, $dateStr);
                        if ($dt && $dt->format($fmt) === $dateStr) {
                            $data[$dateField] = $dt->format('Y-m-d');
                            break;
                        }
                    }
                }
            }
            $rowResult = ['row' => $rowNum, 'data' => $data, 'status' => '', 'error' => ''];
            // Validate each field
            $validator = \Validator::make($data, [
                'Insured Name' => 'required|string|max:255',
                'Product Name' => 'required|string|max:255',
                'Policy Number' => 'required|string|max:255',
                'Start Date' => 'required|date',
                'Expiry Date' => 'required|date|after_or_equal:Start Date',
                'Sum Insured' => 'required|numeric|min:0',
                'PBST' => 'nullable|string|max:255',
                'Gross Premium' => 'required|numeric|min:0',
                'Insurance Company Name' => 'required|string|max:255',
                'Cash Deposit' => 'nullable|string|max:255',
            ]);
            if ($validator->fails()) {
                $rowResult['status'] = 'Failed';
                $rowResult['error'] = implode('; ', $validator->errors()->all());
            } else {
                try {
                    PolicyPortfolio::create([
                        'company_id' => $companyId,
                        'insured_name' => $data['Insured Name'],
                        'product_name' => $data['Product Name'],
                        'policy_number' => $data['Policy Number'],
                        'start_date' => $data['Start Date'],
                        'expiry_date' => $data['Expiry Date'],
                        'sum_insured' => $data['Sum Insured'],
                        'pbst' => $data['PBST'],
                        'gross_premium' => $data['Gross Premium'],
                        'insurance_company_name' => $data['Insurance Company Name'],
                        'cash_deposit' => $data['Cash Deposit'],
                        'policy_copy' => null,
                    ]);
                    $rowResult['status'] = 'Inserted';
                } catch (\Exception $e) {
                    $rowResult['status'] = 'Failed';
                    $rowResult['error'] = $e->getMessage();
                }
            }
            $results[] = $rowResult;
            $logRows[] = [
                $rowNum,
                $rowResult['status'],
                $rowResult['error'],
                json_encode($data)
            ];
        }
        fclose($handle);
        // Generate log file
        $logFileName = 'bulk_upload_log_' . time() . '.csv';
        $logPath = storage_path('app/public/' . $logFileName);
        $logHandle = fopen($logPath, 'w');
        fputcsv($logHandle, ['Row', 'Status', 'Error', 'Data']);
        foreach ($logRows as $logRow) {
            fputcsv($logHandle, $logRow);
        }
        fclose($logHandle);
        $logUrl = asset('storage/' . $logFileName);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'results' => $results,
                'log_url' => $logUrl,
            ]);
        }
        return redirect()->route('admin.portfolio.policies', $companyId)->with('success', 'Bulk upload complete.');
    }
}
