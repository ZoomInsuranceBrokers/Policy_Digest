@extends('layouts.user')
@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">User Dashboard</h2>
    <div class="bg-blue-100 text-blue-800 p-4 rounded">Welcome, {{ Auth::user()->full_name ?? Auth::user()->name }}</div>
</div>
@endsection
