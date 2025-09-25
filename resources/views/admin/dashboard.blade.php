@extends('layouts.admin')

@section('content')
<div class="flex flex-col items-center justify-center h-full">
    <div class="bg-white rounded-lg shadow p-10 w-full max-w-2xl text-center">
        <h2 class="text-3xl font-bold mb-4 text-indigo-700">Welcome back, {{ Auth::user()->full_name ?? Auth::user()->name ?? '' }}!</h2>
        <p class="text-gray-600 text-lg">We're glad to see you again. Use the sidebar to manage your company, portfolio, and more.</p>
    </div>
</div>
@endsection
