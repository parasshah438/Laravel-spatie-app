@extends('admin.layouts.app')

@section('title', 'Create Feature Permissions')
@section('page-title', 'Create Feature Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Create New Feature Permissions</h5>
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
                        
                        <div class="mb-3">
                            <label for="feature_name" class="form-label">Feature Name</label>
                            <input type="text" 
                                   class="form-control @error('feature_name') is-invalid @enderror" 
                                   id="feature_name" 
                                   name="feature_name" 
                                   value="{{ old('feature_name') }}" 
                                   placeholder="e.g., blogs, sliders, services"
                                   required>
                            @error('feature_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Permissions to Create</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="manage" id="manage" name="permissions[]" checked>
                                        <label class="form-check-label" for="manage">Manage</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="create" id="create" name="permissions[]" checked>
                                        <label class="form-check-label" for="create">Create</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="view" id="view" name="permissions[]" checked>
                                        <label class="form-check-label" for="view">View</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="edit" id="edit" name="permissions[]" checked>
                                        <label class="form-check-label" for="edit">Edit</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div id="permission-preview" class="border p-3 bg-light rounded">
                                <em class="text-muted">Enter feature name to see preview...</em>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Permissions</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
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
                `<span class="badge bg-primary me-2">${perm} ${featureName}</span>`
            ).join('');
            preview.innerHTML = permissions;
        } else {
            preview.innerHTML = '<em class="text-muted">Enter feature name to see preview...</em>';
        }
    }
    
    featureInput.addEventListener('input', updatePreview);
    checkboxes.forEach(cb => cb.addEventListener('change', updatePreview));
});
</script>
@endsection
