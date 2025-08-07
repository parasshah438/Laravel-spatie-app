@extends('admin.layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('top-buttons')
<a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Add New Role
</a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Guard</th>
                        <th>Permissions Count</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <strong>{{ ucfirst($role->name) }}</strong>
                            @if($role->name === 'superadmin')
                                <span class="badge bg-danger ms-2">Super</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $role->guard_name }}</span></td>
                        <td>
                            <span class="badge bg-secondary">{{ $role->permissions->count() }} permissions</span>
                        </td>
                        <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->name !== 'superadmin')
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No roles found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
