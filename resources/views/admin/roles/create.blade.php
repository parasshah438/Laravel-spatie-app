@extends('admin.layouts.app')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" 
                               required autofocus 
                               placeholder="e.g., manager, editor">
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
                                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" id="selectAll">
                                <i class="fas fa-check-circle"></i> Select All
                            </button>
                            <button type="button" class="btn btn-outline-secondary me-2" id="deselectAll">
                                <i class="fas fa-times-circle"></i> Deselect All
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All button
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
            checkbox.checked = true;
        });
    });
    
    // Deselect All button
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
            checkbox.checked = false;
        });
    });
    
    // Feature-specific select/deselect
    document.querySelectorAll('.select-all-feature').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const feature = this.getAttribute('data-feature');
            document.querySelectorAll('.feature-' + feature).forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });
    });
    
    document.querySelectorAll('.deselect-all-feature').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const feature = this.getAttribute('data-feature');
            document.querySelectorAll('.feature-' + feature).forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });
    });
});
</script>
@endsection
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Role
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
document.getElementById('selectAll').addEventListener('click', function() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
        checkbox.checked = true;
    });
});

document.getElementById('deselectAll').addEventListener('click', function() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
        checkbox.checked = false;
    });
});
</script>
@endpush
