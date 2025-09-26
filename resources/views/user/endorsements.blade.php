@extends('layouts.user')

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-4">
        <a href="{{ route('user.portfolio') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Portfolio
        </a>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-2xl font-bold mb-2">Endorsement Copies</h2>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Policy: {{ $policy->product_name }}</h3>
            <p class="text-gray-600">Policy Number: {{ $policy->policy_number }}</p>
            <p class="text-gray-600">Insurance Company: {{ $policy->insurance_company_name }}</p>
        </div>

        @if($endorsements->count() > 0)
        <div class="overflow-x-auto">
            <table id="endorsementsTable" class="min-w-full display nowrap" style="width:100%">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Sr. No.</th>
                        <th class="px-4 py-2">File Name</th>
                        <th class="px-4 py-2">Document</th>
                        <th class="px-4 py-2">Upload Date</th>
                        <th class="px-4 py-2">Upload Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($endorsements as $index => $endorsement)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">
                            <span class="font-medium text-gray-900">{{ $endorsement->remark ?: 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ asset('storage/' . $endorsement->document) }}"
                               target="_blank"
                               class="inline-flex items-center bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Document
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($endorsement->created_at)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($endorsement->created_at)->format('h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <div class="bg-gray-100 rounded-lg p-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Endorsements</h3>
                <p class="mt-1 text-gray-500">This policy does not have any endorsement copies uploaded yet.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    @if($endorsements->count() > 0)
    $('#endorsementsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[3, 'desc']], // Order by upload date descending
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy'
            },
            {
                extend: 'csv',
                text: 'CSV'
            },
            {
                extend: 'excel',
                text: 'Excel'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'Endorsements - {{ $policy->product_name }} ({{ $policy->policy_number }})'
            },
            {
                extend: 'print',
                text: 'Print',
                title: 'Endorsements - {{ $policy->product_name }} ({{ $policy->policy_number }})'
            }
        ],
        columnDefs: [
            {
                targets: [0], // Sr. No. column
                className: 'text-center'
            },
            {
                targets: [2], // Document column
                orderable: false
            }
        ],
        language: {
            search: "Search endorsements:",
            lengthMenu: "Show _MENU_ endorsements per page",
            info: "Showing _START_ to _END_ of _TOTAL_ endorsements",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            emptyTable: "No endorsements found for this policy"
        }
    });
    @endif
});
</script>

<style>
/* DataTables styling to work with Tailwind CSS */
.dataTables_wrapper .dt-buttons {
    margin-bottom: 15px;
}

.dataTables_wrapper .dt-buttons .dt-button {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 6px 12px;
    margin-right: 5px;
    border-radius: 4px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.dataTables_wrapper .dt-buttons .dt-button:hover {
    background: #2563eb;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 6px 12px;
    margin-left: 8px;
}

.dataTables_wrapper .dataTables_length select {
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 4px 8px;
    margin: 0 8px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid #d1d5db;
    background: white;
    color: #374151;
    padding: 6px 12px;
    margin: 0 2px;
    border-radius: 4px;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}
</style>
@endsection
