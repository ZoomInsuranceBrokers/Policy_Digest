<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CdMaster;
use App\Models\CompanyMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CdAccountController extends Controller
{
    /**
     * Display a listing of CD accounts.
     */
    public function index()
    {
        $cdAccounts = CdMaster::with('company')->get();
        return view('admin.cd_account.index', compact('cdAccounts'));
    }

    /**
     * Show the form for creating a new CD account.
     */
    public function create()
    {
        $companies = CompanyMaster::where('is_active', '1')->get();

        $insuranceCompanies = [
            'Life Insurance Corporation of India (LIC)',
            'HDFC Life Insurance Co. Ltd',
            'Max Life Insurance Co. Ltd',
            'ICICI Prudential Life Insurance Co. Ltd',
            'Kotak Mahindra Life Insurance Co. Ltd',
            'Aditya Birla Sun Life Insurance Co. Ltd',
            'TATA AIA Life Insurance Co. Ltd',
            'SBI Life Insurance Co. Ltd',
            'Bajaj Allianz Life Insurance Co. Ltd',
            'PNB MetLife India Insurance Co. Ltd',
            'Reliance Nippon Life Insurance Co.',
            'Aviva Life Insurance Company India Ltd',
            'Sahara India Life Insurance Co. Ltd',
            'Shriram Life Insurance Co. Ltd',
            'Bharti AXA Life Insurance Co. Ltd',
            'Future Generali India Life Insurance Co. Ltd',
            'Ageas Federal Life Insurance Co. Ltd',
            'Canara HSBC Life Insurance Co. Ltd',
            'Bandhan Life Insurance Co. Ltd',
            'Pramerica Life Insurance Co. Ltd',
            'Star Union Dai-ichi Life Insurance',
            'IndiaFirst Life Insurance Co. Ltd',
            'Edelweiss Life Insurance Co. Ltd',
            'Credit Access Life Insurance Limited',
            'Acko Life Insurance Limited',
            'Go Digit Life Insurance Limited'
        ];

        return view('admin.cd_account.create', compact('companies', 'insuranceCompanies'));
    }

    /**
     * Store a newly created CD account in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'comp_id' => 'required|exists:company_master,id',
            'insurance_name' => 'required|string|max:255',
            'cd_ac_name' => 'required|string|max:255',
            'cd_ac_no' => 'required|string|max:50|unique:cd_master,cd_ac_no',
            'minimum_balance' => 'required|numeric|min:0',
            'current_balance' => 'required|numeric|min:0',
            'statment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        $cdAccount = new CdMaster();
        $cdAccount->comp_id = $request->comp_id;

        // Handle insurance name - use custom if selected, otherwise use dropdown selection
        if ($request->insurance_name === 'custom') {
            $cdAccount->insurance_name = $request->custom_insurance_name;
        } else {
            $cdAccount->insurance_name = $request->insurance_name;
        }

        $cdAccount->cd_ac_name = $request->cd_ac_name;
        $cdAccount->cd_ac_no = $request->cd_ac_no;
        $cdAccount->minimum_balance = $request->minimum_balance;
        $cdAccount->current_balance = $request->current_balance;
        $cdAccount->status = 1;

        // Handle file upload
        if ($request->hasFile('statment_file')) {
            $file = $request->file('statment_file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Create folder if it doesn't exist
            $folderPath = 'uploads/cd_statements';
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0755, true);
            }

            $file->move(public_path($folderPath), $fileName);
            $cdAccount->statment_file = $fileName;
            $cdAccount->cd_folder = $folderPath;
        }

        $cdAccount->save();

        return redirect()->route('admin.cd_account')->with('success', 'CD Account created successfully!');
    }

    /**
     * Show the form for editing the specified CD account.
     */
    public function edit($id)
    {
        $cdAccount = CdMaster::findOrFail($id);
        $companies = CompanyMaster::where('is_active', '1')->get();
        $insuranceCompanies = [
            'Life Insurance Corporation of India (LIC)',
            'HDFC Life Insurance Co. Ltd',
            'Max Life Insurance Co. Ltd',
            'ICICI Prudential Life Insurance Co. Ltd',
            'Kotak Mahindra Life Insurance Co. Ltd',
            'Aditya Birla Sun Life Insurance Co. Ltd',
            'TATA AIA Life Insurance Co. Ltd',
            'SBI Life Insurance Co. Ltd',
            'Bajaj Allianz Life Insurance Co. Ltd',
            'PNB MetLife India Insurance Co. Ltd',
            'Reliance Nippon Life Insurance Co.',
            'Aviva Life Insurance Company India Ltd',
            'Sahara India Life Insurance Co. Ltd',
            'Shriram Life Insurance Co. Ltd',
            'Bharti AXA Life Insurance Co. Ltd',
            'Future Generali India Life Insurance Co. Ltd',
            'Ageas Federal Life Insurance Co. Ltd',
            'Canara HSBC Life Insurance Co. Ltd',
            'Bandhan Life Insurance Co. Ltd',
            'Pramerica Life Insurance Co. Ltd',
            'Star Union Dai-ichi Life Insurance',
            'IndiaFirst Life Insurance Co. Ltd',
            'Edelweiss Life Insurance Co. Ltd',
            'Credit Access Life Insurance Limited',
            'Acko Life Insurance Limited',
            'Go Digit Life Insurance Limited'
        ];

        return view('admin.cd_account.edit', compact('cdAccount', 'companies', 'insuranceCompanies'));
    }

    /**
     * Update the specified CD account in storage.
     */
    public function update(Request $request, $id)
    {
        $cdAccount = CdMaster::findOrFail($id);

        $request->validate([
            'comp_id' => 'required|exists:company_master,id',
            'insurance_name' => 'required|string|max:255',
            'cd_ac_name' => 'required|string|max:255',
            'cd_ac_no' => 'required|string|max:50|unique:cd_master,cd_ac_no,' . $id,
            'minimum_balance' => 'required|numeric|min:0',
            'current_balance' => 'required|numeric|min:0',
            'status' => 'required',
            'statment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        $cdAccount->comp_id = $request->comp_id;

        // Handle insurance name - use custom if selected, otherwise use dropdown selection
        if ($request->insurance_name === 'custom') {
            $cdAccount->insurance_name = $request->custom_insurance_name;
        } else {
            $cdAccount->insurance_name = $request->insurance_name;
        }

        $cdAccount->cd_ac_name = $request->cd_ac_name;
        $cdAccount->cd_ac_no = $request->cd_ac_no;
        $cdAccount->minimum_balance = $request->minimum_balance;
        $cdAccount->current_balance = $request->current_balance;
        $cdAccount->status = $request->status;

        // Handle file upload
        if ($request->hasFile('statment_file')) {
            // Delete old file if exists
            if ($cdAccount->statment_file && file_exists(public_path($cdAccount->cd_folder . '/' . $cdAccount->statment_file))) {
                unlink(public_path($cdAccount->cd_folder . '/' . $cdAccount->statment_file));
            }

            $file = $request->file('statment_file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Create folder if it doesn't exist
            $folderPath = 'uploads/cd_statements';
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0755, true);
            }

            $file->move(public_path($folderPath), $fileName);
            $cdAccount->statment_file = $fileName;
            $cdAccount->cd_folder = $folderPath;
        }

        $cdAccount->save();

        return redirect()->route('admin.cd_account')->with('success', 'CD Account updated successfully!');
    }

    /**
     * Remove the specified CD account from storage.
     */
    public function destroy($id)
    {
        $cdAccount = CdMaster::findOrFail($id);

        // Delete associated file if exists
        if ($cdAccount->statment_file && file_exists(public_path($cdAccount->cd_folder . '/' . $cdAccount->statment_file))) {
            unlink(public_path($cdAccount->cd_folder . '/' . $cdAccount->statment_file));
        }

        $cdAccount->delete();

        return redirect()->route('admin.cd_account')->with('success', 'CD Account deleted successfully!');
    }

    /**
     * Get CD accounts by company ID for AJAX requests
     */
    public function getByCompany($companyId)
    {
        $cdAccounts = CdMaster::where('comp_id', $companyId)
            ->where('status', 1)
            ->select('id', 'cd_ac_name', 'cd_ac_no', 'current_balance')
            ->get();

        return response()->json($cdAccounts);
    }
}
