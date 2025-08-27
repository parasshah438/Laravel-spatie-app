@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1><i class="bi bi-shield-lock"></i> Welcome Back</h1>
        <p>Sign in to your account to continue</p>
    </div>
    
    <div class="auth-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="form-floating">
                <input id="email" 
                       type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
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
                       autocomplete="current-password"
                       placeholder="Password">
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                @error('password')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="remember" 
                       id="remember" 
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    <i class="bi bi-check2-square me-1"></i>Remember me for 30 days
                </label>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-auth btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </div>

            <div class="divider">
                <span>or</span>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="button" class="btn btn-outline-auth">
                    <i class="bi bi-google me-2"></i>Continue with Google
                </button>
            </div>

            <div class="auth-links">
                @if (Route::has('password.request'))
                    <div class="mb-3">
                        <a href="{{ route('password.request') }}">
                            <i class="bi bi-key me-1"></i>Forgot your password?
                        </a>
                    </div>
                @endif
                
                @if (Route::has('register'))
                    <div>
                        Don't have an account? 
                        <a href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i>Create one here
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    // Add floating label animation
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-floating input');
        
        inputs.forEach(input => {
            // Check if input has value on page load
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
</script>
@endsection