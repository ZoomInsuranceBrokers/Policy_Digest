@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">My Portfolio</h2>
    @if(count($policies) === 0)
        <div class="bg-yellow-100 text-yellow-800 p-6 rounded-lg text-center">No policies found for your company.</div>
    @else
    <div class="bg-white rounded-xl shadow-lg mb-8">
        <h3 class="text-xl font-semibold mb-6 text-gray-700 px-6 pt-6">Policy Portfolio</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200 px-6 pb-6">
            <table id="portfolioTable" class="min-w-full display nowrap text-sm" style="width:100%">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Product Name</th>
                        <th class="px-4 py-2">Policy Number</th>
                        <th class="px-4 py-2">Start Date</th>
                        <th class="px-4 py-2">Expiry Date</th>
                        <th class="px-4 py-2">Sum Insured</th>
                        <th class="px-4 py-2">PBST</th>
                        <th class="px-4 py-2">Gross Premium</th>
                        <th class="px-4 py-2">Insurance Company</th>
                        <th class="px-4 py-2">Cash Deposit</th>
                        <th class="px-4 py-2">Policy Copy</th>
                        <th class="px-4 py-2">Endorsements</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($policies as $policy)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $policy->product_name }}</td>
                        <td class="px-4 py-2">{{ $policy->policy_number }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($policy->start_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($policy->expiry_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">₹{{ number_format($policy->sum_insured, 2) }}</td>
                        <td class="px-4 py-2">{{ $policy->pbst ?? 'N/A' }}</td>
                        <td class="px-4 py-2">₹{{ number_format($policy->gross_premium, 2) }}</td>
                        <td class="px-4 py-2">{{ $policy->insurance_company_name }}</td>
                        <td class="px-4 py-2">{{ $policy->cash_deposit ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @if($policy->policy_copy)
                                <a href="{{ asset('storage/' . $policy->policy_copy) }}" class="text-blue-600 underline" target="_blank">View Document</a>
                            @else
                                <span class="text-gray-400">No Copy</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @php
                                $policyEndorsements = $endorsements->where('policy_portfolio_id', $policy->id);
                            @endphp
                            @if($policyEndorsements->count())
                                <a href="{{ route('user.portfolio.endorsements', $policy->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">View Endorsements ({{ $policyEndorsements->count() }})</a>
                            @else
                                <span class="text-gray-400">No Endorsements</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#portfolioTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[2, 'desc']], // Order by start date descending
        columnDefs: [
            {
                targets: [4, 6], // Sum Insured and Gross Premium columns
                className: 'text-right'
            },
            {
                targets: [9, 10], // Policy Copy and Endorsements columns
                orderable: false
            }
        ],
        language: {
            search: "Search policies:",
            lengthMenu: "Show _MENU_ policies per page",
            info: "Showing _START_ to _END_ of _TOTAL_ policies",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});
</script>

<style>
/* DataTables styling to work with Tailwind CSS */
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
