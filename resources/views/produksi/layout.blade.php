<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMKAJAR - Sistem Manajemen Kualitas Jaring Industri">

    <title>@yield('title', 'SIMKAJAR - Admin Produksi')</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    
    {{-- Template Style --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="{{ route('produksi.dashboard') }}" aria-label="Dashboard Produksi">
                    <span class="brand-icon"><i class="bi bi-gear-wide-connected" aria-hidden="true"></i></span>
                    <span class="brand-copy">
                        <span class="brand-title">SIMKAJAR</span>
                        <span class="brand-subtitle">PT Arteria Daya Mulia</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('produksi.dashboard') ? 'active' : '' }}"
                    href="{{ route('produksi.dashboard') }}" aria-current="page">
                    <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('produksi.index') ? 'active' : '' }}" href="{{ route('produksi.index') }}">
                    <span class="nav-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
                    <span class="nav-text">Data Produksi</span>
                </a>
                <a class="nav-link {{ request()->routeIs('pengaturan-mesin.*') ? 'active' : '' }}" href="{{ route('pengaturan-mesin.index') }}">
                    <span class="nav-icon"><i class="bi bi-sliders" aria-hidden="true"></i></span>
                    <span class="nav-text">Pengaturan Mesin</span>
                </a>
                <a class="nav-link" href="#">
                    <span class="nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                    <span class="nav-text">Logout</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span class="sidebar-footer-text">Sistem Aktif</span>
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

                    <span class="navbar-title d-none d-md-inline fw-semibold ms-3">Dashboard Produksi</span>

                    <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                        <input class="form-control search-input" type="search"
                            placeholder="Cari Data Produksi" aria-label="Search">
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
                                    <span class="notification-title">Data Produksi Ditambahkan</span>
                                    <span class="notification-time">Baru saja</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span class="notification-title">Analisis QCC Selesai</span>
                                    <span class="notification-time">10 menit lalu</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span class="notification-title">Evaluasi Taguchi Tersedia</span>
                                    <span class="notification-time">1 jam lalu</span>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle me-2 fs-5"></i>
                                <span class="profile-name d-none d-sm-inline">
                                    @auth
                                        {{ Auth::user()->name }}
                                    @else
                                        Admin Produksi
                                    @endauth
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Pengaturan Akun</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    @auth
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    @endauth
                                </li>
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
                    <span>© {{ date('Y') }} SIMKAJAR <br> PT Arteria Daya Mulia Cirebon</span>
                    <span>Sistem Manajemen Kualitas Jaring Industri.</span>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
```
