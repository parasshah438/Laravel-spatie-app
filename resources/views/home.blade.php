@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Notification Container for Real-time updates -->
    <div id="notification-container" class="mb-3"></div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Welcome Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-tachometer-alt mr-2"></i>Welcome back, {{ $user->name }}!</h4>
                    <div class="text-success small">
                        <i class="fas fa-circle fa-sm"></i> Live Updates
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="bg-primary text-white rounded p-3 text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h5 data-counter="activities">{{ $stats['total_activities'] }}</h5>
                                <small>Total Activities</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-success text-white rounded p-3 text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h5 data-stat="total_users">{{ $systemStats['total_users'] ?? 0 }}</h5>
                                <small>Total Users</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-info text-white rounded p-3 text-center">
                                <i class="fas fa-user-friends fa-2x mb-2"></i>
                                <h5 data-stat="total_customers">{{ $systemStats['total_customers'] ?? 0 }}</h5>
                                <small>Total Customers</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-warning text-white rounded p-3 text-center">
                                <i class="fas fa-user-clock fa-2x mb-2"></i>
                                <h5 data-stat="active_users_today">{{ $systemStats['active_users_today'] ?? 0 }}</h5>
                                <small>Active Today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Activities -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-clock mr-2"></i>Recent Activities</h5>
                            <div class="text-success small">
                                <i class="fas fa-circle fa-sm"></i> Live Updates
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="activity-feed" style="max-height: 400px; overflow-y: auto;">
                                @if($userActivities->count() > 0)
                                    @foreach($userActivities as $activity)
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
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">No recent activities found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Your Profile</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-4x text-secondary"></i>
                            </div>
                            <h5>{{ $user->name }}</h5>
                            <p class="text-muted">{{ $user->email }}</p>
                            <hr>
                            <div class="text-left">
                                <p><strong>Member Since:</strong> {{ $stats['member_since'] }}</p>
                                <p><strong>Last Activity:</strong> {{ $stats['last_activity'] }}</p>
                                <p><strong>Roles:</strong> 
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-primary">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">No roles assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Activities -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-clock mr-2"></i>Recent Activities</h5>
                        </div>
                        <div class="card-body">
                            @if($userActivities->count() > 0)
                                <div class="activity-list">
                                    @foreach($userActivities as $activity)
                                        <div class="activity-item d-flex align-items-center mb-3 p-3 border rounded">
                                            <div class="activity-icon mr-3">
                                                @if(str_contains($activity->description, 'login'))
                                                    <i class="fas fa-sign-in-alt text-success fa-lg"></i>
                                                @elseif(str_contains($activity->description, 'logout'))
                                                    <i class="fas fa-sign-out-alt text-warning fa-lg"></i>
                                                @elseif(str_contains($activity->description, 'Created'))
                                                    <i class="fas fa-plus-circle text-primary fa-lg"></i>
                                                @elseif(str_contains($activity->description, 'updated'))
                                                    <i class="fas fa-edit text-info fa-lg"></i>
                                                @elseif(str_contains($activity->description, 'Profile'))
                                                    <i class="fas fa-user-edit text-success fa-lg"></i>
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
                                    <small>Activities will appear here when you interact with the system.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Profile Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-user mr-2"></i>Profile Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-muted"></i>
                                </div>
                            </div>
                            
                            <div class="user-info">
                                <div class="mb-2">
                                    <strong>Name:</strong><br>
                                    <span class="text-muted">{{ $user->name }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong><br>
                                    <span class="text-muted">{{ $user->email }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Roles:</strong><br>
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-primary mr-1">{{ ucfirst($role->name) }}</span>
                                    @empty
                                        <span class="badge badge-secondary">User</span>
                                    @endforelse
                                </div>
                                <div class="mb-2">
                                    <strong>Verified:</strong><br>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Unverified
                                        </span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <strong>Joined:</strong><br>
                                    <span class="text-muted">{{ $user->created_at->format('F d, Y') }}</span>
                                </div>
                                
                                <div class="text-center">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit mr-1"></i>Edit Profile
                                    </a>
                                </div>
                            </div>
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
        guard: 'web',
        updateStatsUrl: '{{ route('home.stats') }}',
    };
</script>
@endpush
