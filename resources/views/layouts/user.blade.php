<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col justify-between">
            <div>
                <div class="p-6 flex items-center space-x-2">
                    <div class="flex flex-col items-center w-full">
                        <div class="h-20 w-20 flex items-center justify-center">
                            <img src="{{ asset('assets/logo/zoom-logo.svg') }}" alt="Logo" class="h-20 w-20 object-contain" />
                        </div>
                    </div>
                </div>
                <nav class="mt-8">
                    <a href="{{ route('user.dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('user.dashboard') ? ' active' : '' }}">
                        <span class="material-icons mr-3">dashboard</span> Dashboard
                    </a>
                    <a href="{{ route('user.portfolio') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('user.portfolio') ? ' active' : '' }}">
                        <span class="material-icons mr-3">account_balance_wallet</span> Portfolio
                    </a>
                    <a href="{{ route('user.claims') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('user.claims') ? ' active' : '' }}">
                        <span class="material-icons mr-3">assignment</span> Claims
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-8">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 sidebar-link">
                            <span class="material-icons mr-3">logout</span> Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Sidebar Bottom Branding -->
            <div class="mb-6 flex flex-col items-center">
                <span class="font-bold text-base text-indigo-700 tracking-wide">Policy Digest</span>
            </div>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="flex items-center justify-between bg-white shadow px-8 py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Overview</h1>
                    <span class="text-sm text-gray-400">{{ date('l, F d, Y') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-xl font-bold text-indigo-700">
                            {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->name ?? Auth::user()->email, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">{{ Auth::user()->full_name ?? Auth::user()->name ?? '' }}</div>
                            <div class="text-xs text-gray-400">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Content -->
            <main class="flex-1 p-8 bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</body>
</html>
