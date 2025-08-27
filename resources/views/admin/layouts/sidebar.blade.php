{{-- Modern Admin Sidebar Navigation --}}
<nav class="admin-sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <h4><i class="bi bi-shield-lock me-2"></i>Admin Panel</h4>
        <small>Welcome, {{ auth('admin')->user()->name }}</small>
    </div>
    
    <!-- Navigation -->
    <div class="sidebar-nav">
        {{-- Dashboard --}}
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
           href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>
        
        {{-- System Management Section --}}
        <div class="nav-section-title">
            <i class="bi bi-gear me-1"></i>
            System Management
        </div>
        
        @can('admin-list', auth('admin')->user())
        <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" 
           href="{{ route('admin.admins.index') }}">
            <i class="bi bi-people-fill"></i>
            Admin Management
        </a>
        @endcan
        
        @can('role-list', auth('admin')->user())
        <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" 
           href="{{ route('admin.roles.index') }}">
            <i class="bi bi-person-badge"></i>
            Role Management
        </a>
        @endcan
        
        @can('permission-list', auth('admin')->user())
        <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" 
           href="{{ route('admin.permissions.index') }}">
            <i class="bi bi-shield-check"></i>
            Permissions
        </a>
        @endcan
        
        <a class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}" 
           href="{{ route('admin.activities.index') }}">
            <i class="bi bi-clock-history"></i>
            Activity Log
        </a>
        
        {{-- Analytics Section --}}
        <div class="nav-section-title">
            <i class="bi bi-graph-up me-1"></i>
            Analytics & Reports
        </div>
        
        <a class="nav-link {{ request()->routeIs('admin.analytics.users') ? 'active' : '' }}" 
           href="{{ route('admin.analytics.users') }}">
            <i class="bi bi-bar-chart"></i>
            User Analytics
        </a>
        
        @can('user-list', auth('admin')->user())
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
           href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i>
            User Management
        </a>
        @endcan
        
        {{-- Content Management Section --}}
        <div class="nav-section-title">
            <i class="bi bi-file-text me-1"></i>
            Content Management
        </div>
        
        @can('manage blogs', auth('admin')->user())
        <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" 
           href="{{ route('admin.blogs.index') }}">
            <i class="bi bi-journal-text"></i>
            Blogs
        </a>
        @endcan
        
        <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" 
           href="{{ route('admin.services.index') }}">
            <i class="bi bi-briefcase"></i>
            Services
        </a>
        
        <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" 
           href="{{ route('admin.sliders.index') }}">
            <i class="bi bi-images"></i>
            Sliders
        </a>
        
        {{-- Website Management Section --}}
        <div class="nav-section-title">
            <i class="bi bi-globe me-1"></i>
            Website Management
        </div>
        
        <a class="nav-link" href="#" onclick="showComingSoon('Pages')">
            <i class="bi bi-file-earmark"></i>
            Pages
            <small>Soon</small>
        </a>
        
        <a class="nav-link" href="#" onclick="showComingSoon('Categories')">
            <i class="bi bi-tags"></i>
            Categories
            <small>Soon</small>
        </a>
        
        <a class="nav-link" href="#" onclick="showComingSoon('Media Library')">
            <i class="bi bi-camera"></i>
            Media Library
            <small>Soon</small>
        </a>
        
        {{-- Settings Section --}}
        <div class="nav-section-title">
            <i class="bi bi-sliders me-1"></i>
            Settings
        </div>
        
        <a class="nav-link" href="#" onclick="showComingSoon('Site Settings')">
            <i class="bi bi-gear-wide-connected"></i>
            Site Settings
            <small>Soon</small>
        </a>
        
        {{-- Reports Section --}}
        <div class="nav-section-title">
            <i class="bi bi-clipboard-data me-1"></i>
            Reports
        </div>
        
        <a class="nav-link" href="#" onclick="showComingSoon('Analytics')">
            <i class="bi bi-graph-up-arrow"></i>
            Analytics
            <small>Soon</small>
        </a>
        
        <a class="nav-link" href="#" onclick="showComingSoon('Activity Logs')">
            <i class="bi bi-list-check"></i>
            Activity Logs
            <small>Soon</small>
        </a>
        
        {{-- Profile & Logout Section --}}
        <div class="nav-section-title">
            <i class="bi bi-person me-1"></i>
            Account
        </div>
        
        <a class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" 
           href="{{ route('admin.profile') }}">
            <i class="bi bi-person-circle"></i>
            Profile
        </a>
        
        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline w-100">
            @csrf
            <button type="submit" class="nav-link border-0 w-100">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>
    </div>
</nav>

<script>
    function showComingSoon(feature) {
        // Create a modern toast notification
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-info border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-info-circle me-2"></i>
                    ${feature} feature is coming soon!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
</script>