<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyMaster;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Excel;
use Carbon\Carbon;

class ClaimsController extends Controller
{
    // Show all companies with claim counts
    public function index()
    {
        $companies = CompanyMaster::withCount('claims')->get();
        return view('admin.claims', compact('companies'));
    }

    // Show claims for a specific company, with filter
    public function companyClaims(Request $request, $companyId)
    {
        $company = CompanyMaster::findOrFail($companyId);
        $query = $company->claims();
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        $claims = $query->orderByDesc('created_at')->get();
        return view('admin.company_claims', compact('company', 'claims'));
    }

    // Export filtered claims to Excel
    public function export(Request $request, $companyId)
    {
        $company = CompanyMaster::findOrFail($companyId);
        $type = $request->get('type');
        $query = $company->claims();
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        if ($type === 'regular') {
            $query->where('is_marine', 0);
            $columns = [
                'id', 'policy_number', 'policy_period', 'estimated_loss_amount', 'date_of_loss',
                'insured_name', 'policy_type', 'brief_description_of_loss', 'details_of_affected_items',
                'complete_loss_location', 'contact_person_name', 'contact_person_phone', 'contact_person_email',
                'status', 'created_at',
            ];
            $filename = 'regular_claims_'.$company->id.'_'.now()->format('Ymd_His').'.csv';
        } elseif ($type === 'marine') {
            $query->where('is_marine', 1);
            $columns = [
                'id', 'policy_number', 'policy_period', 'estimated_loss_amount', 'date_of_loss',
                'name_of_insured', 'consignor_name_address', 'consignee_name_address', 'invoice_no', 'invoice_date',
                'invoice_value', 'lr_gr_airway_bl_no', 'lr_gr_airway_bl_date', 'transporter_name', 'driver_name',
                'driver_phone', 'vehicle_container_no', 'consignment_received_date', 'place_of_loss', 'nature_of_loss',
                'survey_address', 'spoc_name', 'spoc_phone', 'item_commodity_description', 'status', 'created_at',
            ];
            $filename = 'marine_claims_'.$company->id.'_'.now()->format('Ymd_His').'.csv';
        } else {
            // fallback: all claims, minimal columns
            $columns = ['id', 'policy_number', 'is_marine', 'status', 'created_at'];
            $filename = 'claims_'.$company->id.'_'.now()->format('Ymd_His').'.csv';
        }
        $claims = $query->orderByDesc('created_at')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function() use ($claims, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($claims as $claim) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = $claim[$col] ?? '';
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
