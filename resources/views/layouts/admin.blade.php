<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: #f3f4f6; font-weight: 600; }

        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin: 1rem 0;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.25rem 0.5rem;
            margin: 0 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col justify-between">
            <div>
                <div class="flex items-center">
                    <div class="flex flex-col items-center w-full">
                        <div class="flex items-center justify-center mt-8">
                            <img src="{{ asset('assets/logo/zoom-logo.svg') }}" alt="Logo" class="w-20 object-contain" />
                        </div>
                    </div>
                </div>
                <nav class="mt-8">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}">
                        <span class="material-icons mr-3">dashboard</span> Dashboard
                    </a>
                    <a href="{{ route('admin.company_master') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('admin.company_master') ? ' active' : '' }}">
                        <span class="material-icons mr-3">business</span> Company Master
                    </a>
                    <a href="{{ route('admin.portfolio') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('admin.portfolio') ? ' active' : '' }}">
                        <span class="material-icons mr-3">account_balance_wallet</span> Portfolio
                    </a>
                    <a href="{{ route('admin.claims') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('admin.claims') ? ' active' : '' }}">
                        <span class="material-icons mr-3">assignment</span> Claims
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('admin.users') ? ' active' : '' }}">
                        <span class="material-icons mr-3">group</span> Users
                    </a>
                    <a href="{{ route('admin.cd_account') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100{{ request()->routeIs('admin.cd_account') ? ' active' : '' }}">
                        <span class="material-icons mr-3">account_balance</span> CD Account
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

    <!-- jQuery and DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    @stack('scripts')
</body>
</html>
