@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-3xl font-bold text-gray-800">Claims Management</h2>
        <button id="addClaimBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow focus:outline-none flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Claim
        </button>
    </div>
    <!-- Modal for selecting policy -->
    <div id="claimModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button id="closeClaimModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h3 class="text-xl font-semibold mb-4">Select Policy to Create Claim</h3>
            <input type="text" id="policySearch" placeholder="Search by type, name, or number..." class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select id="policySelect" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" size="8">
                @foreach($policies as $policy)
                    <option value="{{ $policy->id }}" data-type="{{ $policy->product_name }}" data-number="{{ $policy->policy_number }}">
                        {{ $policy->product_name }} - {{ $policy->policy_number }}
                    </option>
                @endforeach
            </select>
            <button id="proceedClaimBtn" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Proceed</button>
        </div>
    </div>

    @if(count($claims) === 0)
        <div class="bg-yellow-100 text-yellow-800 p-6 rounded-lg">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-yellow-800 mb-2">No Claims Found</h3>
                <p>No claims have been submitted for your company yet.</p>
            </div>
        </div>
    @else
    <div class="bg-white rounded-xl shadow-lg">
        <!-- Tab Navigation -->
        <div class="border-b flex gap-2 px-6 pt-4">
            <button class="px-6 py-3 text-base font-semibold text-blue-600 border-b-2 border-blue-600 bg-white" id="regularTab">
                Regular Claims
            </button>
            <button class="px-6 py-3 text-base font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent bg-white" id="marineTab">
                Marine Claims
            </button>
        </div>

        <!-- Regular Claims Table -->
        <div id="regularClaimsTable" class="p-6">
            <h3 class="text-xl font-semibold mb-6 text-gray-700">Regular Claims</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table id="regularTable" class="min-w-full display nowrap text-sm" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Sr. No.</th>
                            <th class="px-4 py-2">Insured Name</th>
                            <th class="px-4 py-2">Policy No.</th>
                            <th class="px-4 py-2">Policy Type</th>
                            <th class="px-4 py-2">Policy Period</th>
                            <th class="px-4 py-2">Date of Loss</th>
                            <th class="px-4 py-2">Tentative Loss Estimate</th>
                            <th class="px-4 py-2">Brief Description</th>
                            <th class="px-4 py-2">Affected Items</th>
                            <th class="px-4 py-2">Loss Location</th>
                            <th class="px-4 py-2">Contact Person</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $regularIndex = 1; @endphp
                        @foreach($claims->where('is_marine', 0) as $claim)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">{{ $regularIndex++ }}</td>
                            <td class="px-4 py-2">{{ $claim->insured_name ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->policy_number ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->policy_type ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->policy_period ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->date_of_loss ? \Carbon\Carbon::parse($claim->date_of_loss)->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->estimated_loss_amount ? '₹' . number_format($claim->estimated_loss_amount, 2) : 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->brief_description_of_loss }}">
                                    {{ $claim->brief_description_of_loss ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->details_of_affected_items }}">
                                    {{ $claim->details_of_affected_items ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->complete_loss_location }}">
                                    {{ $claim->complete_loss_location ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2">{{ $claim->contact_person_name ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->contact_person_phone ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->contact_person_email ?: 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Marine Claims Table -->
        <div id="marineClaimsTable" class="p-6 hidden">
            <h3 class="text-xl font-semibold mb-6 text-gray-700">Marine Claims</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table id="marineTable" class="min-w-full display nowrap text-sm" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Sr. No.</th>
                            <th class="px-4 py-2">Name of Insured</th>
                            <th class="px-4 py-2">Policy Number</th>
                            <th class="px-4 py-2">Policy Period</th>
                            <th class="px-4 py-2">Consignor</th>
                            <th class="px-4 py-2">Consignee</th>
                            <th class="px-4 py-2">Invoice No. & Date</th>
                            <th class="px-4 py-2">Invoice Value</th>
                            <th class="px-4 py-2">LR/GR/Airway/B/L No. & Date</th>
                            <th class="px-4 py-2">Transporter</th>
                            <th class="px-4 py-2">Driver Details</th>
                            <th class="px-4 py-2">Vehicle/Container No.</th>
                            <th class="px-4 py-2">Estimated Loss</th>
                            <th class="px-4 py-2">Date of Loss</th>
                            <th class="px-4 py-2">Consignment Received Date</th>
                            <th class="px-4 py-2">Place of Loss</th>
                            <th class="px-4 py-2">Nature of Loss</th>
                            <th class="px-4 py-2">Survey Address</th>
                            <th class="px-4 py-2">SPOC Details</th>
                            <th class="px-4 py-2">Item Description</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $marineIndex = 1; @endphp
                        @foreach($claims->where('is_marine', 1) as $claim)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">{{ $marineIndex++ }}</td>
                            <td class="px-4 py-2">{{ $claim->name_of_insured ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->policy_number ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->policy_period ?: 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->consignor_name_address }}">
                                    {{ $claim->consignor_name_address ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->consignee_name_address }}">
                                    {{ $claim->consignee_name_address ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                {{ $claim->invoice_no ?: 'N/A' }}
                                @if($claim->invoice_date)
                                    <br><small>{{ \Carbon\Carbon::parse($claim->invoice_date)->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $claim->invoice_value ? '₹' . number_format($claim->invoice_value, 2) : 'N/A' }}</td>
                            <td class="px-4 py-2">
                                {{ $claim->lr_gr_airway_bl_no ?: 'N/A' }}
                                @if($claim->lr_gr_airway_bl_date)
                                    <br><small>{{ \Carbon\Carbon::parse($claim->lr_gr_airway_bl_date)->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $claim->transporter_name ?: 'N/A' }}</td>
                            <td class="px-4 py-2">
                                {{ $claim->driver_name ?: 'N/A' }}
                                @if($claim->driver_phone)
                                    <br><small>{{ $claim->driver_phone }}</small>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $claim->vehicle_container_no ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->estimated_loss_amount ? '₹' . number_format($claim->estimated_loss_amount, 2) : 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->date_of_loss ? \Carbon\Carbon::parse($claim->date_of_loss)->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->consignment_received_date ? \Carbon\Carbon::parse($claim->consignment_received_date)->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->place_of_loss ?: 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $claim->nature_of_loss ?: 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->survey_address }}">
                                    {{ $claim->survey_address ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                {{ $claim->spoc_name ?: 'N/A' }}
                                @if($claim->spoc_phone)
                                    <br><small>{{ $claim->spoc_phone }}</small>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $claim->item_commodity_description }}">
                                    {{ $claim->item_commodity_description ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold" title="View"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-800 font-semibold" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3h3z" /></svg></a>
                                    <a href="#" class="text-red-600 hover:text-red-800 font-semibold" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
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
    // Modal open/close
    $('#addClaimBtn').click(function(e) {
        e.preventDefault();
        $('#claimModal').removeClass('hidden');
        $('#policySearch').val('');
        $('#policySelect option').show();
    });
    $('#closeClaimModal').click(function() {
        $('#claimModal').addClass('hidden');
    });
    // Close modal on outside click
    $('#claimModal').on('click', function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });

    // Search filter for select
    $('#policySearch').on('input', function() {
        var val = $(this).val().toLowerCase();
        $('#policySelect option').each(function() {
            var type = ($(this).data('type') || '').toString().toLowerCase();
            var number = ($(this).data('number') || '').toString().toLowerCase();
            var text = $(this).text().toLowerCase();
            if (type.includes(val) || number.includes(val) || text.includes(val)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Proceed button
    $('#proceedClaimBtn').click(function() {
        var selected = $('#policySelect option:visible:selected');
        if (selected.length === 0) {
            alert('Please select a policy.');
            return;
        }
        var productName = selected.data('type');
        var policyId = selected.val();
        redirectToClaim(productName, policyId);
    });

    // Tab switching functionality
    $('#regularTab').click(function() {
        $('#regularTab').addClass('text-blue-600 border-b-2 border-blue-600').removeClass('text-gray-500');
        $('#marineTab').removeClass('text-blue-600 border-b-2 border-blue-600').addClass('text-gray-500');
        $('#regularClaimsTable').show();
        $('#marineClaimsTable').hide();
    });

    $('#marineTab').click(function() {
        $('#marineTab').addClass('text-blue-600 border-b-2 border-blue-600').removeClass('text-gray-500');
        $('#regularTab').removeClass('text-blue-600 border-b-2 border-blue-600').addClass('text-gray-500');
        $('#marineClaimsTable').show();
        $('#regularClaimsTable').hide();
    });

    // Initialize DataTables
    @if(count($claims) > 0)
    var commonConfig = {
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
                orientation: 'landscape'
            },
            {
                extend: 'print',
                text: 'Print'
            }
        ],
        columnDefs: [
            {
                targets: [0], // Sr. No. column
                className: 'text-center'
            }
        ],
        scrollX: true
    };

    // Regular Claims DataTable
    $('#regularTable').DataTable($.extend({}, commonConfig, {
        order: [[5, 'desc']], // Order by Date of Loss
        language: {
            search: "Search regular claims:",
            lengthMenu: "Show _MENU_ regular claims per page",
            info: "Showing _START_ to _END_ of _TOTAL_ regular claims"
        }
    }));

    // Marine Claims DataTable
    $('#marineTable').DataTable($.extend({}, commonConfig, {
        order: [[13, 'desc']], // Order by Date of Loss
        language: {
            search: "Search marine claims:",
            lengthMenu: "Show _MENU_ marine claims per page",
            info: "Showing _START_ to _END_ of _TOTAL_ marine claims"
        }
    }));
    @endif
});

// Function to redirect based on policy type
function redirectToClaim(productName, policyId) {
    // Hide the dropdown
    $('#claimTypeMenu').addClass('hidden');

    // Check if the product name contains marine-related keywords
    const marineKeywords = ['marine', 'cargo', 'transit', 'shipping', 'freight', 'vessel', 'ocean', 'sea'];
    const isMarinePolicy = marineKeywords.some(keyword =>
        productName.toLowerCase().includes(keyword.toLowerCase())
    );

    // Redirect based on policy type
    if (isMarinePolicy) {
        // Redirect to marine claim page
        window.location.href = "{{ route('user.claims.create', ['type' => 'marine']) }}" + "?policy_id=" + policyId;
    } else {
        // Redirect to regular claim page
        window.location.href = "{{ route('user.claims.create', ['type' => 'regular']) }}" + "?policy_id=" + policyId;
    }
}
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

/* Tab styling */
.border-b-2 {
    border-bottom-width: 2px;
}

/* Text truncation for long content */
.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection
