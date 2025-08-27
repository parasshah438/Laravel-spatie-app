@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1><i class="bi bi-shield-exclamation"></i> Confirm Password</h1>
        <p>Please confirm your password before continuing</p>
    </div>
    
    <div class="auth-body">
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

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

        <form method="POST" action="{{ route('password.confirm') }}" novalidate>
            @csrf

            <div class="form-floating mb-4">
                <input id="password" 
                       type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       autofocus
                       placeholder="Password">
                <label for="password"><i class="bi bi-lock me-2"></i>Current Password</label>
                @error('password')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-auth btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Confirm Password
                </button>
            </div>

            <div class="auth-links">
                <div>
                    <a href="{{ route('password.request') }}">
                        <i class="bi bi-key me-1"></i>Forgot your password?
                    </a>
                </div>
            </div>
        </form>

        <!-- Security Notice -->
        <div class="mt-4 p-3 rounded" style="background: rgba(245, 158, 11, 0.1); border-left: 4px solid #f59e0b;">
            <h6 class="mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Security Notice</h6>
            <small class="text-muted">
                For your security, we require password confirmation when accessing sensitive areas. 
                Your session will remain secure throughout this process.
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