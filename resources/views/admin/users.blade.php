@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 5.87v-2a4 4 0 00-3-3.87m6 5.87v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
        Users Management
    </h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <button id="openUserModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-6">+ Add User</button>
    <!-- User Add Modal -->
    <div id="userModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <button id="closeUserModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h3 class="text-xl font-bold mb-4">Add User</h3>
            <form id="userAddForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="first_name" placeholder="First Name" class="border p-2 rounded" required>
                    <input type="text" name="last_name" placeholder="Last Name" class="border p-2 rounded" required>
                    <input type="email" name="email" placeholder="Email" class="border p-2 rounded md:col-span-2" required>
                    <select name="role_id" id="roleSelect" class="border p-2 rounded" required>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                    <select name="company_id" id="companySelect" class="border p-2 rounded hidden">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->comp_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2 text-sm text-gray-500">Default password for all users is <span class="font-mono">password</span></div>
                <div class="flex justify-end mt-4">
                    <button type="button" id="cancelUserModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add User</button>
                </div>
            </form>
        </div>
    </div>
    <div class="bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200" id="users-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $i+1 }}</td>
                    <td class="px-4 py-2">{{ $user->full_name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 capitalize">{{ $user->role_id }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                        <!-- Edit button could open a modal for inline editing (not implemented here) -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && $('#users-table').DataTable) {
            $('#users-table').DataTable();
        }
        // Modal logic
        const openBtn = document.getElementById('openUserModal');
        const modal = document.getElementById('userModal');
        const closeBtn = document.getElementById('closeUserModal');
        const cancelBtn = document.getElementById('cancelUserModal');
        openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
        cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
        // Show company select if role is user
        const roleSelect = document.getElementById('roleSelect');
        const companySelect = document.getElementById('companySelect');
        function toggleCompanySelect() {
            if (roleSelect.value === '2' || roleSelect.value === 2) {
                companySelect.classList.remove('hidden');
                companySelect.required = true;
            } else {
                companySelect.classList.add('hidden');
                companySelect.required = false;
            }
        }
        roleSelect.addEventListener('change', toggleCompanySelect);
        toggleCompanySelect();
    });
</script>
@endsection
