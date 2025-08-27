@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1><i class="bi bi-key"></i> Reset Password</h1>
        <p>Enter your email to receive a password reset link</p>
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

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <div class="form-floating mb-4">
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

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-auth btn-lg">
                    <i class="bi bi-send me-2"></i>Send Reset Link
                </button>
            </div>

            <div class="auth-links">
                <div class="mb-3">
                    <a href="{{ route('login') }}">
                        <i class="bi bi-arrow-left me-1"></i>Back to Sign In
                    </a>
                </div>
                
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

        <!-- Help Section -->
        <div class="mt-4 p-3 rounded" style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6;">
            <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Need Help?</h6>
            <small class="text-muted">
                If you don't receive an email within a few minutes, please check your spam folder or 
                <a href="mailto:support@example.com" class="text-decoration-none">contact support</a>.
            </small>
        </div>
    </div>
</div>

<script>
    // Add floating label animation
    document.addEventListener('DOMContentLoaded', function() {
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
</script>
@endsection