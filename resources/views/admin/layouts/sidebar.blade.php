{{-- Admin Sidebar Navigation --}}
<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center py-3 border-bottom">
            <h4 class="text-white mb-1">Admin Panel</h4>
            <small class="text-muted">Welcome, {{ auth('admin')->user()->name }}</small>
        </div>
        
        <ul class="nav flex-column py-2">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            {{-- System Management Section --}}
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-muted text-uppercase">
                    <i class="fas fa-cogs me-2"></i>
                    System Management
                </h6>
            </li>
            
            @can('admin-list', auth('admin')->user())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" 
                   href="{{ route('admin.admins.index') }}">
                    <i class="fas fa-users-cog me-2"></i>
                    Admin Management
                </a>
            </li>
            @endcan
            
            @can('role-list', auth('admin')->user())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" 
                   href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-tag me-2"></i>
                    Role Management
                </a>
            </li>
            @endcan
            
            @can('permission-list', auth('admin')->user())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" 
                   href="{{ route('admin.permissions.index') }}">
                    <i class="fas fa-shield-alt me-2"></i>
                    Permissions
                </a>
            </li>
            @endcan
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}" 
                   href="{{ route('admin.activities.index') }}">
                    <i class="fas fa-history me-2"></i>
                    System Activity Log
                </a>
            </li>
            
            {{-- Analytics Section --}}
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-muted text-uppercase">
                    <i class="fas fa-chart-bar me-2"></i>
                    Analytics & Reports
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.users') ? 'active' : '' }}" 
                   href="{{ route('admin.analytics.users') }}">
                    <i class="fas fa-user-chart me-2"></i>
                    User Registration Analytics
                </a>
            </li>
            
            @can('user-list', auth('admin')->user())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i>
                    User Management
                </a>
            </li>
            @endcan
            
            {{-- Content Management Section --}}
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-muted text-uppercase">
                    <i class="fas fa-edit me-2"></i>
                    Content Management
                </h6>
            </li>
            
            @can('manage blogs', auth('admin')->user())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" 
                   href="{{ route('admin.blogs.index') }}">
                    <i class="fas fa-blog me-2"></i>
                    Blogs
                </a>
            </li>
            @endcan
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" 
                   href="{{ route('admin.services.index') }}">
                    <i class="fas fa-concierge-bell me-2"></i>
                    Services
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" 
                   href="{{ route('admin.sliders.index') }}">
                    <i class="fas fa-images me-2"></i>
                    Sliders
                </a>
            </li>
            
            {{-- Website Management Section --}}
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-muted text-uppercase">
                    <i class="fas fa-globe me-2"></i>
                    Website Management
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" 
                   href="#" onclick="alert('Pages feature coming soon!')">
                    <i class="fas fa-file-alt me-2"></i>
                    Pages <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                   href="#" onclick="alert('Categories feature coming soon!')">
                    <i class="fas fa-tags me-2"></i>
                    Categories <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}" 
                   href="#" onclick="alert('Media Library feature coming soon!')">
                    <i class="fas fa-photo-video me-2"></i>
                    Media Library <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            
            {{-- Settings Section --}}
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-muted text-uppercase">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                   href="#" onclick="alert('Site Settings feature coming soon!')">
                    <i class="fas fa-sliders-h me-2"></i>
                    Site Settings <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            
            {{-- Reports Section --}}
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-muted text-uppercase">
                    <i class="fas fa-chart-bar me-2"></i>
                    Reports
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                   href="#" onclick="alert('Analytics feature coming soon!')">
                    <i class="fas fa-chart-line me-2"></i>
                    Analytics <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" 
                   href="#" onclick="alert('Activity Logs feature coming soon!')">
                    <i class="fas fa-history me-2"></i>
                    Activity Logs <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            
            {{-- Profile & Logout Section --}}
            <hr class="my-3 border-secondary">
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" 
                   href="{{ route('admin.profile') }}">
                    <i class="fas fa-user-circle me-2"></i>
                    Profile
                </a>
            </li>
            
            <li class="nav-item">
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100 border-0">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

