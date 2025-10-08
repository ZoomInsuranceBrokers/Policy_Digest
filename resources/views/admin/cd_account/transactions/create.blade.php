@extends('layouts.admin')

@section('title', 'Add Transaction - ' . $cdAccount->cd_ac_name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Transaction</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Add a transaction for CD Account: <span class="font-semibold">{{ $cdAccount->cd_ac_name }} ({{ $cdAccount->cd_ac_no }})</span>
                    </p>
                </div>
                <a href="{{ route('admin.cd_account.transactions', $cdAccount->id) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Transactions
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Transaction Details</h2>
            </div>

            <form action="{{ route('admin.cd_account.transactions.store', $cdAccount->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transaction Type -->
                    <div>
                        <label for="credit_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction Type <span class="text-red-500">*</span>
                        </label>
                        <select name="credit_type" id="credit_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Transaction Type</option>
                            <option value="CREDIT" {{ old('credit_type') == 'CREDIT' ? 'selected' : '' }}>Credit (+)</option>
                            <option value="DEBIT" {{ old('credit_type') == 'DEBIT' ? 'selected' : '' }}>Debit (-)</option>
                        </select>
                        @error('credit_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transaction Amount -->
                    <div>
                        <label for="transaction_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" name="transaction_amount" id="transaction_amount" required
                                   value="{{ old('transaction_amount') }}"
                                   placeholder="0.00"
                                   step="0.01" min="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('transaction_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transaction Date -->
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="transaction_date" id="transaction_date" required
                               value="{{ old('transaction_date', date('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('transaction_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Upload -->
                    <div>
                        <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                            Supporting Document
                        </label>
                        <div class="relative">
                            <input type="file" name="document" id="document"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB)
                        </p>
                        @error('document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Enter transaction description or notes..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.cd_account.transactions', $cdAccount->id) }}"
                       class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                        Add Transaction
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Info Card -->
        <div class="mt-6 bg-blue-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Account Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p><strong>Account Name:</strong> {{ $cdAccount->cd_ac_name }}</p>
                        <p><strong>Account Number:</strong> {{ $cdAccount->cd_ac_no }}</p>
                        <p><strong>Company:</strong> {{ $cdAccount->company ? $cdAccount->company->company_name : 'N/A' }}</p>
                        <p><strong>Current Balance:</strong> ₹{{ number_format($cdAccount->current_balance ?? 0, 2) }}</p>
                        <p><strong>Minimum Balance:</strong> ₹{{ number_format($cdAccount->minimum_balance, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const creditTypeSelect = document.getElementById('credit_type');
    const amountInput = document.getElementById('transaction_amount');

    // Add visual feedback for transaction type
    creditTypeSelect.addEventListener('change', function() {
        const container = amountInput.parentElement;
        container.classList.remove('credit-highlight', 'debit-highlight');

        if (this.value === 'CREDIT') {
            container.classList.add('credit-highlight');
        } else if (this.value === 'DEBIT') {
            container.classList.add('debit-highlight');
        }
    });
});
</script>

<style>
.credit-highlight input {
    border-color: #10b981;
    background-color: #f0fdf4;
}

.debit-highlight input {
    border-color: #ef4444;
    background-color: #fef2f2;
}
</style>
@endsection
