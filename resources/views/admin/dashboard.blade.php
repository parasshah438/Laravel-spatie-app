@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin Panel')

@section('content')
<!-- Notification Container for Real-time updates -->
<div id="notification-container" class="mb-3"></div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4e73df;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="total_users">{{ $stats['total_users'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="total_customers">{{ $stats['total_customers'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users-cog fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Roles</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="total_roles">{{ $stats['total_roles'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tag fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Today</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="active_users_today">{{ $stats['active_users_today'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock mr-2"></i>Recent Activities
                </h6>
                <div class="text-success">
                    <i class="fas fa-circle fa-sm"></i> Live Updates
                </div>
            </div>
            <div class="card-body">
                <div id="activity-feed" class="activity-list" style="max-height: 400px; overflow-y: auto;">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                            <div class="activity-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $activity['description'] }}</h6>
                                        <p class="text-muted small mb-1">
                                            by {{ $activity['causer_name'] }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $activity['created_at'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary">View All Activities</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clock fa-3x mb-3 text-muted"></i>
                            <p>No recent activities found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Laravel Version:</strong></td>
                        <td>{{ app()->version() }}</td>
                    </tr>
                    <tr>
                        <td><strong>PHP Version:</strong></td>
                        <td>{{ phpversion() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Server Time:</strong></td>
                        <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Environment:</strong></td>
                        <td><span class="badge bg-{{ app()->environment('production') ? 'success' : 'warning' }}">{{ app()->environment() }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus-circle mb-2"></i><br>
                            Add Admin
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-user-tag mb-2"></i><br>
                            Create Role
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-list mb-2"></i><br>
                            Manage Admins
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-cogs mb-2"></i><br>
                            Manage Roles
                        </a>
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
</script>
@endpush
