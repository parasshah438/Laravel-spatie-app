@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1><i class="bi bi-shield-lock"></i> New Password</h1>
        <p>Create a new secure password for your account</p>
    </div>
    
    <div class="auth-body">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                @if ($errors->count() > 1)
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $errors->first() }}
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-floating">
                <input id="email" 
                       type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ $email ?? old('email') }}" 
                       required 
                       autocomplete="email" 
                       autofocus
                       placeholder="name@example.com">
                <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                @error('email')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="form-floating">
                <input id="password" 
                       type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       required 
                       autocomplete="new-password"
                       placeholder="New Password"
                       minlength="8">
                <label for="password"><i class="bi bi-lock me-2"></i>New Password</label>
                <div class="form-text">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Must be at least 8 characters long
                    </small>
                </div>
                @error('password')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="form-floating">
                <input id="password-confirm" 
                       type="password" 
                       class="form-control" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       placeholder="Confirm New Password">
                <label for="password-confirm"><i class="bi bi-lock-fill me-2"></i>Confirm New Password</label>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-auth btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Reset Password
                </button>
            </div>

            <div class="auth-links">
                <div>
                    Remember your password? 
                    <a href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Sign in here
                    </a>
                </div>
            </div>
        </form>

        <!-- Security Tips -->
        <div class="mt-4 p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border-left: 4px solid #22c55e;">
            <h6 class="mb-2"><i class="bi bi-shield-check me-2"></i>Password Security Tips</h6>
            <ul class="small text-muted mb-0 ps-3">
                <li>Use a mix of uppercase and lowercase letters</li>
                <li>Include numbers and special characters</li>
                <li>Make it at least 8 characters long</li>
                <li>Avoid using personal information</li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password-confirm');
        
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

        // Password confirmation validation
        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });

        passwordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
                confirmPasswordInput.classList.add('is-invalid');
            } else {
                confirmPasswordInput.setCustomValidity('');
                confirmPasswordInput.classList.remove('is-invalid');
            }
        });

        // Password strength indicator
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
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
            // You can add a visual strength indicator here if needed
            const strengthTexts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            const strengthColors = ['#dc2626', '#ea580c', '#d97706', '#65a30d', '#16a34a'];
            
            // This is where you could add visual feedback
            console.log(`Password strength: ${strengthTexts[strength - 1] || 'Very Weak'}`);
        }
    });
</script>
@endsection