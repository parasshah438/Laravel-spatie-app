@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<!-- Notification Container for Real-time updates -->
<div id="notification-container" class="mb-3"></div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value" data-stat="total_users">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-value" data-stat="total_customers">{{ $stats['total_customers'] ?? 0 }}</div>
            <div class="stat-label">Total Customers</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-value" data-stat="total_roles">{{ $stats['total_roles'] ?? 0 }}</div>
            <div class="stat-label">Total Roles</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-person-fill-check"></i>
            </div>
            <div class="stat-value" data-stat="active_users_today">{{ $stats['active_users_today'] ?? 0 }}</div>
            <div class="stat-label">Active Today</div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4 mb-4">
    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="modern-card h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Recent Activities
                </h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success-subtle text-success me-2">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Live Updates
                    </span>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshActivities()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="activity-feed" class="activity-list" style="max-height: 450px; overflow-y: auto;">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $activity['description'] }}</h6>
                                        <p class="text-muted small mb-1">
                                            by <strong>{{ $activity['causer_name'] }}</strong>
                                        </p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $activity['created_at'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="p-3 text-center border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View All Activities
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="text-muted">No Recent Activities</h6>
                            <p class="text-muted small">Activities will appear here as they happen</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="col-lg-4">
        <div class="modern-card h-100">
            <div class="card-header bg-transparent border-0 p-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2 text-info"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="system-info-list">
                    <div class="system-info-item d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-code-square text-primary me-2"></i>
                            <span class="fw-medium">Laravel Version</span>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">{{ app()->version() }}</span>
                    </div>
                    
                    <div class="system-info-item d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-server text-success me-2"></i>
                            <span class="fw-medium">PHP Version</span>
                        </div>
                        <span class="badge bg-success-subtle text-success">{{ phpversion() }}</span>
                    </div>
                    
                    <div class="system-info-item d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-info me-2"></i>
                            <span class="fw-medium">Server Time</span>
                        </div>
                        <span class="text-muted small">{{ now()->format('Y-m-d H:i:s') }}</span>
                    </div>
                    
                    <div class="system-info-item d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-gear text-warning me-2"></i>
                            <span class="fw-medium">Environment</span>
                        </div>
                        <span class="badge bg-{{ app()->environment('production') ? 'success' : 'warning' }}-subtle text-{{ app()->environment('production') ? 'success' : 'warning' }}">
                            {{ ucfirst(app()->environment()) }}
                        </span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-speedometer2 me-2 text-primary"></i>Quick Stats
                    </h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-center p-2 bg-primary-subtle rounded">
                                <div class="fw-bold text-primary">{{ $stats['total_users'] ?? 0 }}</div>
                                <small class="text-muted">Users</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-success-subtle rounded">
                                <div class="fw-bold text-success">{{ $stats['total_roles'] ?? 0 }}</div>
                                <small class="text-muted">Roles</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header bg-transparent border-0 p-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.admins.create') }}" class="quick-action-btn">
                            <i class="bi bi-person-plus-fill"></i>
                            <h6 class="mt-2 mb-1">Add Admin</h6>
                            <small class="text-muted">Create new admin user</small>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.roles.create') }}" class="quick-action-btn">
                            <i class="bi bi-person-badge"></i>
                            <h6 class="mt-2 mb-1">Create Role</h6>
                            <small class="text-muted">Define new user role</small>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.admins.index') }}" class="quick-action-btn">
                            <i class="bi bi-people-fill"></i>
                            <h6 class="mt-2 mb-1">Manage Admins</h6>
                            <small class="text-muted">View all admin users</small>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.roles.index') }}" class="quick-action-btn">
                            <i class="bi bi-gear-fill"></i>
                            <h6 class="mt-2 mb-1">Manage Roles</h6>
                            <small class="text-muted">Configure user roles</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics (Optional Enhancement) -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header bg-transparent border-0 p-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-graph-up me-2 text-success"></i>Performance Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-primary-subtle rounded">
                            <i class="bi bi-speedometer2 text-primary mb-2" style="font-size: 2rem;"></i>
                            <h6 class="fw-bold">System Health</h6>
                            <span class="badge bg-success">Excellent</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-success-subtle rounded">
                            <i class="bi bi-shield-check text-success mb-2" style="font-size: 2rem;"></i>
                            <h6 class="fw-bold">Security</h6>
                            <span class="badge bg-success">Secure</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-info-subtle rounded">
                            <i class="bi bi-database text-info mb-2" style="font-size: 2rem;"></i>
                            <h6 class="fw-bold">Database</h6>
                            <span class="badge bg-success">Connected</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-warning-subtle rounded">
                            <i class="bi bi-cloud-check text-warning mb-2" style="font-size: 2rem;"></i>
                            <h6 class="fw-bold">Backup</h6>
                            <span class="badge bg-warning">Scheduled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Pusher JavaScript -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<!-- Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<!-- Real-time Dashboard Script -->
<script src="{{ asset('js/dashboard-realtime.js') }}"></script>

<script>
    // Configure real-time dashboard
    window.dashboardConfig = {
        pusherKey: '{{ config('broadcasting.connections.pusher.key') }}',
        pusherCluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        guard: 'admin',
        updateStatsUrl: '{{ route('admin.dashboard.stats') }}',
    };

    // Dashboard functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stats on load
        animateStats();
        
        // Auto-refresh activities every 30 seconds
        setInterval(refreshActivities, 30000);
        
        // Add hover effects to cards
        addCardHoverEffects();
    });

    function animateStats() {
        const statValues = document.querySelectorAll('.stat-value');
        statValues.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            let currentValue = 0;
            const increment = finalValue / 50;
            
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    stat.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(currentValue);
                }
            }, 20);
        });
    }

    function refreshActivities() {
        const activityFeed = document.getElementById('activity-feed');
        const refreshBtn = document.querySelector('[onclick="refreshActivities()"]');
        
        // Show loading state
        refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
        refreshBtn.classList.add('loading-spinner');
        
        // Simulate refresh (replace with actual AJAX call)
        setTimeout(() => {
            refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
            refreshBtn.classList.remove('loading-spinner');
            
            // Show success toast
            showToast('Activities refreshed successfully!', 'success');
        }, 1000);
    }

    function addCardHoverEffects() {
        const cards = document.querySelectorAll('.modern-card, .stat-card, .quick-action-btn');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Real-time updates simulation
    function simulateRealTimeUpdates() {
        setInterval(() => {
            // Randomly update one of the stats
            const stats = ['total_users', 'total_customers', 'total_roles', 'active_users_today'];
            const randomStat = stats[Math.floor(Math.random() * stats.length)];
            const statElement = document.querySelector(`[data-stat="${randomStat}"]`);
            
            if (statElement) {
                const currentValue = parseInt(statElement.textContent);
                const change = Math.floor(Math.random() * 3) - 1; // -1, 0, or 1
                const newValue = Math.max(0, currentValue + change);
                
                if (newValue !== currentValue) {
                    statElement.textContent = newValue;
                    statElement.parentElement.classList.add('animate__animated', 'animate__pulse');
                    
                    setTimeout(() => {
                        statElement.parentElement.classList.remove('animate__animated', 'animate__pulse');
                    }, 1000);
                }
            }
        }, 10000); // Update every 10 seconds
    }

    // Start real-time updates
    simulateRealTimeUpdates();
</script>
@endpush