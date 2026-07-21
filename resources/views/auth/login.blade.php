<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMKAJAR</title>
    
    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }

        .login-image {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            width: 50%;
            position: relative;
            overflow: hidden;
        }

        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.1)" stroke-width="2" fill="none"/></svg>') repeat;
            opacity: 0.5;
        }

        .login-form-container {
            padding: 50px 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -1px;
            margin-bottom: 5px;
            position: relative;
            z-index: 2;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
            position: relative;
            z-index: 2;
        }

        .form-floating .form-control {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 1rem 0.75rem;
        }

        .form-floating .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-login {
            background: #2563eb;
            color: white;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e2e8f0;
            background: white;
            border-right: none;
            color: #64748b;
        }

        .form-control-with-icon {
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding-left: 0;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                margin: 20px;
                max-width: 100%;
            }
            .login-image {
                width: 100%;
                padding: 30px;
                text-align: center;
            }
            .login-form-container {
                width: 100%;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Left Side: Branding -->
        <div class="login-image">
            <div style="z-index: 2;">
                <i class="bi bi-gear-wide-connected" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                <div class="brand-title">SIMKAJAR</div>
                <div class="brand-subtitle">Sistem Informasi Manajemen Kualitas Jaring<br>PT Arteria Daya Mulia</div>
                
                <div class="mt-5">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                        <span>Monitoring Produksi Real-time</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-shield-check me-3 fs-5"></i>
                        <span>Quality Control Terpadu</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up-arrow me-3 fs-5"></i>
                        <span>Analisis Pareto & FMEA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-form-container">
            <div class="text-center mb-4">
                <h4 class="fw-bold text-dark">Selamat Datang</h4>
                <p class="text-muted">Masuk untuk mengakses dashboard Anda</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span class="small">{{ $errors->first() }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control form-control-with-icon" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control form-control-with-icon" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 d-flex justify-content-center align-items-center">
                    <span>Masuk ke Sistem</span>
                    <i class="bi bi-box-arrow-in-right ms-2 fs-5"></i>
                </button>
                
                <div class="text-center mt-4">
                    <p class="text-muted small mb-0">Role Default: <br> <b>produksi</b> (pass: password) | <b>qc</b> (pass: password)</p>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
