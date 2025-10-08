@extends('layouts.user')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">CD Accounts</h1>
        </div>

        <div class="overflow-x-auto">
            <table id="cdAccountsTable" class="min-w-full table-auto border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">Insurance Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">CD Account Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">CD Account No.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">Minimum Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">Current Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">Statement File</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cdAccounts as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 text-sm text-gray-900 border-b border-gray-300">{{ $account->insurance_name }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 border-b border-gray-300">{{ $account->cd_ac_name }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 border-b border-gray-300">{{ $account->cd_ac_no }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 border-b border-gray-300">
                            <span class="font-medium">₹{{ number_format($account->minimum_balance, 2) }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 border-b border-gray-300">
                            <span class="font-medium">₹{{ number_format($account->current_balance, 2) }}</span>
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-900 border-b border-gray-300">
                            @if($account->statment_file)
                                <a href="{{ asset( $account->statment_file) }}"
                                   target="_blank"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="material-icons text-sm mr-1">description</i>
                                    View Statement
                                </a>
                            @else
                                <span class="text-gray-400">No Statement</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm border-b border-gray-300">
                            @if($account->status == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="material-icons text-xs mr-1">check_circle</i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="material-icons text-xs mr-1">cancel</i>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm border-b border-gray-300">
                            <a href="{{ route('user.cd_accounts.transactions', $account->id) }}"
                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="material-icons text-sm mr-1">visibility</i>
                                View Transactions
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">
                            <i class="material-icons text-4xl text-gray-300 mb-2">account_balance</i>
                            <p>No CD accounts found for your company</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

<script>
$(document).ready(function() {
    $('#cdAccountsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'asc']], // Order by CD Account Name
        columnDefs: [
            { orderable: false, targets: [5, 6, 8] } // CD Folder, Statement File, and Actions columns not orderable
        ]
    });
});
</script>
@endsection
