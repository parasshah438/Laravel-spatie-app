@extends('layouts.auth')

@section('title', 'Create Account')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1><i class="bi bi-person-plus"></i> Create Account</h1>
        <p>Join us today and get started in minutes</p>
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

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="form-floating">
                <input id="name" 
                       type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autocomplete="name" 
                       autofocus
                       placeholder="Full Name">
                <label for="name"><i class="bi bi-person me-2"></i>Full Name</label>
                @error('name')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="form-floating">
                <input id="email" 
                       type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="email"
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
                       placeholder="Password"
                       minlength="8">
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
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
                       placeholder="Confirm Password">
                <label for="password-confirm"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="terms" 
                       id="terms" 
                       required>
                <label class="form-check-label" for="terms">
                    <i class="bi bi-check2-square me-1"></i>
                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                    and <a href="#" class="text-decoration-none">Privacy Policy</a>
                </label>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-auth btn-lg">
                    <i class="bi bi-person-check me-2"></i>Create Account
                </button>
            </div>

            <div class="divider">
                <span>or</span>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="button" class="btn btn-outline-auth">
                    <i class="bi bi-google me-2"></i>Sign up with Google
                </button>
            </div>

            <div class="auth-links">
                <div>
                    Already have an account? 
                    <a href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Sign in here
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Password strength indicator
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
    });
</script>
@endsection