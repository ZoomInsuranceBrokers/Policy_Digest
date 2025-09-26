<?php

namespace App\Http\Controllers;

use App\Models\CompanyMaster;
use App\Models\PolicyPortfolio;
use Illuminate\Http\Request;
use App\Models\EndorsementCopy;

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

    public function ajaxBulkRow(Request $request, $companyId)
    {
        $data = $request->all();
        // Convert date fields to YYYY-MM-DD if needed
        foreach (['Start Date', 'Expiry Date'] as $dateField) {
            if (!empty($data[$dateField])) {
                $date = $data[$dateField];
                if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $m)) {
                    $data[$dateField] = $m[3] . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT);
                }
            }
        }
        $validator = \Validator::make($data, [
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
            'remark' => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                $path = $file->store('endorsement_copies', 'public');
                EndorsementCopy::create([
                    'policy_portfolio_id' => $policyId,
                    'document' => $path,
                    'remark' => $request->remark,
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
        $request->validate([
            'bulk_csv' => 'required|file|mimes:csv,txt',
        ]);

        $requiredColumns = [
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
            $rowResult = ['row' => $rowNum, 'data' => $data, 'status' => '', 'error' => ''];
            // Validate each field
            $validator = \Validator::make($data, [
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
