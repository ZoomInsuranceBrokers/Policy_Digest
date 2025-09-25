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
@endsection
