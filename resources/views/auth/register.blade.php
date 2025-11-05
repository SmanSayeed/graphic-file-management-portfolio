<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - Graphic Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .auth-tabs {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 30px;
        }

        .auth-tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            border: none;
            background: transparent;
            color: #666;
            font-weight: 600;
            transition: all 0.3s;
        }

        .auth-tab.active {
            color: #00B894;
            border-bottom: 3px solid #00B894;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body class="auth-body" onload="checkTab()">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="bi bi-shield-lock"></i>
                <h3>Welcome</h3>
                <p>Login to your account or create a new one</p>
            </div>

            <!-- Tabs -->
            <div class="auth-tabs">
                <button class="auth-tab active" onclick="switchTab('login')">Login</button>
                <button class="auth-tab" onclick="switchTab('register')">Register</button>
            </div>

            <!-- Login Tab -->
            <div id="loginTab" class="tab-content active">
                @if ($errors->any() && old('form_type') !== 'register')
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login') }}" class="auth-form">
                    @csrf
                    <input type="hidden" name="form_type" value="login">

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                            autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-admin-primary w-100">Login</button>
                </form>
            </div>

            <!-- Register Tab -->
            <div id="registerTab" class="tab-content">
                @if ($errors->any() && old('form_type') === 'register')
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.register') }}" class="auth-form">
                    @csrf
                    <input type="hidden" name="form_type" value="register">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Must be at least 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-admin-primary w-100">Register</button>
                </form>
            </div>

            <div class="auth-footer">
                <a href="{{ route('home') }}" class="btn btn-link">
                    <i class="bi bi-arrow-left me-2"></i>Back to Website
                </a>
            </div>
        </div>
    </div>

    <script>
        function checkTab() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            
            if (tab === 'register') {
                switchTab('register');
            }
        }

        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.auth-tab').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            if (tab === 'login') {
                document.getElementById('loginTab').classList.add('active');
                document.querySelectorAll('.auth-tab')[0].classList.add('active');
            } else {
                document.getElementById('registerTab').classList.add('active');
                document.querySelectorAll('.auth-tab')[1].classList.add('active');
            }
        }
    </script>
</body>

</html>
