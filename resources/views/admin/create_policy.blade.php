@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Create New Policy for {{ $company->comp_name }}</h2>
    <div class="mb-6 flex gap-4">
        <button id="singleEntryBtn" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Single Entry</button>
        <button id="bulkUploadBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Bulk Upload</button>
    </div>
    <div id="singleEntryForm">
        <form action="{{ route('admin.portfolio.store_policy', $company->id) }}" method="POST" enctype="multipart/form-data" class="w-[600px] mx-auto bg-gradient-to-br from-white via-gray-100 to-white rounded-xl shadow-2xl p-8 border border-gray-200 relative" style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37); backdrop-filter: blur(4px);">
            <div class="mb-6">
                <label class="block font-semibold mb-1">Insured Name <span class="text-red-500">*</span></label>
                <input type="text" name="insured_name" value="{{ old('insured_name') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                @error('insured_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            @csrf
            <div class="absolute inset-0 rounded-xl pointer-events-none" style="box-shadow: 0 4px 60px 0 rgba(80, 80, 255, 0.12) inset, 0 1.5px 0 0 #fff; opacity: 0.7;"></div>
            <div class="mb-6 flex gap-6">
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('product_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Policy Number <span class="text-red-500">*</span></label>
                    <input type="text" name="policy_number" value="{{ old('policy_number') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('policy_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-6 flex gap-6">
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Expiry Date <span class="text-red-500">*</span></label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('expiry_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-6 flex gap-6">
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Sum Insured <span class="text-red-500">*</span></label>
                    <input type="number" name="sum_insured" value="{{ old('sum_insured') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('sum_insured') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">PBST</label>
                    <input type="text" name="pbst" value="{{ old('pbst') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner">
                    @error('pbst') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-6 flex gap-6">
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Gross Premium <span class="text-red-500">*</span></label>
                    <input type="number" name="gross_premium" value="{{ old('gross_premium') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('gross_premium') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Insurance Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="insurance_company_name" value="{{ old('insurance_company_name') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner" required>
                    @error('insurance_company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-6 flex gap-6">
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Cash Deposit</label>
                    <input type="text" name="cash_deposit" value="{{ old('cash_deposit') }}" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner">
                    @error('cash_deposit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/2">
                    <label class="block font-semibold mb-1">Policy Copy (Upload Document) <span class="text-red-500">*</span></label>
                    <input type="file" name="policy_copy" class="w-full border border-indigo-200 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-400 shadow-inner bg-white" required>
                    @error('policy_copy') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex gap-4 justify-end mt-8">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg shadow hover:bg-indigo-700 transition-all">Save Policy</button>
                <a href="{{ url('/admin/portfolio/policies/' . $company->id) }}" class="bg-gray-300 px-6 py-2 rounded-lg shadow hover:bg-gray-400 transition-all">Cancel</a>
            </div>
        </form>
    </div>
    <div id="bulkUploadForm" style="display:none;">
        <form id="bulkUploadCsvForm" action="{{ route('admin.portfolio.bulk_upload', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Bulk Upload CSV</label>
                <input type="file" name="bulk_csv" id="bulk_csv_input" class="w-full border rounded px-3 py-2" accept=".csv" required>
            </div>
            <div class="mb-4">
                <a href="{{ asset('sample_policy_upload.csv') }}" class="text-blue-600 underline">Download Sample CSV</a>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
                <a href="{{ url('/admin/portfolio/policies/' . $company->id) }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</div>
<!-- Modal for upload progress -->
<div id="uploadProgressModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white p-6 rounded shadow w-[600px] text-center">
        <h3 class="text-lg font-bold mb-4">Bulk Upload Progress</h3>
        <div id="progressBar" class="w-full bg-gray-200 rounded h-4 mb-2">
            <div id="progressBarFill" class="bg-blue-600 h-4 rounded" style="width:0%"></div>
        </div>
        <div id="progressText" class="text-sm mb-2">0%</div>
        <div class="overflow-x-auto max-h-60 mb-2">
            <table class="min-w-full text-xs" id="progressTable">
                <thead><tr><th class="px-2 py-1">Row</th><th class="px-2 py-1">Status</th><th class="px-2 py-1">Error</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <a id="downloadLogBtn" href="#" class="hidden bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mt-2" download>Download Log</a>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
<script>
    document.getElementById('singleEntryBtn').onclick = function() {
        document.getElementById('singleEntryForm').style.display = '';
        document.getElementById('bulkUploadForm').style.display = 'none';
    };
    document.getElementById('bulkUploadBtn').onclick = function() {
        document.getElementById('singleEntryForm').style.display = 'none';
        document.getElementById('bulkUploadForm').style.display = '';
    };
    // Bulk upload row-by-row AJAX logic
    document.getElementById('bulkUploadCsvForm').onsubmit = function(e) {
        e.preventDefault();
        var fileInput = document.getElementById('bulk_csv_input');
        var file = fileInput.files[0];
        if (!file) return;
        document.getElementById('uploadProgressModal').classList.remove('hidden');
        document.getElementById('progressBarFill').style.width = '0%';
        document.getElementById('progressText').innerText = '0%';
        var progressTableBody = document.querySelector('#progressTable tbody');
        progressTableBody.innerHTML = '';
        var results = [];
        Papa.parse(file, {
            header: true,
            skipEmptyLines: true,
            complete: function(resultsObj) {
                var rows = resultsObj.data;
                var total = rows.length;
                var processed = 0;
                var logRows = [];
                function processRow(i) {
                    if (i >= total) {
                        document.getElementById('progressBarFill').style.width = '100%';
                        document.getElementById('progressText').innerText = '100%';
                        // Generate CSV log
                        var csvContent = 'Row,Status,Error\n' + logRows.map(r => r.join(',')).join('\n');
                        var blob = new Blob([csvContent], {type: 'text/csv'});
                        var url = URL.createObjectURL(blob);
                        var downloadBtn = document.getElementById('downloadLogBtn');
                        downloadBtn.href = url;
                        downloadBtn.classList.remove('hidden');
                        downloadBtn.innerText = 'Download Log';
                        return;
                    }
                    var row = rows[i];
                    var rowNum = i + 2; // +2 for header and 1-based
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{ route('admin.portfolio.ajax_bulk_row', $company->id) }}", true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.onload = function() {
                        processed++;
                        var percent = Math.round((processed / total) * 100);
                        document.getElementById('progressBarFill').style.width = percent + '%';
                        document.getElementById('progressText').innerText = percent + '%';
                        var res = {row: rowNum, status: 'Inserted', error: ''};
                        if (xhr.status === 200) {
                            var resp = JSON.parse(xhr.responseText);
                            if (!resp.success) {
                                res.status = 'Failed';
                                res.error = resp.error || 'Unknown error';
                            }
                        } else {
                            res.status = 'Failed';
                            res.error = 'Server error';
                        }
                        var tr = document.createElement('tr');
                        tr.innerHTML = `<td class='px-2 py-1'>${res.row}</td><td class='px-2 py-1'>${res.status}</td><td class='px-2 py-1 text-red-600'>${res.error}</td>`;
                        progressTableBody.appendChild(tr);
                        logRows.push([res.row, res.status, '"' + res.error.replace(/"/g, '""') + '"']);
                        processRow(i + 1);
                    };
                    xhr.onerror = function() {
                        processed++;
                        var percent = Math.round((processed / total) * 100);
                        document.getElementById('progressBarFill').style.width = percent + '%';
                        document.getElementById('progressText').innerText = percent + '%';
                        var tr = document.createElement('tr');
                        tr.innerHTML = `<td class='px-2 py-1'>${rowNum}</td><td class='px-2 py-1'>Failed</td><td class='px-2 py-1 text-red-600'>AJAX error</td>`;
                        progressTableBody.appendChild(tr);
                        logRows.push([rowNum, 'Failed', '"AJAX error"']);
                        processRow(i + 1);
                    };
                    xhr.send(JSON.stringify(row));
                }
                processRow(0);
            }
        });
    };
</script>
@endsection
