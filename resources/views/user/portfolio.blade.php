@extends('layouts.user')
@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">My Portfolio</h2>
    @if(count($policies) === 0)
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">No policies found for your company.</div>
    @else
    <div class="bg-white rounded shadow p-4 mb-6">
        <h3 class="text-lg font-semibold mb-2">Policies</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">Policy #</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Policy Copy</th>
                    <th class="px-4 py-2">Endorsements</th>
                </tr>
            </thead>
            <tbody>
                @foreach($policies as $policy)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $policy->policy_number ?? $policy->id }}</td>
                    <td class="px-4 py-2">{{ $policy->policy_name ?? $policy->id }}</td>
                    <td class="px-4 py-2">
                        @if($policy->policy_copy)
                            <a href="{{ asset('storage/' . $policy->policy_copy) }}" class="text-blue-600 underline" target="_blank">View</a>
                        @else
                            <span class="text-gray-400">No Copy</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @php
                            $policyEndorsements = $endorsements->where('policy_id', $policy->id);
                        @endphp
                        @if($policyEndorsements->count())
                            <ul class="list-disc ml-4">
                                @foreach($policyEndorsements as $endorsement)
                                    <li>
                                        @if($endorsement->endorsement_copy)
                                            <a href="{{ asset('storage/' . $endorsement->endorsement_copy) }}" class="text-blue-600 underline" target="_blank">Endorsement Copy</a>
                                        @else
                                            <span class="text-gray-400">No Copy</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-400">No Endorsements</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
