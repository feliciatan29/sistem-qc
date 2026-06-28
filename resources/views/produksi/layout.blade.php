```php
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMKAJAR - Sistem Manajemen Kualitas Jaring Industri">

    <title>@yield('title', 'SIMKAJAR - Admin Produksi')</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="admin-shell">

        <div class="sidebar-backdrop" data-sidebar-close></div>

        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="adminSidebar">

            <div class="sidebar-header">

                <a class="brand-mark" href="{{ route('produksi.dashboard') }}">

                    <span class="brand-icon">
                        <i class="bi bi-gear-wide-connected"></i>
                    </span>

                    <span class="brand-copy">
                        <span class="brand-title">
                            SIMKAJAR
                        </span>

                        <span class="brand-subtitle">
                            PT Arteria Daya Mulia
                        </span>
                    </span>

                </a>

            </div>

            <nav class="sidebar-nav">

                <!-- Dashboard -->
                <a class="nav-link {{ request()->routeIs('produksi.dashboard') ? 'active' : '' }}"
                    href="{{ route('produksi.dashboard') }}">

                    <span class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </span>

                    <span class="nav-text">
                        Dashboard
                    </span>

                </a>

                <!-- Data Produksi -->
                <a class="nav-link {{ request()->routeIs('produksi.index') ? 'active' : '' }}"
                    href="{{ route('produksi.index') }}">
                    <span class="nav-icon">
                        <i class="bi bi-table"></i>
                    </span>
                    <span class="nav-text">Data Produksi</span>
                </a>
                <!-- Pengaturan Mesin -->
                <a class="nav-link {{ request()->routeIs('pengaturan-mesin.*') ? 'active' : '' }}"
                    href="{{ route('pengaturan-mesin.index') }}">

                    <span class="nav-icon">
                        <i class="bi bi-sliders"></i>
                    </span>

                    <span class="nav-text">
                        Pengaturan Mesin
                    </span>

                </a>
                <!-- Grafik Produksi -->
                <a class="nav-link" href="#">
                    <span class="nav-icon">
                        <i class="bi bi-bar-chart-line"></i>
                    </span>

                    <span class="nav-text">
                        Grafik Produksi
                    </span>
                </a>

                <!-- Hasil Produksi -->
                <a class="nav-link" href="#">
                    <span class="nav-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </span>

                    <span class="nav-text">
                        Hasil Produksi
                    </span>
                </a>

            </nav>

            <div class="sidebar-footer">

                <span class="status-dot"></span>

                <span class="sidebar-footer-text">
                    Sistem Aktif
                </span>

            </div>

        </aside>

        <!-- MAIN -->
        <div class="admin-main">

            <!-- NAVBAR -->
            <nav class="navbar admin-navbar navbar-expand bg-white">

                <div class="container-fluid px-3 px-lg-4">

                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                        aria-expanded="true">

                        <span></span>
                        <span></span>
                        <span></span>

                    </button>

                    <!-- SEARCH -->
                    <form class="d-none d-md-flex ms-3 flex-grow-1">

                        <input class="form-control search-input" type="search" placeholder="Cari Data Produksi">

                    </form>

                    <div class="navbar-actions ms-auto">

                        <!-- THEME -->
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle>

                            <i class="bi bi-moon-stars" data-theme-icon></i>

                        </button>

                        <!-- NOTIFIKASI -->
                        <div class="dropdown">

                            <button class="icon-button" type="button" data-bs-toggle="dropdown">

                                <span class="notification-dot"></span>

                                <i class="bi bi-bell"></i>

                            </button>

                            <div class="dropdown-menu dropdown-menu-end notification-menu">

                                <div class="dropdown-header fw-bold">

                                    Notifikasi

                                </div>

                                <a class="dropdown-item" href="#">
                                    Data Produksi Ditambahkan
                                </a>

                                <a class="dropdown-item" href="#">
                                    Analisis QCC Selesai
                                </a>

                                <a class="dropdown-item" href="#">
                                    Evaluasi Taguchi Tersedia
                                </a>

                            </div>

                        </div>

                        <!-- PROFILE -->
                        <div class="dropdown">

                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown">

                                <i class="bi bi-person-circle me-2"></i>

                                <span class="profile-name">

                                    @auth
                                        {{ Auth::user()->name }}
                                    @else
                                        Admin Produksi
                                    @endauth

                                </span>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="#">
                                        Profil
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        Pengaturan Akun
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>

                                    @auth

                                        <form method="POST" action="{{ route('logout') }}">

                                            @csrf

                                            <button type="submit" class="dropdown-item">

                                                Logout

                                            </button>

                                        </form>

                                    @endauth

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </nav>

            <!-- CONTENT -->
            <main class="dashboard-content">

                <div class="container-fluid px-3 px-lg-4 py-4">

                    {{-- Header hanya tampil pada Dashboard --}}
                    @if (request()->routeIs('produksi.dashboard'))
                        <div class="page-heading">

                            <div class="page-heading-copy">

                                <span class="page-icon">
                                    <i class="bi bi-speedometer2"></i>
                                </span>

                                <div>

                                    <p class="eyebrow mb-1">
                                        PRODUKSI
                                    </p>

                                    <h1 class="h3 mb-1">
                                        Dashboard Produksi
                                    </h1>

                                    <p class="text-muted mb-0">
                                        Sistem Manajemen Kualitas Jaring Industri
                                    </p>

                                </div>

                            </div>

                        </div>
                    @endif

                    @yield('content')

                </div>

            </main>

            <!-- FOOTER -->
            <footer class="admin-footer">

                <div class="container-fluid px-3 px-lg-4">

                    <span>
                        © {{ date('Y') }} SIMKAJAR
                    </span>

                    <span>
                        PT Arteria Daya Mulia Cirebon
                    </span>

                    <span>
                        Sistem Manajemen Kualitas Jaring Industri
                    </span>

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
