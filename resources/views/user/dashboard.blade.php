@extends('user.layouts.app')

@section('title', 'User Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Welcome back, {{ $user->name }}!</h3>
                        <p class="mb-0 opacity-75">Have a great day ahead</p>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-circle fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Member Since:</strong></td>
                        <td>{{ $user->created_at->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Roles:</strong></td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                            @empty
                                <span class="text-muted">No roles assigned</span>
                            @endforelse
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-edit me-2"></i>Update Profile
                    </a>
                    <button class="btn btn-outline-info" disabled>
                        <i class="fas fa-bell me-2"></i>Notifications (Coming Soon)
                    </button>
                    <button class="btn btn-outline-success" disabled>
                        <i class="fas fa-cog me-2"></i>Settings (Coming Soon)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Activity Overview</h5>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-md-4">
                        <div class="bg-light rounded p-3">
                            <i class="fas fa-eye fa-2x text-info mb-2"></i>
                            <h4>0</h4>
                            <small class="text-muted">Profile Views</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded p-3">
                            <i class="fas fa-edit fa-2x text-warning mb-2"></i>
                            <h4>0</h4>
                            <small class="text-muted">Profile Updates</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded p-3">
                            <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                            <h4>{{ $user->created_at->diffInDays() }}</h4>
                            <small class="text-muted">Days as Member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
