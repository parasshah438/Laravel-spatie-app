@extends('admin.layouts.app')

@section('title', 'Feature Permissions Management')
@section('page-title', 'Feature Permissions Management')

@section('top-buttons')
<a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Add New Feature
</a>
<a href="{{ route('admin.permissions.seed-basic') }}" class="btn btn-warning ms-2" 
   onclick="return confirm('This will create basic admin permissions and assign them to Super Admin role. Continue?')">
    <i class="fas fa-seedling"></i> Seed Basic Permissions
</a>
<a href="{{ route('admin.roles.index') }}" class="btn btn-success ms-2">
    <i class="fas fa-users"></i> Manage Roles
</a>
@endsection

@section('content')
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

<!-- Permissions grouped by features -->
@if($permissions->count() > 0)
    @foreach($permissions as $featureName => $featurePermissions)
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-cog"></i>
                    {{ ucfirst($featureName) }} Feature
                    <span class="badge bg-light text-dark ms-2">{{ $featurePermissions->count() }} permissions</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($featurePermissions as $permission)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="border p-3 rounded bg-light">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong class="text-primary">{{ $permission->name }}</strong>
                                    <span class="badge bg-secondary">ID: {{ $permission->id }}</span>
                                </div>
                                <small class="text-muted">Guard: {{ $permission->guard_name }}</small>
                                <br>
                                <small class="text-info">
                                    Used in {{ $permission->roles->count() }} role(s)
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
            <h4 class="mt-3">No Feature Permissions Found</h4>
            <p class="text-muted">Start by creating your first feature permissions like blogs, sliders, services, etc.</p>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Feature Permissions
            </a>
        </div>
    </div>
@endif

<!-- Quick Actions -->
<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-users"></i> Manage Roles & Assign Permissions
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100">
                    <i class="fas fa-user"></i> Assign Roles to Users
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-plus"></i> Add More Features
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
