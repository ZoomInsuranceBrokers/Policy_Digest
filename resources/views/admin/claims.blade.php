@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-8 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Claims by Company</h2>
    <div class="bg-white rounded-xl shadow-lg mb-8">
        <h3 class="text-xl font-semibold mb-6 text-gray-700 px-6 pt-6">Select a Company</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200 px-2 md:px-6 pb-6">
            <table id="adminCompanyTable" class="min-w-full display nowrap text-sm" style="width:100%">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Company Name</th>
                        <th class="px-4 py-2">Total Claims</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $company->comp_name }}</td>
                        <td class="px-4 py-2">{{ $company->claims_count }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.claims.company', $company->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">View Claims</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#adminCompanyTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
    });
});
</script>
@endsection
