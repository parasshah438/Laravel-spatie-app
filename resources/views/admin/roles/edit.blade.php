@extends('admin.layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role: ' . ucfirst($role->name))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name', $role->name) }}" 
                               required autofocus 
                               placeholder="e.g., manager, editor"
                               {{ $role->name === 'superadmin' ? 'readonly' : '' }}>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Role name should be lowercase and single word</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-shield-alt text-success"></i> Assign Permissions to Role
                        </label>
                        <small class="text-muted d-block mb-3">Select permissions that this role should have access to</small>
                        
                        @if($permissions->count() > 0)
                            @foreach($permissions as $featureName => $featurePermissions)
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cog text-primary"></i>
                                            {{ ucfirst($featureName) }} Feature
                                        </h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-success select-all-feature" 
                                                    data-feature="{{ $featureName }}">
                                                Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning deselect-all-feature" 
                                                    data-feature="{{ $featureName }}">
                                                Deselect All
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($featurePermissions as $permission)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input feature-{{ $featureName }}" type="checkbox" 
                                                       id="permission_{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}"
                                                       {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    <strong>{{ $permission->name }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No permissions found. <a href="{{ route('admin.permissions.create') }}">Create permissions first</a>.
                            </div>
                        @endif

                    @if($role->name === 'superadmin')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Super Admin role has all permissions and cannot be modified.
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <div>
                            @if($role->name !== 'superadmin')
                            <button type="button" class="btn btn-outline-primary me-2" id="selectAll">
                                Select All
                            </button>
                            <button type="button" class="btn btn-outline-secondary me-2" id="deselectAll">
                                Deselect All
                            </button>
                            @endif
                            <button type="submit" class="btn btn-primary" {{ $role->name === 'superadmin' ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> Update Role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@if($role->name !== 'superadmin')
document.getElementById('selectAll').addEventListener('click', function() {
    document.querySelectorAll('input[name="permissions[]"]:not([disabled])').forEach(function(checkbox) {
        checkbox.checked = true;
    });
});

document.getElementById('deselectAll').addEventListener('click', function() {
    document.querySelectorAll('input[name="permissions[]"]:not([disabled])').forEach(function(checkbox) {
        checkbox.checked = false;
    });
});
@endif
</script>
@endpush
