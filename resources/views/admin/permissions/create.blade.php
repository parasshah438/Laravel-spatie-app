@extends('admin.layouts.app')

@section('title', 'Add New Feature Permissions')
@section('page-title', 'Add New Feature Permissions')

@section('top-buttons')
<a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Permissions
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Create New Feature Permissions
                </h5>
                <small>Add a new feature like blogs, sliders, services with CRUD permissions</small>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.permissions.store') }}">
                    @csrf
                    
                    <!-- Feature Name -->
                    <div class="mb-4">
                        <label for="feature_name" class="form-label">
                            <i class="fas fa-cog text-primary"></i> Feature Name
                        </label>
                        <input type="text" 
                               class="form-control @error('feature_name') is-invalid @enderror" 
                               id="feature_name" 
                               name="feature_name" 
                               value="{{ old('feature_name') }}" 
                               placeholder="e.g., blogs, sliders, services, products"
                               required>
                        <div class="form-text">
                            Enter feature name in lowercase. Examples: blogs, sliders, services, products, categories
                        </div>
                        @error('feature_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Permission Types -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-shield-alt text-success"></i> Select Permissions to Create
                        </label>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="manage" id="manage" name="permissions[]" checked>
                                    <label class="form-check-label" for="manage">
                                        <strong>Manage</strong>
                                        <br><small class="text-muted">Overall feature access</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="create" id="create" name="permissions[]" checked>
                                    <label class="form-check-label" for="create">
                                        <strong>Create</strong>
                                        <br><small class="text-muted">Add new records</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="view" id="view" name="permissions[]" checked>
                                    <label class="form-check-label" for="view">
                                        <strong>View</strong>
                                        <br><small class="text-muted">List and view records</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="edit" id="edit" name="permissions[]" checked>
                                    <label class="form-check-label" for="edit">
                                        <strong>Edit</strong>
                                        <br><small class="text-muted">Update existing records</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="delete" id="delete" name="permissions[]">
                                    <label class="form-check-label" for="delete">
                                        <strong>Delete</strong>
                                        <br><small class="text-muted">Remove records</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('permissions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Preview -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-eye text-info"></i> Preview Generated Permissions
                        </label>
                        <div id="permission-preview" class="border p-3 bg-light rounded">
                            <em class="text-muted">Enter feature name to see preview...</em>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Feature Permissions
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card shadow mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-question-circle"></i> What happens next?
                </h6>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li><strong>Permissions Created:</strong> The system will create selected permissions for your feature</li>
                    <li><strong>Assign to Roles:</strong> Go to <a href="{{ route('admin.roles.index') }}">Role Management</a> to assign these permissions</li>
                    <li><strong>Protect Routes:</strong> Use middleware like <code>permission:manage blogs</code> in your routes</li>
                    <li><strong>Control Views:</strong> Use <code>@can('manage blogs')</code> in your Blade templates</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const featureInput = document.getElementById('feature_name');
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    const preview = document.getElementById('permission-preview');
    
    function updatePreview() {
        const featureName = featureInput.value.toLowerCase().trim();
        const selectedPermissions = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (featureName && selectedPermissions.length > 0) {
            const permissions = selectedPermissions.map(perm => 
                `<span class="badge bg-primary me-2 mb-1">${perm} ${featureName}</span>`
            ).join('');
            preview.innerHTML = permissions;
        } else {
            preview.innerHTML = '<em class="text-muted">Enter feature name and select permissions to see preview...</em>';
        }
    }
    
    featureInput.addEventListener('input', updatePreview);
    checkboxes.forEach(cb => cb.addEventListener('change', updatePreview));
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection
