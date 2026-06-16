<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SIMKAJAR - Admin Produksi')</title>

   <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
   <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="admin-shell">

    <div class="sidebar-backdrop" data-sidebar-close></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">

        <div class="sidebar-header">

            <a class="brand-mark" href="{{ url('/') }}">

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
            <a class="nav-link" href="{{ url('/') }}">
                <span class="nav-icon">
                    <i class="bi bi-speedometer2"></i>
                </span>
                <span class="nav-text">
                    Dashboard
                </span>
            </a>

            <!-- Data Produksi -->
            <a class="nav-link" href="#">
                <span class="nav-icon">
                    <i class="bi bi-box-seam"></i>
                </span>
                <span class="nav-text">
                    Data Produksi
                </span>
            </a>

            <!-- Pengaturan Mesin -->
            <a class="nav-link" href="#">
                <span class="nav-icon">
                    <i class="bi bi-sliders"></i>
                </span>
                <span class="nav-text">
                    Pengaturan Mesin
                </span>
            </a>

            <!-- Data Kerusakan -->
            <a class="nav-link" href="#">
                <span class="nav-icon">
                    <i class="bi bi-exclamation-octagon"></i>
                </span>
                <span class="nav-text">
                    Data Kerusakan
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

    <!-- Main -->
    <div class="admin-main">

        <!-- Navbar -->
        <nav class="navbar admin-navbar navbar-expand bg-white">

            <div class="container-fluid px-3 px-lg-4">

                <button class="sidebar-toggle"
                        type="button"
                        data-sidebar-toggle>

                    <span></span>
                    <span></span>
                    <span></span>

                </button>

                <h5 class="ms-3 mb-0">
                    @yield('header', 'Dashboard Produksi')
                </h5>

                <div class="navbar-actions ms-auto">

                    <div class="dropdown">

                        <button class="profile-button dropdown-toggle"
                                data-bs-toggle="dropdown">

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
                                @else
                                <a href="#" class="dropdown-item">
                                    Logout
                                </a>
                                @endauth

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </nav>

        <!-- Content -->
        <main class="dashboard-content">

            <div class="container-fluid px-3 px-lg-4 py-4">

                @yield('content')

            </div>

        </main>

        <!-- Footer -->
        <footer class="admin-footer">

            <div class="container-fluid px-3 px-lg-4">

                <span>
                    © {{ date('Y') }} Sistem Manajemen Produksi Jaring Industri
                </span>

                <span>
                    PT Arteria Daya Mulia Cirebon
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
