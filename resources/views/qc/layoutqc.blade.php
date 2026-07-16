<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Quality Control - Monitoring Pengendalian Kualitas Produksi">

    <title>@yield('title', 'Dashboard QC') | Quality Control</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    {{-- Template Style --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="{{ route('qc.dashboard') }}" aria-label="Dashboard Quality Control">
                    <span class="brand-icon"><i class="bi bi-gear-wide-connected" aria-hidden="true"></i></span>
                    <span class="brand-copy">
                        <span class="brand-title">SIMKAJAR</span>
                        <span class="brand-subtitle">PT Arteria Daya Mulia</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('qc.dashboard') ? 'active' : '' }}"
                    href="{{ route('qc.dashboard') }}" aria-current="page">
                    <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('qc.pemeriksaan.*') ? 'active' : '' }}" href="{{ route('qc.pemeriksaan.index') }}">
                    <span class="nav-icon"><i class="bi bi-clipboard2-check" aria-hidden="true"></i></span>
                    <span class="nav-text">Data Pemeriksaan QC</span>
                </a>
                <a class="nav-link {{ request()->routeIs('qc.defect.summary') ? 'active' : '' }}" href="{{ route('qc.defect.summary') }}">
                    <span class="nav-icon"><i class="bi bi-exclamation-circle" aria-hidden="true"></i></span>
                    <span class="nav-text">Data Defect</span>
                </a>
                <a class="nav-link {{ request()->routeIs('qc.analisis.pareto') ? 'active' : '' }}" href="{{ route('qc.analisis.pareto') }}">
                    <span class="nav-icon"><i class="bi bi-bar-chart-steps" aria-hidden="true"></i></span>
                    <span class="nav-text">Analisis Pareto</span>
                </a>
                <a class="nav-link {{ request()->routeIs('qc.fmea.index') ? 'active' : '' }}" href="{{ route('qc.fmea.index') }}">
                    <span class="nav-icon"><i class="bi bi-shield-exclamation" aria-hidden="true"></i></span>
                    <span class="nav-text">Analisis FMEA</span>
                </a>
                <a class="nav-link" href="#">
                    <span class="nav-icon"><i class="bi bi-sliders" aria-hidden="true"></i></span>
                    <span class="nav-text">Optimasi Taguchi</span>
                </a>
                <a class="nav-link" href="#">
                    <span class="nav-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
                    <span class="nav-text">Estimasi Kerugian</span>
                </a>
                <a class="nav-link" href="#">
                    <span class="nav-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                    <span class="nav-text">Laporan</span>
                </a>
                <a class="nav-link" href="#">
                    <span class="nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                    <span class="nav-text">Logout</span>
                </a>
            </nav>


            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span class="sidebar-footer-text">Sistem berjalan normal</span>
            </div>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                        aria-expanded="true" aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <span class="navbar-title d-none d-md-inline fw-semibold ms-3">Dashboard QC</span>

                    <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                        <input class="form-control search-input" type="search"
                            placeholder="Cari data QC, defect, laporan" aria-label="Search">
                    </form>

                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle
                            aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>
                        <div class="dropdown">
                            <button class="icon-button" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false" aria-label="Notifications">
                                <span class="notification-dot"></span>
                                <i class="bi bi-bell" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <div class="dropdown-header fw-bold text-body">Notifikasi</div>
                                <a class="dropdown-item" href="#">
                                    <span class="notification-title">Defect baru terdeteksi</span>
                                    <span class="notification-time">4 menit lalu</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span class="notification-title">Laporan mingguan siap</span>
                                    <span class="notification-time">32 menit lalu</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span class="notification-title">Parameter Taguchi diperbarui</span>
                                    <span class="notification-time">1 jam lalu</span>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle me-2 fs-5"></i>
                                <span class="profile-name d-none d-sm-inline">Admin QC</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Pengaturan Akun</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    @yield('content')
                </div>
            </main>

            <footer class="admin-footer">
                <div class="container-fluid px-3 px-lg-4">
                    <span>Copyright 2026 Dashboard Quality Control. <br> Sistem Monitoring Pengendalian Kualitas
                        Produksi</span>
                    <span>Modul Quality Control.</span>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
