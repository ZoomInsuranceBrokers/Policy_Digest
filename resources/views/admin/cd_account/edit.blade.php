@extends('layouts.admin')

@section('title', 'Edit CD Account')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit CD Account</h1>
        <a href="{{ route('admin.cd_account') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Back to CD Accounts
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.cd_account.update', $cdAccount->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Selection -->
                <div>
                    <label for="comp_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Company <span class="text-red-500">*</span>
                    </label>
                    <select name="comp_id" id="comp_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ (old('comp_id') ?? $cdAccount->comp_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->comp_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Insurance Company Selection -->
                <div>
                    <label for="insurance_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Insurance Company <span class="text-red-500">*</span>
                    </label>
                    <select name="insurance_name" id="insurance_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Insurance Company</option>
                        @foreach($insuranceCompanies as $insurance)
                            <option value="{{ $insurance }}" {{ (old('insurance_name') ?? $cdAccount->insurance_name) == $insurance ? 'selected' : '' }}>
                                {{ $insurance }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Insurance Name (Hidden by default) -->
                <div id="customInsuranceDiv" class="{{ !in_array((old('insurance_name') ?? $cdAccount->insurance_name), $insuranceCompanies) && (old('insurance_name') ?? $cdAccount->insurance_name) ? '' : 'hidden' }}">
                    <label for="custom_insurance_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Custom Insurance Company Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="custom_insurance_name" id="custom_insurance_name"
                           value="{{ !in_array((old('insurance_name') ?? $cdAccount->insurance_name), $insuranceCompanies) ? (old('custom_insurance_name') ?? $cdAccount->insurance_name) : old('custom_insurance_name') }}"
                           placeholder="Enter insurance company name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- CD Account Name -->
                <div>
                    <label for="cd_ac_name" class="block text-sm font-medium text-gray-700 mb-2">
                        CD Account Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cd_ac_name" id="cd_ac_name" required
                           value="{{ old('cd_ac_name') ?? $cdAccount->cd_ac_name }}"
                           placeholder="Enter CD account name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- CD Account Number -->
                <div>
                    <label for="cd_ac_no" class="block text-sm font-medium text-gray-700 mb-2">
                        CD Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cd_ac_no" id="cd_ac_no" required
                           value="{{ old('cd_ac_no') ?? $cdAccount->cd_ac_no }}"
                           placeholder="Enter CD account number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Minimum Balance -->
                <div>
                    <label for="minimum_balance" class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Balance <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="minimum_balance" id="minimum_balance" required
                           value="{{ old('minimum_balance') ?? $cdAccount->minimum_balance }}"
                           placeholder="Enter minimum balance"
                           step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Current Balance -->
                <div>
                    <label for="current_balance" class="block text-sm font-medium text-gray-700 mb-2">
                        Current Balance <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="current_balance" id="current_balance" required
                           value="{{ old('current_balance') ?? $cdAccount->current_balance }}"
                           placeholder="Enter current balance"
                           step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Current Statement File -->
                @if($cdAccount->statment_file)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Current Statement File
                    </label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <a href="{{ asset($cdAccount->cd_folder . '/' . $cdAccount->statment_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Current File
                        </a>
                    </div>
                </div>
                @endif

                <!-- New Statement File -->
                <div>
                    <label for="statment_file" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $cdAccount->statment_file ? 'Replace Statement File' : 'Statement File' }}
                    </label>
                    <input type="file" name="statment_file" id="statment_file"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">
                        Accepted formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB)
                        @if($cdAccount->statment_file)
                            <br>Leave empty to keep current file.
                        @endif
                    </p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="1" {{ (old('status') ?? $cdAccount->status) == 1 ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ (old('status') ?? $cdAccount->status) == 0? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('admin.cd_account') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                    Update CD Account
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const insuranceSelect = document.getElementById('insurance_name');
    const customDiv = document.getElementById('customInsuranceDiv');
    const customInput = document.getElementById('custom_insurance_name');

    insuranceSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDiv.classList.remove('hidden');
            customInput.required = true;
        } else {
            customDiv.classList.add('hidden');
            customInput.required = false;
            customInput.value = '';
        }
    });

    // Check initial state
    if (insuranceSelect.value === 'custom') {
        customDiv.classList.remove('hidden');
        customInput.required = true;
    }
});
</script>
@endpush
@endsection
