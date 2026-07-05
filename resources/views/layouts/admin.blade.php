<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SISMOKAP - Construction Monitor')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #111c44;
            --sidebar-color: #a3aed0;
            --sidebar-hover: #ffffff;
            --sidebar-active: #2b6cb0;
            --sidebar-active-bg: #2d60ff;
            --body-bg: #f4f7fe;
            --navbar-bg: #ffffff;
            --font-family: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--body-bg);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            background-color: var(--sidebar-bg);
            width: 280px;
            height: 100vh;
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.05);
        }

        #sidebar::-webkit-scrollbar {
            width: 6px;
        }
        #sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 3px;
        }
        #sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Sidebar Overlay on mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 999;
            transition: all 0.3s ease;
        }
        #sidebar-overlay.show {
            display: block;
        }

        #sidebar .logo-section {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        #sidebar .logo-text {
            color: #ffffff;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 0.5px;
            margin: 0;
            display: flex;
            align-items: center;
        }

        #sidebar .logo-subtext {
            color: var(--sidebar-color);
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            margin-left: 36px;
        }

        #sidebar .menu-section {
            padding: 20px 14px;
        }

        #sidebar .menu-header {
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 12px 14px 6px;
            margin-top: 10px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-color);
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link i {
            font-size: 18px;
            margin-right: 12px;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link:hover {
            color: var(--sidebar-hover);
            background-color: rgba(255, 255, 255, 0.05);
        }

        #sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--sidebar-active-bg);
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(45, 96, 255, 0.25);
        }

        /* Main Content Wrapper */
        #main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Navbar Styling */
        #navbar {
            background-color: var(--navbar-bg);
            padding: 16px 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .navbar-brand-title {
            font-weight: 700;
            font-size: 24px;
            color: #1b2559;
            margin: 0;
        }

        .navbar-search {
            position: relative;
            max-width: 300px;
        }

        .navbar-search input {
            background-color: #f4f7fe;
            border: none;
            border-radius: 30px;
            padding: 10px 20px 10px 45px;
            font-size: 14px;
            width: 100%;
            color: #1b2559;
            transition: all 0.2s;
        }

        .navbar-search input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(45, 96, 255, 0.1);
        }

        .navbar-search i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #8f9bba;
            font-size: 16px;
        }

        .profile-section {
            display: flex;
            align-items: center;
        }

        .profile-name {
            font-weight: 600;
            font-size: 14px;
            color: #1b2559;
            margin: 0;
            line-height: 1.2;
        }

        .profile-role {
            font-size: 11px;
            color: #8f9bba;
            font-weight: 500;
            margin: 0;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        /* Card Custom */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar {
                left: -280px;
            }
            #sidebar.show {
                left: 0;
            }
            #main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar Overlay for mobile -->
    <div id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="logo-section">
            <div class="logo-text" style="font-size: 20px; font-weight: 800; color: #ffffff; display: flex; align-items: center; gap: 12px; font-family: 'Outfit', sans-serif; letter-spacing: 1px;">
                @php
                    $logoUrl = \App\Models\Setting::getLogoUrl();
                    $namaInstansi = \App\Models\Setting::getValue('nama_instansi', 'SISMOKAP');
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" style="height: 34px; object-fit: contain;">
                @else
                    <i class="bi bi-shield-fill text-primary"></i>
                @endif
                <span>{{ $namaInstansi }}</span>
            </div>
        </div>

        <div class="menu-section">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>

            <!-- Menu Master Data (Hanya untuk Admin) -->
            @if(auth()->user()->role === 'admin')
                <div class="menu-header">Master Data</div>
                <a href="{{ route('admin.proyek.index') }}" class="nav-link {{ Request::is('admin/proyek*') ? 'active' : '' }}">
                    <i class="bi bi-folder-fill"></i> Data Proyek
                </a>
                <a href="{{ route('admin.lokasi.index') }}" class="nav-link {{ Request::is('admin/lokasi*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill"></i> Lokasi
                </a>
                <a href="{{ route('admin.kontraktor.index') }}" class="nav-link {{ Request::is('admin/kontraktor*') ? 'active' : '' }}">
                    <i class="bi bi-building-fill"></i> Kontraktor
                </a>
                <a href="{{ route('admin.personel.index') }}" class="nav-link {{ Request::is('admin/personel*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Personel
                </a>
                <a href="{{ route('admin.user.index') }}" class="nav-link {{ Request::is('admin/user*') ? 'active' : '' }}">
                    <i class="bi bi-person-fill-gear"></i> User
                </a>
            @endif

            <!-- Menu Monitoring (Untuk semua role) -->
            <div class="menu-header">Monitoring</div>
            <a href="{{ route('progress-harian.index') }}" class="nav-link {{ Request::is('monitoring/progress-harian*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> Progress Harian
            </a>
            <a href="{{ route('progress-mingguan.index') }}" class="nav-link {{ Request::is('monitoring/progress-mingguan*') ? 'active' : '' }}">
                <i class="bi bi-calendar-range-fill"></i> Progress Mingguan
            </a>
            <a href="{{ route('dokumentasi.index') }}" class="nav-link {{ Request::is('monitoring/dokumentasi*') ? 'active' : '' }}">
                <i class="bi bi-cloud-arrow-up-fill"></i> Upload Dokumentasi
            </a>
            <a href="{{ route('monitoring.timeline') }}" class="nav-link {{ Request::is('monitoring/timeline*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Timeline
            </a>
            <a href="{{ route('monitoring.persentase-progress') }}" class="nav-link {{ Request::is('monitoring/persentase-progress*') ? 'active' : '' }}">
                <i class="bi bi-percent"></i> Persentase Progress
            </a>

            <!-- Menu Laporan -->
            <div class="menu-header">Laporan</div>
            <a href="{{ route('laporan.harian') }}" class="nav-link {{ Request::is('laporan/harian*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i> Laporan Harian
            </a>
            <a href="{{ route('laporan.mingguan') }}" class="nav-link {{ Request::is('laporan/mingguan*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i> Laporan Mingguan
            </a>
            <a href="{{ route('laporan.bulanan') }}" class="nav-link {{ Request::is('laporan/bulanan*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i> Laporan Bulanan
            </a>
            <a href="{{ route('laporan.rekap') }}" class="nav-link {{ Request::is('laporan/rekap*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Rekap Progress
            </a>

            <!-- Menu Setting -->
            @if(auth()->user()->role === 'admin')
                <div class="menu-header">Setting</div>
                <a href="{{ route('admin.setting.pengaturan') }}" class="nav-link {{ Request::is('admin/setting/pengaturan*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Pengaturan
                </a>
                <a href="{{ route('admin.setting.backup') }}" class="nav-link {{ Request::is('admin/setting/backup*') ? 'active' : '' }}">
                    <i class="bi bi-database-fill"></i> Backup Database
                </a>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Navbar -->
        <nav id="navbar" class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-2" id="sidebar-toggle">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <h1 class="navbar-brand-title">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="profile-section dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="text-end d-none d-sm-block me-2">
                            <h6 class="profile-name">{{ auth()->user()->name }}</h6>
                            <p class="profile-role">
                                {{ auth()->user()->role === 'admin' ? 'Super Admin' : (auth()->user()->role === 'pimpinan' ? 'Pimpinan' : 'Operator') }}
                            </p>
                        </div>
                        <div class="profile-avatar d-flex align-items-center justify-content-center bg-secondary-subtle text-secondary border">
                            <i class="bi bi-person-fill fs-5"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="profileDropdown">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content Container -->
        <div class="container-fluid p-4">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        if (toggle && sidebar && overlay) {
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // Sidebar scroll state persistence
        if (sidebar) {
            // Restore scroll position
            const savedScroll = localStorage.getItem('sidebar-scroll');
            if (savedScroll) {
                sidebar.scrollTop = savedScroll;
            }

            // Save scroll position on scroll
            sidebar.addEventListener('scroll', function() {
                localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
