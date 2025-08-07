<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            padding: 0;
        }
        .sidebar .nav-link {
            color: #adb5bd !important;
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            border-radius: 0;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff !important;
            background-color: #495057 !important;
        }
        .sidebar .nav-link:focus,
        .sidebar .nav-link:active {
            color: #fff !important;
            background-color: #495057 !important;
            outline: none;
            box-shadow: none;
        }
        .sidebar .sidebar-heading {
            color: #6c757d !important;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sidebar .text-muted {
            color: #6c757d !important;
        }
        .sidebar .text-white {
            color: #fff !important;
        }
        .sidebar small {
            font-size: 0.7rem;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .navbar-brand {
            font-weight: bold;
        }
        /* Fix button in sidebar */
        .sidebar .btn-link {
            color: #adb5bd !important;
            text-decoration: none;
            padding: 12px 20px;
        }
        .sidebar .btn-link:hover {
            color: #fff !important;
            background-color: #495057 !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Include Sidebar --}}
            @include('admin.layouts.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('top-buttons')
                    </div>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
