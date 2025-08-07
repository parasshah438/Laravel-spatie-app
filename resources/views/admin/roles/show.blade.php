@extends('admin.layouts.app')

@section('title', 'Role Details')
@section('page-title', 'Role Details: ' . ucfirst($role->name))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-tag me-2"></i>Role Information
                    @if($role->name === 'superadmin')
                        <span class="badge bg-danger ms-2">Super Admin</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Role Name:</strong>
                        <p class="text-muted">{{ ucfirst($role->name) }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Guard:</strong>
                        <p><span class="badge bg-info">{{ $role->guard_name }}</span></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Created:</strong>
                        <p class="text-muted">{{ $role->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Last Updated:</strong>
                        <p class="text-muted">{{ $role->updated_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Total Permissions:</strong>
                    <span class="badge bg-secondary">{{ $role->permissions->count() }} permissions assigned</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Role
                    </a>
                    
                    @if($role->name !== 'superadmin')
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this role?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Role
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Roles
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-key me-2"></i>Assigned Permissions</h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    @php
                    $groupedPermissions = $role->permissions->groupBy(function($permission) {
                        return explode('-', $permission->name)[0];
                    });
                    @endphp
                    
                    <div class="row">
                        @foreach($groupedPermissions as $group => $perms)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0 text-primary">{{ ucfirst($group) }} Permissions</h6>
                                </div>
                                <div class="card-body">
                                    @foreach($perms as $permission)
                                    <div class="mb-2">
                                        <span class="badge bg-success me-2">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-key fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Permissions Assigned</h5>
                        <p class="text-muted">This role currently has no permissions assigned to it.</p>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Assign Permissions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
