@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Company Master</h2>
        <button id="createCompanyBtn" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ Create Company</button>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-2">{{ session('success') }}</div>
    @endif
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded mb-4 flex items-center">
        <span class="material-icons mr-2">info</span>
        Create company records to manage your organizations.
    </div>
    <div class="overflow-x-auto">
        <table id="companyTable" class="min-w-full rounded-xl shadow-lg">
            <thead>
                <tr class="bg-indigo-500 text-white align-middle">
                    <th class="px-6 py-4 text-left font-semibold align-middle">Active</th>
                    <th class="px-6 py-4 text-left font-semibold align-middle">Name</th>
                    <th class="px-6 py-4 text-right font-semibold align-middle">Actions</th>
                </tr>
            </thead>
            <tbody id="companyTableBody">
                @foreach($companies as $company)
                <tr class="even:bg-indigo-50 hover:bg-indigo-100 transition align-middle">
                    <td class="px-6 py-4 align-middle">
                            <form method="POST" action="{{ route('admin.company_master.toggle', $company->id) }}">
                                @csrf
                                <button type="submit" class="focus:outline-none">
                                    <span class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in" style="top:0;">
                                        <span class="absolute left-0 top-0 w-10 h-6 rounded-full transition bg-gray-300 {{ $company->is_active ? 'bg-indigo-500' : '' }}"></span>
                                        <span class="absolute left-0 top-0 w-6 h-6 bg-white border rounded-full shadow transform transition {{ $company->is_active ? 'translate-x-4 border-indigo-500' : 'border-gray-300' }}"></span>
                                    </span>
                                </button>
                            </form>
                    </td>
                    <td class="px-6 py-4 align-middle">
                        <div class="flex items-center gap-4 h-full align-middle">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="h-8 w-8 rounded object-cover border" />
                            @endif
                            <span class="font-medium text-gray-800 text-base">{{ $company->comp_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 align-middle">
                        <div class="flex items-center justify-start gap-2 h-full align-middle">
                            <button class="editCompanyBtn text-indigo-600 hover:bg-indigo-100 p-2 rounded" data-id="{{ $company->id }}" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="17" width="18" height="4" rx="2"/><path d="M12 17V3m0 0l4 4m-4-4l-4 4"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.company_master.delete', $company->id) }}" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:bg-red-100 p-2 rounded" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2m2 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Modal for Create/Edit -->
    <div id="companyModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-lg relative">
            <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">&times;</button>
            <h3 id="modalTitle" class="text-xl font-bold mb-4">Create Company</h3>
            <form id="companyForm" method="POST" action="{{ route('admin.company_master.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="companyId">
                <div class="mb-4">
                    <label class="block text-left mb-1 font-semibold">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="comp_name" id="compName" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-left mb-1 font-semibold">Address</label>
                    <input type="text" name="address" id="address" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-left mb-1 font-semibold">Pincode</label>
                    <input type="text" name="pincode" id="pincode" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-left mb-1 font-semibold">Logo</label>
                    <div id="editLogoPreview" class="mb-2 hidden">
                        <img src="" alt="Current Logo" class="h-12 w-12 rounded object-cover border inline-block" />
                        <span class="text-xs text-gray-500 ml-2">Current logo</span>
                    </div>
                    <input type="file" name="logo" id="logo" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancelBtn" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // DataTable initialization
    const table = $('#companyTable').DataTable({
        paging: true,
        searching: true,
        info: true,
        lengthChange: false,
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [0, 1] }
        ]
    });
    // Modal logic
    $('#createCompanyBtn').on('click', function() {
        $('#modalTitle').text('Create Company');
        $('#companyForm').attr('action', "{{ route('admin.company_master.store') }}");
        $('#compName, #address, #pincode, #logo, #companyId').val('');
        $('#editLogoPreview').addClass('hidden');
        $('#companyModal').removeClass('hidden');
    });
    $('#closeModalBtn, #cancelBtn').on('click', function() {
        $('#companyModal').addClass('hidden');
    });
    $('.editCompanyBtn').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.get(`/admin/company-master/edit/${id}`, function(data) {
            $('#modalTitle').text('Edit Company');
            $('#companyForm').attr('action', `/admin/company-master/update/${data.id}`);
            $('#compName').val(data.comp_name);
            $('#address').val(data.address);
            $('#pincode').val(data.pincode);
            $('#logo').val('');
            $('#companyId').val(data.id);
            if (data.logo) {
                $('#editLogoPreview img').attr('src', `/storage/${data.logo}`);
                $('#editLogoPreview').removeClass('hidden');
            } else {
                $('#editLogoPreview').addClass('hidden');
            }
            $('#companyModal').removeClass('hidden');
        });
    });
});
</script>
@endsection
