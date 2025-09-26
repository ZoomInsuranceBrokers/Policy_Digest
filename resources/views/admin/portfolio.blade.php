@extends('layouts.admin')

@section('content')
<div class="flex flex-col items-center justify-center h-full">
    <div class="bg-white rounded-lg shadow p-10 w-full max-w-2xl text-center">
        <h2 class="text-3xl font-bold mb-4 text-indigo-700">Portfolio</h2>
        <p class="text-gray-600 text-lg">Manage your portfolio here.</p>
    </div>
    <div class="flex flex-col gap-4 mt-8 w-full max-w-2xl">
        <h2 class="text-2xl font-bold mb-4">Portfolio - Companies</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 text-left">Company Name</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                    <tr class="border-b">
                        <td class="px-4 py-2  gap-2">
                            {{ $company->comp_name }}
                        </td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ url('/admin/portfolio/create-policy/' . $company->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Create New Policy</a>
                            <a href="{{ url('/admin/portfolio/policies/' . $company->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">View Policies</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
