@extends('layouts.user')

@section('content')
    <div class="container mx-auto p-4">
        <div class="mb-4">
            <a href="{{ route('user.claims') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Claims
            </a>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-2xl font-bold mb-6">
                {{ $type === 'marine' ? 'Create Marine Claim' : 'Create Regular Claim' }}
            </h2>

            <form action="{{ route('user.claims.store') }}" method="POST">
                @csrf
                <input type="hidden" name="is_marine" value="{{ $isMarine }}">

                <!-- Policy Details (auto-filled, readonly) -->
                @if(isset($selectedPolicy))
                    <input type="hidden" name="policy_portfolio_id" value="{{ $selectedPolicy->id }}">
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Policy Name</label>
                        <input type="text" name="policy_name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $selectedPolicy->product_name ?? '' }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Insured Name</label>
                        <input type="text" name="insured_name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $selectedPolicy->company->comp_name ?? '' }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Policy Number</label>
                        <input type="text" name="policy_number"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $selectedPolicy->policy_number ?? '' }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Policy Period</label>
                        <input type="text" name="policy_period"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $selectedPolicy ? \Carbon\Carbon::parse($selectedPolicy->start_date)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($selectedPolicy->expiry_date)->format('d/m/Y') : '' }}"
                            readonly>
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Loss</label>
                        <input type="date" name="date_of_loss"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Loss Amount</label>
                        <input type="number" step="0.01" name="estimated_loss_amount"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                @push('scripts')
                    <script>
                        $(document).ready(function() {
                            function setFieldsFromPolicy(option) {
                                if (!option || option.value === "") {
                                    $('#policyNameInput').val("");
                                    $('#insuredNameInput').val("");
                                    $('#policyNumberInput').val("");
                                    $('#policyPeriodInput').val("");
                                    $('#policyNameInput, #insuredNameInput, #policyNumberInput, #policyPeriodInput').prop(
                                        'readonly', true);
                                } else if (option.value === "other") {
                                    $('#policyNameInput').val("").prop('readonly', false);
                                    $('#insuredNameInput').val("").prop('readonly', false);
                                    $('#policyNumberInput').val("").prop('readonly', false);
                                    $('#policyPeriodInput').val("").prop('readonly', false);
                                } else {
                                    var $opt = $(option);
                                    $('#policyNameInput').val($opt.data('product')).prop('readonly', true);
                                    $('#insuredNameInput').val($opt.data('insured')).prop('readonly', true);
                                    $('#policyNumberInput').val($opt.data('number')).prop('readonly', true);
                                    $('#policyPeriodInput').val($opt.data('period')).prop('readonly', true);
                                }
                            }

                            setFieldsFromPolicy($('#policySelect')[0].selectedOptions[0]);
                            $('#policySelect').on('change', function() {
                                setFieldsFromPolicy(this.selectedOptions[0]);
                            });
                        });
                    </script>
                @endpush

                @if ($type === 'marine')
                    <!-- Marine Claims Fields -->
                    <h3 class="text-lg font-semibold mb-4 text-blue-600">Marine Claim Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name of Insured</label>
                            <input type="text" name="name_of_insured"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transporter Name</label>
                            <input type="text" name="transporter_name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consignor Name & Address</label>
                            <textarea name="consignor_name_address" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consignee Name & Address</label>
                            <textarea name="consignee_name_address" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invoice No.</label>
                            <input type="text" name="invoice_no"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date</label>
                            <input type="date" name="invoice_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Value</label>
                            <input type="number" step="0.01" name="invoice_value"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LR/GR/Airway Bill/B/L No.</label>
                            <input type="text" name="lr_gr_airway_bl_no"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LR/GR/Airway Bill/B/L Date</label>
                            <input type="date" name="lr_gr_airway_bl_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Driver Name</label>
                            <input type="text" name="driver_name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Driver Phone</label>
                            <input type="tel" name="driver_phone"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle/Container No.</label>
                            <input type="text" name="vehicle_container_no"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consignment Received Date</label>
                            <input type="date" name="consignment_received_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Place of Loss</label>
                            <input type="text" name="place_of_loss"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nature of Loss</label>
                        <select name="nature_of_loss"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select nature of loss</option>
                            <option value="Jerks & Jolts">Jerks & Jolts</option>
                            <option value="Theft">Theft</option>
                            <option value="Vehicle Accident">Vehicle Accident</option>
                            <option value="Wet Damage">Wet Damage</option>
                            <option value="Fire Damage">Fire Damage</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Survey Address</label>
                            <textarea name="survey_address" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item/Commodity Description</label>
                            <textarea name="item_commodity_description" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SPOC Name</label>
                            <input type="text" name="spoc_name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SPOC Phone</label>
                            <input type="tel" name="spoc_phone"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                @else
                    <!-- Regular Claims Fields -->
                    <h3 class="text-lg font-semibold mb-4 text-blue-600">Regular Claim Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Insured Name</label>
                            <input type="text" name="insured_name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Policy Type</label>
                            <input type="text" name="policy_type"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brief Description of Loss</label>
                        <textarea name="brief_description_of_loss" rows="4"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="How did the loss happen?"></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Details of Affected Items</label>
                        <textarea name="details_of_affected_items" rows="4"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Complete Loss Location (Address with
                            Pincode)</label>
                        <textarea name="complete_loss_location" rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Where survey needs to be done"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person Name</label>
                            <input type="text" name="contact_person_name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person Phone</label>
                            <input type="tel" name="contact_person_phone"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person Email</label>
                            <input type="email" name="contact_person_email"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('user.claims') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        Submit Claim
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
