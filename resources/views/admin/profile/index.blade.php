@extends('admin.layouts.app')

@section('title', 'Profile - Admin Panel')
@section('page-title', 'Profile')

@section('content')
<!-- Profile Header -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="profile-avatar">
                            <div class="avatar-circle">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="avatar-status"></div>
                        </div>
                    </div>
                    <div class="col">
                        <h2 class="profile-name mb-1">{{ $admin->name }}</h2>
                        <p class="profile-email text-muted mb-2">{{ $admin->email }}</p>
                        <div class="profile-roles">
                            @forelse($admin->roles as $role)
                                <span class="badge role-badge role-{{ $role->name }}">
                                    <i class="bi bi-shield-check me-1"></i>{{ ucfirst($role->name) }}
                                </span>
                            @empty
                                <span class="badge bg-secondary">
                                    <i class="bi bi-exclamation-circle me-1"></i>No roles assigned
                                </span>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="profile-stats">
                            <div class="stat-item">
                                <div class="stat-value">{{ $admin->created_at->diffInDays(now()) }}</div>
                                <div class="stat-label">Days Active</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4">
    <!-- Profile Information -->
    <div class="col-lg-8">
        <div class="modern-card h-100">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-lines-fill me-2 text-primary"></i>Profile Information
                </h5>
                <p class="text-muted small mb-0 mt-1">Update your account details and personal information</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.profile.update') }}" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $admin->name) }}" 
                                       placeholder="Full Name"
                                       required>
                                <label for="name">
                                    <i class="bi bi-person me-2"></i>Full Name
                                </label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $admin->email) }}" 
                                       placeholder="Email Address"
                                       required>
                                <label for="email">
                                    <i class="bi bi-envelope me-2"></i>Email Address
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-section">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-info-circle me-2 text-info"></i>Account Information
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <div class="info-label">Member Since</div>
                                            <div class="info-value">{{ $admin->created_at->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <div class="info-label">Last Updated</div>
                                            <div class="info-value">{{ $admin->updated_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <div class="info-label">Account Status</div>
                                            <div class="info-value">
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-check-circle me-1"></i>Active
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Profile
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Change Password -->
        <div class="modern-card mb-4">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-shield-lock me-2 text-warning"></i>Security
                </h5>
                <p class="text-muted small mb-0 mt-1">Update your password to keep your account secure</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.profile.password.update') }}" id="passwordForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Current Password"
                                   required>
                            <label for="current_password">
                                <i class="bi bi-key me-2"></i>Current Password
                            </label>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="New Password"
                                   required>
                            <label for="new_password">
                                <i class="bi bi-lock me-2"></i>New Password
                            </label>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="password-strength mt-2" id="passwordStrength"></div>
                    </div>

                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   placeholder="Confirm New Password"
                                   required>
                            <label for="new_password_confirmation">
                                <i class="bi bi-lock-fill me-2"></i>Confirm Password
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-shield-check me-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="modern-card">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-graph-up me-2 text-success"></i>Account Overview
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-6">
                        <div class="stat-card-small primary">
                            <div class="stat-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $admin->roles->count() }}</div>
                                <div class="stat-label">Roles</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-6">
                        <div class="stat-card-small success">
                            <div class="stat-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $admin->getAllPermissions()->count() }}</div>
                                <div class="stat-label">Permissions</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-6">
                        <div class="stat-card-small info">
                            <div class="stat-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $admin->created_at->diffInDays(now()) }}</div>
                                <div class="stat-label">Days Active</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-6">
                        <div class="stat-card-small warning">
                            <div class="stat-icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">Active</div>
                                <div class="stat-label">Status</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="security-tips mt-4 pt-3 border-top">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-lightbulb me-2 text-warning"></i>Security Tips
                    </h6>
                    <ul class="security-list">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Use a strong, unique password</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Enable two-factor authentication</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Regular password updates</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Monitor account activity</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile-specific styles */
.profile-avatar {
    position: relative;
    display: inline-block;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

[data-bs-theme="dark"] .avatar-circle {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
}

.avatar-status {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #22c55e;
    border: 3px solid #ffffff;
}

[data-bs-theme="dark"] .avatar-status {
    border-color: #1e293b;
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

[data-bs-theme="dark"] .profile-name {
    color: #f1f5f9;
}

.profile-email {
    font-size: 1.1rem;
    color: #64748b;
}

[data-bs-theme="dark"] .profile-email {
    color: #94a3b8;
}

.role-badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin-right: 0.5rem;
    font-weight: 500;
}

.role-superadmin {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.role-admin {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

[data-bs-theme="dark"] .role-superadmin {
    background: rgba(239, 68, 68, 0.2);
    color: #fca5a5;
}

[data-bs-theme="dark"] .role-admin {
    background: rgba(59, 130, 246, 0.2);
    color: #93c5fd;
}

.profile-stats {
    text-align: center;
}

.stat-item {
    padding: 1rem;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 12px;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

[data-bs-theme="dark"] .stat-item {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.3);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
}

[data-bs-theme="dark"] .stat-value {
    color: #60a5fa;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 0.25rem;
}

[data-bs-theme="dark"] .stat-label {
    color: #94a3b8;
}

.info-section {
    background: rgba(59, 130, 246, 0.05);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(59, 130, 246, 0.1);
}

[data-bs-theme="dark"] .info-section {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.2);
}

.info-item {
    text-align: center;
}

.info-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

[data-bs-theme="dark"] .info-label {
    color: #94a3b8;
}

.info-value {
    font-weight: 600;
    color: #1e293b;
}

[data-bs-theme="dark"] .info-value {
    color: #f1f5f9;
}

.stat-card-small {
    background: #ffffff;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    height: 100%;
    min-height: 80px;
}

.stat-card-small:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

[data-bs-theme="dark"] .stat-card-small {
    background: #1e293b;
    border-color: #334155;
}

.stat-card-small.primary { border-left: 4px solid #3b82f6; }
.stat-card-small.success { border-left: 4px solid #10b981; }
.stat-card-small.info { border-left: 4px solid #06b6d4; }
.stat-card-small.warning { border-left: 4px solid #f59e0b; }

.stat-card-small .stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-card-small.primary .stat-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.stat-card-small.success .stat-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-card-small.info .stat-icon {
    background: rgba(6, 182, 212, 0.1);
    color: #06b6d4;
}

.stat-card-small.warning .stat-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-card-small .stat-content .stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

[data-bs-theme="dark"] .stat-card-small .stat-content .stat-value {
    color: #f1f5f9;
}

.stat-card-small .stat-content .stat-label {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
}

[data-bs-theme="dark"] .stat-card-small .stat-content .stat-label {
    color: #94a3b8;
}

.security-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.security-list li {
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: #64748b;
}

[data-bs-theme="dark"] .security-list li {
    color: #94a3b8;
}

.password-strength {
    height: 4px;
    border-radius: 2px;
    background: #e2e8f0;
    overflow: hidden;
    transition: all 0.3s ease;
}

[data-bs-theme="dark"] .password-strength {
    background: #334155;
}

.password-strength.weak {
    background: linear-gradient(90deg, #ef4444 0%, #ef4444 33%, #e2e8f0 33%);
}

.password-strength.medium {
    background: linear-gradient(90deg, #f59e0b 0%, #f59e0b 66%, #e2e8f0 66%);
}

.password-strength.strong {
    background: linear-gradient(90deg, #10b981 0%, #10b981 100%);
}

/* Form enhancements */
.form-floating > .form-control {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem 1rem;
    height: auto;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.form-floating > .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    background: #ffffff;
}

[data-bs-theme="dark"] .form-floating > .form-control {
    background-color: #2d3748;
    border-color: #4a5568;
    color: #e2e8f0;
}

[data-bs-theme="dark"] .form-floating > .form-control:focus {
    background-color: #374151;
    border-color: #667eea;
    color: #e2e8f0;
}

[data-bs-theme="dark"] .form-floating > label {
    color: #9ca3af;
}

.form-floating > label {
    color: #6b7280;
    font-weight: 500;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const newPasswordInput = document.getElementById('new_password');
    const passwordStrengthDiv = document.getElementById('passwordStrength');
    const confirmPasswordInput = document.getElementById('new_password_confirmation');

    if (newPasswordInput && passwordStrengthDiv) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
        });
    }

    // Password confirmation validation
    if (confirmPasswordInput && newPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (newPasswordInput.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });

        newPasswordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value && newPasswordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
                confirmPasswordInput.classList.add('is-invalid');
            } else {
                confirmPasswordInput.setCustomValidity('');
                confirmPasswordInput.classList.remove('is-invalid');
            }
        });
    }

    // Form submission with loading states
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');

    if (profileForm) {
        profileForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    }

    if (passwordForm) {
        passwordForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Changing...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    }

    // Add floating label animation
    const inputs = document.querySelectorAll('.form-floating input');
    inputs.forEach(input => {
        if (input.value) {
            input.classList.add('has-value');
        }
        
        input.addEventListener('input', function() {
            if (this.value) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
        
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});

function calculatePasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

function updatePasswordStrengthIndicator(strength) {
    const passwordStrengthDiv = document.getElementById('passwordStrength');
    if (!passwordStrengthDiv) return;

    passwordStrengthDiv.className = 'password-strength';
    
    if (strength <= 2) {
        passwordStrengthDiv.classList.add('weak');
    } else if (strength <= 4) {
        passwordStrengthDiv.classList.add('medium');
    } else {
        passwordStrengthDiv.classList.add('strong');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
</script>
@endpush