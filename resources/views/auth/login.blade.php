@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 to-white">
    <div class="bg-white bg-opacity-80 rounded-xl shadow-lg p-8 w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <div class="bg-gray-200 rounded-full p-3 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <h2 class="text-2xl font-bold mb-1">Sign in with email</h2>
            <p class="text-gray-600 text-sm text-center">Make a new doc to bring your words, data, and teams together. For free</p>
        </div>

        @if ($errors->any())
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative text-sm">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="mb-4">
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Email" required autofocus>
            </div>
            <div class="mb-2">
                <input type="password" name="password" value="{{ old('password') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Password" required>
            </div>
            <div class="flex justify-end mb-4">
                <a href="#" class="text-xs text-gray-500 hover:underline">Forgot password?</a>
            </div>
            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg font-semibold hover:bg-gray-800 transition">Get Started</button>
        </form>
    </div>
</div>
@endsection
