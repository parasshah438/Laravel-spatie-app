<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-font-sans-serif: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--bs-font-sans-serif);
            background-color: #f8fafc;
            font-size: 0.9rem;
        }

        [data-bs-theme="dark"] body {
            background-color: #0f172a;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .admin-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h4 {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-header small {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section-title {
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem 1.5rem 0.5rem;
            margin-top: 1rem;
        }

        .nav-section-title:first-child {
            margin-top: 0;
        }

        .sidebar-nav .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            font-weight: 500;
        }

        .sidebar-nav .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.8) 0%, rgba(99, 102, 241, 0.8) 100%);
            border-right: 3px solid #3b82f6;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .sidebar-nav .nav-link small {
            margin-left: auto;
            font-size: 0.7rem;
            opacity: 0.7;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Header */
        .top-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .top-header {
            background: #1e293b;
            border-bottom: 1px solid #334155;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        [data-bs-theme="dark"] .page-title {
            color: #f1f5f9;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 8px;
            padding: 0.5rem;
            color: #3b82f6;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.05);
        }

        [data-bs-theme="dark"] .theme-toggle {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.3);
            color: #60a5fa;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Modern Cards */
        .modern-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        [data-bs-theme="dark"] .modern-card {
            background: #1e293b;
            border-color: #334155;
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--card-color, #3b82f6);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .stat-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-color: #334155;
        }

        .stat-card.primary { --card-color: #3b82f6; }
        .stat-card.success { --card-color: #10b981; }
        .stat-card.info { --card-color: #06b6d4; }
        .stat-card.warning { --card-color: #f59e0b; }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .stat-icon.info { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        [data-bs-theme="dark"] .stat-value {
            color: #f1f5f9;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        [data-bs-theme="dark"] .stat-label {
            color: #94a3b8;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        [data-bs-theme="dark"] .alert-success {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        [data-bs-theme="dark"] .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        /* Quick Action Buttons */
        .quick-action-btn {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: #1e293b;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            height: 100%;
        }

        .quick-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            color: #1e293b;
        }

        [data-bs-theme="dark"] .quick-action-btn {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-color: #334155;
            color: #f1f5f9;
        }

        [data-bs-theme="dark"] .quick-action-btn:hover {
            color: #f1f5f9;
        }

        .quick-action-btn i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #3b82f6;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 1rem;
            }
            
            .top-header {
                padding: 1rem;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #1e293b;
            font-size: 1.25rem;
            padding: 0.5rem;
        }

        [data-bs-theme="dark"] .mobile-menu-toggle {
            color: #f1f5f9;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Activity Feed */
        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: #f8fafc;
        }

        [data-bs-theme="dark"] .activity-item {
            border-bottom-color: #334155;
        }

        [data-bs-theme="dark"] .activity-item:hover {
            background: #0f172a;
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Scrollbar Styling */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        {{-- Include Sidebar --}}
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <div class="top-header">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-toggle me-3" id="mobileMenuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="header-actions">
                    @yield('top-buttons')
                    
                    <!-- Theme Toggle -->
                    <button class="theme-toggle btn" id="themeToggle" type="button">
                        <i class="bi bi-sun-fill" id="themeIcon"></i>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth('admin')->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;

            // Get saved theme or default to light
            const savedTheme = localStorage.getItem('admin-theme') || 'light';
            html.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcon(savedTheme);

            themeToggle.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('admin-theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.className = 'bi bi-moon-fill';
                } else {
                    themeIcon.className = 'bi bi-sun-fill';
                }
            }

            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            
            if (mobileMenuToggle && sidebar) {
                mobileMenuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', (e) => {
                    if (window.innerWidth <= 768 && 
                        !sidebar.contains(e.target) && 
                        !mobileMenuToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>