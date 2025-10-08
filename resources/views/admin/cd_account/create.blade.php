@extends('layouts.admin')

@section('title', 'Create CD Account')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create New CD Account</h1>
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
        <form action="{{ route('admin.cd_account.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Selection -->
                <div>
                    <label for="comp_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Company <span class="text-red-500">*</span>
                    </label>
                    <select name="comp_id" id="comp_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('comp_id') == $company->id ? 'selected' : '' }}>
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
                            <option value="{{ $insurance }}" {{ old('insurance_name') == $insurance ? 'selected' : '' }}>
                                {{ $insurance }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Insurance Name (Hidden by default) -->
                <div id="customInsuranceDiv" class="hidden">
                    <label for="custom_insurance_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Custom Insurance Company Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="custom_insurance_name" id="custom_insurance_name"
                           value="{{ old('custom_insurance_name') }}"
                           placeholder="Enter insurance company name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- CD Account Name -->
                <div>
                    <label for="cd_ac_name" class="block text-sm font-medium text-gray-700 mb-2">
                        CD Account Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cd_ac_name" id="cd_ac_name" required
                           value="{{ old('cd_ac_name') }}"
                           placeholder="Enter CD account name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- CD Account Number -->
                <div>
                    <label for="cd_ac_no" class="block text-sm font-medium text-gray-700 mb-2">
                        CD Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cd_ac_no" id="cd_ac_no" required
                           value="{{ old('cd_ac_no') }}"
                           placeholder="Enter CD account number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Minimum Balance -->
                <div>
                    <label for="minimum_balance" class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Balance <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="minimum_balance" id="minimum_balance" required
                           value="{{ old('minimum_balance') }}"
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
                           value="{{ old('current_balance') }}"
                           placeholder="Enter current balance"
                           step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Statement File -->
                <div>
                    <label for="statment_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Statement File
                    </label>
                    <input type="file" name="statment_file" id="statment_file"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">
                        Accepted formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB)
                    </p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('admin.cd_account') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                    Create CD Account
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
