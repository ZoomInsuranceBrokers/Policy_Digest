@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Endorsement Copies for {{ $policy->product_name }} ({{ $policy->policy_number }})</h2>
    <form action="{{ route('admin.portfolio.upload_endorsement', $policy->id) }}" method="POST" enctype="multipart/form-data" class="mb-6 flex flex-col md:flex-row gap-4 items-end">
        @csrf
        <div class="flex-1">
            <label class="block font-semibold mb-1">Select Endorsement Copies (multiple)</label>
            <div class="relative flex items-center gap-2">
                <input type="file" name="document[]" id="endorsement-files" multiple class="hidden" required onchange="updateFileList()">
                <label for="endorsement-files" class="cursor-pointer flex items-center gap-1 bg-green-100 hover:bg-green-200 px-3 py-2 rounded border border-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                    </svg>
                    <span class="text-green-700 text-xs font-semibold">Select Files</span>
                </label>
                <button type="button" onclick="clearFiles()" class="ml-2 text-xs text-red-600 underline hidden" id="clear-files-btn">Reselect</button>
                <span id="selected-files" class="ml-2 text-xs text-gray-700"></span>
            </div>
        </div>
        <div class="flex-1">
            <label class="block font-semibold mb-1">Remark (optional, applies to all)</label>
            <input type="text" name="remark" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Upload</button>
    </form>
    <script>
        function updateFileList() {
            const input = document.getElementById('endorsement-files');
            const files = input.files;
            const fileNames = Array.from(files).map(f => f.name).join(', ');
            document.getElementById('selected-files').textContent = fileNames;
            document.getElementById('clear-files-btn').classList.toggle('hidden', files.length === 0);
        }
        function clearFiles() {
            const input = document.getElementById('endorsement-files');
            input.value = '';
            document.getElementById('selected-files').textContent = '';
            document.getElementById('clear-files-btn').classList.add('hidden');
        }
    </script>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2">Document</th>
                    <th class="px-4 py-2">Remark</th>
                    <th class="px-4 py-2">Uploaded At</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($endorsements as $endorsement)
                <tr class="border-b">
                    <td class="px-4 py-2">
                        <a href="{{ asset('storage/' . $endorsement->document) }}" target="_blank" class="text-indigo-600 underline">View</a>
                    </td>
                    <td class="px-4 py-2">
                        <span id="remark-text-{{ $endorsement->id }}">{{ $endorsement->remark }}</span>
                        <form action="{{ route('admin.portfolio.update_endorsement', $endorsement->id) }}" method="POST" class="hidden inline-block" id="remark-form-{{ $endorsement->id }}">
                            @csrf
                            @method('PUT')
                            <input type="text" name="remark" value="{{ $endorsement->remark }}" class="border rounded px-2 py-1 text-xs">
                            <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">Save</button>
                            <button type="button" onclick="cancelEdit({{ $endorsement->id }})" class="bg-gray-300 px-2 py-1 rounded text-xs ml-1">Cancel</button>
                        </form>
                    </td>
                    <td class="px-4 py-2">{{ $endorsement->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <button onclick="editRemark({{ $endorsement->id }})" class="bg-yellow-400 text-white px-2 py-1 rounded text-xs hover:bg-yellow-500">Edit</button>
                        <form action="{{ route('admin.portfolio.delete_endorsement', $endorsement->id) }}" method="POST" onsubmit="return confirm('Delete this endorsement copy?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
    <script>
        function editRemark(id) {
            document.getElementById('remark-text-' + id).style.display = 'none';
            document.getElementById('remark-form-' + id).style.display = 'inline-block';
        }
        function cancelEdit(id) {
            document.getElementById('remark-form-' + id).style.display = 'none';
            document.getElementById('remark-text-' + id).style.display = '';
        }
    </script>
            </tbody>
        </table>
    </div>
</div>
@endsection
