@extends('customer.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back! Here\'s your account overview')

@section('content')
<!-- Notification Container for Real-time updates -->
<div id="notification-container" class="mb-3"></div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary" style="border-left: 4px solid #667eea;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Profile Completion
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['profile_completion'] }}%
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-user-check fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-success" style="border-left: 4px solid #1cc88a;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Activities
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-counter="activities">
                            {{ $stats['total_activities'] }}
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-chart-line fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-info" style="border-left: 4px solid #36b9cc;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Customers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="total_customers">
                            {{ $systemStats['total_customers'] ?? 0 }}
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-warning" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Active Today
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="active_customers_today">
                            {{ $systemStats['active_customers_today'] ?? 0 }}
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-user-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock text-primary me-2"></i>
                    Recent Activities
                </h5>
                <div class="text-success small">
                    <i class="fas fa-circle fa-sm"></i> Live Updates
                </div>
            </div>
            <div class="card-body p-0">
                <div id="activity-feed" class="p-3" style="max-height: 500px; overflow-y: auto;">
                    @if($customerActivities->count() > 0)
                        @foreach($customerActivities as $activity)
                            <div class="activity-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $activity->description }}</h6>
                                        <p class="text-muted small mb-1">
                                            {{ $activity->subject_type ? class_basename($activity->subject_type) : 'System' }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Member Since
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['member_since'] }}
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-calendar fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-warning" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Last Login
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800" style="font-size: 0.9rem;">
                            {{ $stats['last_login'] }}
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history mr-2"></i>Recent Activities
                </h6>
            </div>
            <div class="card-body">
                @if($customerActivities->count() > 0)
                    <div class="activity-list" style="max-height: 400px; overflow-y: auto;">
                        @foreach($customerActivities as $activity)
                            <div class="activity-item d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="activity-icon mr-3">
                                    @if(str_contains($activity->description, 'login'))
                                        <i class="fas fa-sign-in-alt text-success fa-lg"></i>
                                    @elseif(str_contains($activity->description, 'logout'))
                                        <i class="fas fa-sign-out-alt text-warning fa-lg"></i>
                                    @elseif(str_contains($activity->description, 'Created'))
                                        <i class="fas fa-user-plus text-primary fa-lg"></i>
                                    @elseif(str_contains($activity->description, 'Profile'))
                                        <i class="fas fa-user-edit text-info fa-lg"></i>
                                    @elseif(str_contains($activity->description, 'Password'))
                                        <i class="fas fa-key text-danger fa-lg"></i>
                                    @else
                                        <i class="fas fa-circle text-secondary fa-lg"></i>
                                    @endif
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <div class="activity-description font-weight-bold">
                                        {{ $activity->description }}
                                    </div>
                                    <div class="activity-time text-muted small">
                                        {{ $activity->created_at->format('M d, Y • h:i A') }}
                                        <span class="ml-2">({{ $activity->created_at->diffForHumans() }})</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clock fa-3x mb-3 text-muted"></i>
                        <p>No recent activities found.</p>
                        <small>Activities will appear here when you use the portal.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions & Profile Summary -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt mr-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('customer.profile') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-edit mr-2"></i>Update Profile
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-shopping-cart mr-2"></i>View Orders
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-file-invoice mr-2"></i>Download Invoices
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-headset mr-2"></i>Contact Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user mr-2"></i>Profile Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-user fa-lg text-white"></i>
                    </div>
                </div>
                
                <div class="user-info">
                    <div class="mb-2">
                        <strong>Name:</strong><br>
                        <span class="text-muted">{{ $customer->name }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong><br>
                        <span class="text-muted">{{ $customer->email }}</span>
                    </div>
                    @if($customer->company)
                    <div class="mb-2">
                        <strong>Company:</strong><br>
                        <span class="text-muted">{{ $customer->company }}</span>
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Status:</strong><br>
                        <span class="badge badge-{{ $customer->status === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Member Since:</strong><br>
                        <span class="text-muted">{{ $customer->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Message for New Customers -->
@if($stats['profile_completion'] < 50)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle mr-2"></i>Welcome to the Customer Portal!</h5>
            <p class="mb-2">Complete your profile to get the most out of our services:</p>
            <ul class="mb-2">
                <li>Add your company information</li>
                <li>Update your contact details</li>
                <li>Set up your preferences</li>
            </ul>
            <a href="{{ route('customer.profile') }}" class="btn btn-info btn-sm">
                <i class="fas fa-user-edit mr-1"></i>Complete Profile
            </a>
        </div>
    </div>
</div>
@endif
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
        guard: 'customer',
        updateStatsUrl: '{{ route('customer.dashboard.stats') }}',
    };
</script>
@endpush
