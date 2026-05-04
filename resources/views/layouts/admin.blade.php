<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Panel - Smart CMS')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <style>
        body {
            background: #0f0f0f;
        }

        .admin-sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1a1a1a;
            border-right: 1px solid rgba(255, 255, 255, 0.09);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding-top: 0;
        }

        .admin-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }

        .admin-brand .logo {
            width: 38px;
            height: 38px;
            background: #e63946;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .admin-nav {
            padding: 15px 0;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .admin-nav-item:hover,
        .admin-nav-item.active {
            color: #fff;
            background: rgba(230, 57, 70, 0.1);
            border-left-color: #e63946;
        }

        .admin-nav-item i {
            width: 18px;
            text-align: center;
        }

        .admin-nav-label {
            padding: 8px 20px 4px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.3);
            font-weight: 600;
        }

        .admin-main {
            margin-left: 250px;
            min-height: 100vh;
            background: #0f0f0f;
        }

        .admin-topbar {
            background: #1a1a1a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .admin-content {
            padding: 25px;
        }

        .stat-card {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: rgba(230, 57, 70, 0.4);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(230, 57, 70, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #e63946;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .admin-table {
            background: #1a1a1a;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.09);
        }

        .admin-table table {
            margin: 0;
        }

        .admin-table thead th {
            background: #242424;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 16px;
            border: none;
            font-weight: 600;
        }

        .admin-table tbody td {
            padding: 14px 16px;
            color: rgba(255, 255, 255, 0.85);
            border-color: rgba(255, 255, 255, 0.06);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .admin-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .admin-card {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            padding: 25px;
        }

        .form-control,
        .form-select,
        .form-check-input {
            background: #242424 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #e63946 !important;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.2) !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn-danger {
            background: #e63946;
            border-color: #e63946;
        }

        .btn-danger:hover {
            background: #c1121f;
            border-color: #c1121f;
        }

        .page-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
        }

        .badge-published {
            background: rgba(39, 174, 96, 0.2);
            color: #27ae60;
        }

        .badge-draft {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }

        select option {
            background: #242424;
            color: #fff;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <div class="logo"><i class="fas fa-car text-white"></i></div>
            Smart CMS
        </a>

        <nav class="admin-nav">
            <div class="admin-nav-label">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="admin-nav-label mt-2">Manage</div>
            <a href="{{ route('admin.items.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                <i class="fas fa-car"></i> Items
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="{{ route('admin.cities.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i> Cities
            </a>
            <a href="{{ route('admin.inquiries.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Inquiries
            </a>
            <a href="{{ route('admin.reports.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-flag"></i> Reports
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="{{ route('admin.property-templates.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.property-templates.*') ? 'active' : '' }}">
                <i class="fas fa-list-alt"></i> Properties
            </a>

            <div class="admin-nav-label mt-2">Account</div>
            <a href="{{ route('home') }}" class="admin-nav-item" target="_blank">
                <i class="fas fa-globe"></i> View Site
            </a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="admin-nav-item w-100 border-0 text-start"
                    style="background:none; cursor:pointer;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">

        {{-- TOPBAR --}}
        <div class="admin-topbar">
            <h1 class="page-title mb-0">@yield('page-title', 'Dashboard')</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-brand small">
                    <i class="fas fa-user-circle me-1 text-red"></i>
                    {{ auth()->user()->name }}
                </span>
            </div>
        </div>

        {{-- ALERTS --}}
        <div class="admin-content pb-0">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        {{-- CONTENT --}}
        <div class="admin-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    {{-- AUTO DISMISS ALERTS AFTER 3 SECONDS --}}
    <script>
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            });
        }, 3000);
    </script>

    @stack('scripts')
</body>

</html>