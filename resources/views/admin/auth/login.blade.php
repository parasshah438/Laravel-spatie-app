<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Admin Login - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-font-sans-serif: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--bs-font-sans-serif);
            height: 100vh;
            overflow: hidden;
        }

        .admin-login-container {
            display: flex;
            height: 100vh;
            position: relative;
        }

        /* Left Side - Login Form */
        .login-section {
            flex: 0 0 45%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            z-index: 2;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .login-section {
            background: #1a1a1a;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
        }

        /* Right Side - Visual Content */
        .visual-section {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        [data-bs-theme="dark"] .visual-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }

        .visual-content {
            text-align: center;
            color: white;
            z-index: 2;
            position: relative;
        }

        .visual-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .visual-content p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 400px;
        }

        .visual-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            max-width: 400px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-item i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Animated Background Elements */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 20%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 30%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Login Form Styles */
        .login-header {
            margin-bottom: 3rem;
        }

        .login-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        [data-bs-theme="dark"] .login-header h2 {
            color: #ffffff;
        }

        .login-header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        [data-bs-theme="dark"] .login-header p {
            color: #9ca3af;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating > .form-control {
            border: 2px solid #e5e7eb;
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

        .btn-admin-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-admin-login:active {
            transform: translateY(0);
        }

        .form-check {
            margin-bottom: 2rem;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            color: #6b7280;
            font-weight: 500;
        }

        [data-bs-theme="dark"] .form-check-label {
            color: #9ca3af;
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 50px;
            padding: 0.75rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .theme-toggle {
            background: rgba(30, 30, 30, 0.9);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
        }

        /* Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        [data-bs-theme="dark"] .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        /* Demo Credentials */
        .demo-credentials {
            background: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 1.25rem;
            margin-top: 2rem;
        }

        [data-bs-theme="dark"] .demo-credentials {
            background: rgba(59, 130, 246, 0.15);
        }

        .demo-credentials h6 {
            color: #3b82f6;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        [data-bs-theme="dark"] .demo-credentials h6 {
            color: #60a5fa;
        }

        .credential-item {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 0.75rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        [data-bs-theme="dark"] .credential-item {
            background: rgba(0, 0, 0, 0.3);
            color: #e2e8f0;
        }

        .copy-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .login-section {
                flex: 0 0 50%;
                padding: 2rem;
            }
            
            .visual-content h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .admin-login-container {
                flex-direction: column;
            }
            
            .login-section {
                flex: 1;
                padding: 2rem 1.5rem;
                order: 2;
            }
            
            .visual-section {
                flex: 0 0 40%;
                order: 1;
            }
            
            .visual-content h1 {
                font-size: 2rem;
            }
            
            .visual-features {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .login-header {
                margin-bottom: 2rem;
            }
            
            .login-header h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .login-section {
                padding: 1.5rem 1rem;
            }
            
            .visual-section {
                flex: 0 0 30%;
            }
            
            .theme-toggle {
                top: 1rem;
                right: 1rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button class="theme-toggle btn btn-outline-secondary" id="themeToggle" type="button">
        <i class="bi bi-sun-fill" id="themeIcon"></i>
    </button>

    <div class="admin-login-container">
        <!-- Left Side - Login Form -->
        <div class="login-section">
            <div class="login-header">
                <h2><i class="bi bi-shield-lock me-3"></i>Admin Login</h2>
                <p>Welcome back! Please sign in to access the admin panel.</p>
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

            <form method="POST" action="{{ route('admin.login') }}" novalidate>
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

                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="remember" 
                           id="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        <i class="bi bi-check2-square me-1"></i>Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-admin-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Admin Panel
                </button>
            </form>

            <!-- Demo Credentials -->
            <div class="demo-credentials">
                <h6><i class="bi bi-info-circle me-2"></i>Demo Credentials</h6>
                <div class="credential-item">
                    <span><strong>Super Admin:</strong> superadmin@admin.com</span>
                    <button class="copy-btn" onclick="copyToClipboard('superadmin@admin.com')" title="Copy email">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
                <div class="credential-item">
                    <span><strong>Password:</strong> password</span>
                    <button class="copy-btn" onclick="copyToClipboard('password')" title="Copy password">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
                <div class="credential-item">
                    <span><strong>Admin:</strong> admin@admin.com</span>
                    <button class="copy-btn" onclick="copyToClipboard('admin@admin.com')" title="Copy email">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Change default passwords in production environment
                </small>
            </div>
        </div>

        <!-- Right Side - Visual Content -->
        <div class="visual-section">
            <div class="bg-shapes">
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
            </div>
            
            <div class="visual-content">
                <h1>Admin Dashboard</h1>
                <p>Powerful tools and insights to manage your application with confidence and control.</p>
                
                <div class="visual-features">
                    <div class="feature-item">
                        <i class="bi bi-speedometer2"></i>
                        <h6>Analytics</h6>
                        <small>Real-time insights</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-people"></i>
                        <h6>User Management</h6>
                        <small>Complete control</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-shield-check"></i>
                        <h6>Security</h6>
                        <small>Advanced protection</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-gear"></i>
                        <h6>Settings</h6>
                        <small>Full customization</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        // Get saved theme or default to light
        const savedTheme = localStorage.getItem('admin-theme') || 'light';
        html.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('admin-theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-moon-fill';
            } else {
                themeIcon.className = 'bi bi-sun-fill';
            }
        }

        // Copy to clipboard functionality
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show temporary success message
                const btn = event.target.closest('.copy-btn');
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check"></i>';
                btn.style.color = '#22c55e';
                
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                    btn.style.color = '#667eea';
                }, 1000);
            });
        }

        // Form submission and animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="loading"></span> Authenticating...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 5 seconds as fallback
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 5000);
                }
            });

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

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>