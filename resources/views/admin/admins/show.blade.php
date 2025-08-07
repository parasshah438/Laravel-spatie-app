@extends('admin.layouts.app')

@section('title', 'Admin Details')
@section('page-title', 'Admin Details: ' . $admin->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Admin Information
                    @if($admin->status)
                        <span class="badge bg-success ms-2">Active</span>
                    @else
                        <span class="badge bg-danger ms-2">Inactive</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Name:</strong>
                        <p class="text-muted">{{ $admin->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <p class="text-muted">{{ $admin->email }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Created:</strong>
                        <p class="text-muted">{{ $admin->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Last Updated:</strong>
                        <p class="text-muted">{{ $admin->updated_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Assigned Roles:</strong>
                    <div class="mt-2">
                        @forelse($admin->roles as $role)
                            <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                        @empty
                            <span class="text-muted">No roles assigned</span>
                        @endforelse
                    </div>
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
                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Admin
                    </a>
                    
                    @if($admin->id !== auth('admin')->id())
                    <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this admin?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Admin
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Admins
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
                <h5 class="mb-0"><i class="fas fa-key me-2"></i>Role Permissions</h5>
            </div>
            <div class="card-body">
                @if($admin->roles->count() > 0)
                    @foreach($admin->roles as $role)
                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="fas fa-user-tag me-2"></i>{{ ucfirst($role->name) }} Role Permissions
                        </h6>
                        
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
                                            <h6 class="mb-0 text-dark">{{ ucfirst($group) }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($perms as $permission)
                                            <div class="mb-1">
                                                <span class="badge bg-success me-2">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <small>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">This role has no permissions assigned.</p>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Roles Assigned</h5>
                        <p class="text-muted">This admin currently has no roles assigned.</p>
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Assign Roles
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
