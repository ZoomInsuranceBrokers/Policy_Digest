<?php

namespace App\Http\Controllers;

use App\Models\CompanyMaster;
use Illuminate\Http\Request;

class CompanyMasterController extends Controller
{
    public function index()
    {
        $companies = CompanyMaster::where('is_delete', 0)->get();
        return view('admin.company_master', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comp_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'pincode' => 'nullable|string|max:20',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $validated['is_delete'] = 0;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }
        CompanyMaster::create($validated);
        return redirect()->route('admin.company_master')->with('success', 'Company created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'comp_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'pincode' => 'nullable|string|max:20',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $company = CompanyMaster::findOrFail($id);
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        } else {
            unset($validated['logo']);
        }
        $company->update($validated);
        return redirect()->route('admin.company_master')->with('success', 'Company updated successfully.');
    }

    public function edit($id)
    {
        $company = CompanyMaster::findOrFail($id);
        return response()->json($company);
    }

    public function destroy($id)
    {
        $company = CompanyMaster::findOrFail($id);
        $company->is_delete = 1;
        $company->save();
        return redirect()->route('admin.company_master')->with('success', 'Company deleted successfully.');
    }

    public function toggleActive($id)
    {
        $company = CompanyMaster::findOrFail($id);
        $company->is_active = !$company->is_active;
        $company->save();
        return redirect()->route('admin.company_master')->with('success', 'Company status updated.');
    }
}
