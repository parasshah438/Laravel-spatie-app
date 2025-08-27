@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1><i class="bi bi-envelope-check"></i> Verify Email</h1>
        <p>Check your email for a verification link</p>
    </div>
    
    <div class="auth-body">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="bi bi-envelope-check" style="font-size: 4rem; color: #667eea;"></i>
            </div>
            <h5>Check Your Email</h5>
            <p class="text-muted">
                Before proceeding, please check your email for a verification link. 
                If you didn't receive the email, you can request another one below.
            </p>
        </div>

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-auth btn-lg">
                    <i class="bi bi-arrow-clockwise me-2"></i>Resend Verification Email
                </button>
            </div>
        </form>

        <div class="auth-links">
            <div class="mb-3">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-1"></i>Sign out and try a different account
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-4 p-3 rounded" style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6;">
            <h6 class="mb-2"><i class="bi bi-question-circle me-2"></i>Didn't receive the email?</h6>
            <ul class="small text-muted mb-0 ps-3">
                <li>Check your spam or junk folder</li>
                <li>Make sure you entered the correct email address</li>
                <li>Wait a few minutes and try again</li>
                <li>Contact support if you continue having issues</li>
            </ul>
        </div>

        <!-- Email Tips -->
        <div class="mt-3 p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border-left: 4px solid #22c55e;">
            <h6 class="mb-2"><i class="bi bi-lightbulb me-2"></i>Email Tips</h6>
            <small class="text-muted">
                Add our email address to your contacts to ensure you receive important notifications. 
                The verification link will expire after 24 hours for security reasons.
            </small>
        </div>
    </div>
</div>

<script>
    // Auto-refresh page after email verification (optional)
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user has verified email every 30 seconds
        let checkInterval;
        
        function checkVerificationStatus() {
            fetch('/email/verify-check', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    clearInterval(checkInterval);
                    window.location.href = data.redirect || '/home';
                }
            })
            .catch(error => {
                console.log('Verification check failed:', error);
            });
        }

        // Start checking after 10 seconds, then every 30 seconds
        setTimeout(() => {
            checkInterval = setInterval(checkVerificationStatus, 30000);
        }, 10000);

        // Clear interval when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && checkInterval) {
                clearInterval(checkInterval);
            } else if (!document.hidden && !checkInterval) {
                checkInterval = setInterval(checkVerificationStatus, 30000);
            }
        });
    });
</script>
@endsection