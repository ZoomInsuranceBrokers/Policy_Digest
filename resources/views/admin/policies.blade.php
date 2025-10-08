@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-4">
    <h2 class="text-2xl font-bold mb-4">Policies for {{ $company->comp_name }}</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="px-4 py-2">Product Name</th>
                    <th class="px-4 py-2">Policy Number</th>
                    <th class="px-4 py-2">Start Date</th>
                    <th class="px-4 py-2">Expiry Date</th>
                    <th class="px-4 py-2">Sum Insured</th>
                    <th class="px-4 py-2">PBST</th>
                    <th class="px-4 py-2">Gross Premium</th>
                    <th class="px-4 py-2">Insurance Company Name</th>
                    <th class="px-4 py-2">Cash Deposit</th>
                    <th class="px-4 py-2">CD Account</th>
                    <th class="px-4 py-2">Policy Copy</th>
                    <th class="px-4 py-2">Endorsement Copies</th>
                </tr>
            </thead>
            <tbody>
                @foreach($policies as $policy)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $policy->product_name }}</td>
                    <td class="px-4 py-2">{{ $policy->policy_number }}</td>
                    <td class="px-4 py-2">{{ $policy->start_date }}</td>
                    <td class="px-4 py-2">{{ $policy->expiry_date }}</td>
                    <td class="px-4 py-2">{{ $policy->sum_insured }}</td>
                    <td class="px-4 py-2">{{ $policy->pbst }}</td>
                    <td class="px-4 py-2">{{ $policy->gross_premium }}</td>
                    <td class="px-4 py-2">{{ $policy->insurance_company_name }}</td>
                    <td class="px-4 py-2">{{ $policy->cash_deposit }}</td>
                    <td class="px-4 py-2">
                        @if($policy->cdAccount)
                            <div class="flex items-center gap-2">
                                <div class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">
                                    <div class="font-semibold">{{ $policy->cdAccount->cd_ac_name }}</div>
                                    <div class="text-xs">{{ $policy->cdAccount->cd_ac_no }}</div>
                                </div>
                                <button onclick="openCdAccountModal({{ $policy->id }}, '{{ $policy->cdAccount->cd_ac_name }}', {{ $company->id }})"
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs">
                                    Change
                                </button>
                            </div>
                        @else
                            <button onclick="openCdAccountModal({{ $policy->id }}, null, {{ $company->id }})"
                                    class="bg-orange-100 hover:bg-orange-200 text-orange-800 px-3 py-1 rounded text-sm font-medium">
                                + Add CD Account
                            </button>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if($policy->policy_copy)
                            <div class="flex items-center gap-2">
                                <a href="{{ asset('storage/' . $policy->policy_copy) }}" target="_blank" class="text-indigo-600 underline">View</a>
                                <form action="{{ route('admin.portfolio.upload_policy_copy', $policy->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-1 upload-form">
                                    @csrf
                                    <input type="file" name="policy_copy" id="reupload-file-input-{{ $policy->id }}" class="hidden" required onchange="this.form.submit()">
                                    <label for="reupload-file-input-{{ $policy->id }}" class="cursor-pointer flex items-center gap-1 bg-yellow-100 hover:bg-yellow-200 px-2 py-1 rounded border border-yellow-400" title="Re-upload">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                        </svg>
                                        <span class="text-yellow-700 text-xs font-semibold">Re-upload</span>
                                    </label>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('admin.portfolio.upload_policy_copy', $policy->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 upload-form">
                                @csrf
                                <input type="file" name="policy_copy" id="file-input-{{ $policy->id }}" class="hidden" required onchange="this.form.submit()">
                                <label for="file-input-{{ $policy->id }}" class="cursor-pointer flex items-center gap-1 bg-green-100 hover:bg-green-200 px-2 py-1 rounded border border-green-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                    </svg>
                                    <span class="text-green-700 text-xs font-semibold">Select & Upload</span>
                                </label>
                            </form>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('admin.portfolio.view_endorsements', $policy->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- CD Account Assignment Modal -->
<div id="cdAccountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Assign CD Account</h3>
                <button onclick="closeCdAccountModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="cdAccountForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="cd_ac_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select CD Account
                    </label>
                    <select name="cd_ac_id" id="cd_ac_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select CD Account</option>
                    </select>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Only CD accounts belonging to this company will be shown.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeCdAccountModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                        Assign CD Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPolicyId = null;

function openCdAccountModal(policyId, currentCdAccount, companyId) {
    currentPolicyId = policyId;
    const modal = document.getElementById('cdAccountModal');
    const form = document.getElementById('cdAccountForm');
    const select = document.getElementById('cd_ac_id');

    // Set form action
    form.action = `/admin/portfolio/assign-cd-account/${policyId}`;

    // Clear previous options
    select.innerHTML = '<option value="">Select CD Account</option>';

    // Fetch CD accounts for this company
    fetch(`/admin/cd-account/by-company/${companyId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(cdAccount => {
                const option = document.createElement('option');
                option.value = cdAccount.id;
                option.textContent = `${cdAccount.cd_ac_name} (${cdAccount.cd_ac_no})`;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching CD accounts:', error);
            alert('Error loading CD accounts. Please try again.');
        });

    modal.classList.remove('hidden');
}

function closeCdAccountModal() {
    const modal = document.getElementById('cdAccountModal');
    modal.classList.add('hidden');
    currentPolicyId = null;
}

// Handle form submission
document.getElementById('cdAccountForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const cdAccountId = formData.get('cd_ac_id');

    if (!cdAccountId) {
        alert('Please select a CD account');
        return;
    }

    // Get CSRF token from the form
    const csrfToken = formData.get('_token');

    fetch(this.action, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            cd_ac_id: cdAccountId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeCdAccountModal();
            location.reload(); // Reload page to show updated data
        } else {
            alert('Error assigning CD account: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error assigning CD account. Please try again.');
    });
});
</script>
@endsection
