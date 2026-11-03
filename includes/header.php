<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIM Mahasiswa UMB' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --hijau-tua: #0d3b2e;
            --hijau-mint: #d1e7dd;
            --hijau-utama: #198754;
            --bg-konten: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-konten);
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        #sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--hijau-tua);
            color: white;
            position: fixed;
            transition: all 0.3s;
            z-index: 1000;
        }
        #sidebar.collapsed {
            margin-left: -260px;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        #sidebar .menu-label {
            padding: 20px 20px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        #sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.15);
            border-left: 4px solid #fff;
        }
        #sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Content Area */
        #content {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.3s;
        }
        #content.expanded {
            margin-left: 0;
            width: 100%;
        }

        .btn-hijau {
            background-color: var(--hijau-utama);
            color: white;
            border: none;
            transition: all 0.2s;
        }
        .btn-hijau:hover {
            background-color: #157347;
            color: white;
        }
        .navbar-admin {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Cards */
        .card-custom {
            border: 1px solid #eee;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .card-summary {
            padding: 25px;
        }
        .icon-box {
            width: 50px;
            height: 50px;
            background-color: var(--hijau-mint);
            color: #0f5132;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }
        .summary-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 5px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar { margin-left: -260px; }
            #sidebar.active { margin-left: 0; }
            #content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center">
                <i class="bi bi-list me-3 d-lg-none" id="sidebarClose"></i>
                <span>SIM Mahasiswa</span>
            </div>
            <div class="sidebar-content">
                <div class="menu-label">Menu Utama</div>
                <a href="<?= $base_url ?? '' ?>dashboard.php" class="nav-link <?= ($active_menu ?? '') == 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>

                <div class="menu-label">Data Akademik</div>
                <a href="<?= $base_url ?? '' ?>mahasiswa/index.php" class="nav-link <?= ($active_menu ?? '') == 'mahasiswa' ? 'active' : '' ?>">
                    <i class="bi bi-mortarboard-fill"></i> Data Mahasiswa
                </a>
                <a href="<?= $base_url ?? '' ?>universitas/index.php" class="nav-link <?= ($active_menu ?? '') == 'universitas' ? 'active' : '' ?>">
                    <i class="bi bi-building-fill"></i> Universitas
                </a>
                <a href="<?= $base_url ?? '' ?>fakultas/index.php" class="nav-link <?= ($active_menu ?? '') == 'fakultas' ? 'active' : '' ?>">
                    <i class="bi bi-bank"></i> Fakultas
                </a>
                <a href="<?= $base_url ?? '' ?>program_studi/index.php" class="nav-link <?= ($active_menu ?? '') == 'program_studi' ? 'active' : '' ?>">
                    <i class="bi bi-book-half"></i> Program Studi
                </a>
                <a href="<?= $base_url ?? '' ?>pengguna/index.php" class="nav-link <?= ($active_menu ?? '') == 'pengguna' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Pengguna
                </a>
                <a href="<?= $base_url ?? '' ?>role/index.php" class="nav-link <?= ($active_menu ?? '') == 'role' ? 'active' : '' ?>">
                    <i class="bi bi-shield-lock-fill"></i> Role
                </a>

                <div class="menu-label">Laporan</div>
                <a href="<?= $base_url ?? '' ?>laporan/rekap_mahasiswa.php" class="nav-link <?= ($active_menu ?? '') == 'rekap' ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i> Rekap Mahasiswa
                </a>

                <div class="menu-label">Sistem</div>
                <a href="<?= $base_url ?? '' ?>audit_log/index.php" class="nav-link <?= ($active_menu ?? '') == 'audit' ? 'active' : '' ?>">
                    <i class="bi bi-journal-text"></i> Audit Log
                </a>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-admin d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button type="button" id="sidebarCollapse" class="btn btn-light border-0 me-3">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <img src="<?= $base_url ?? '' ?>assets/logo.png" alt="Logo" width="35" class="me-3">
                    <div>
                        <h6 class="mb-0 fw-bold">SIM Mahasiswa</h6>
                        <small class="text-muted">Universitas Muhammadiyah Bengkulu</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="text-end me-3 d-none d-md-block">
                        <h6 class="mb-0 fw-bold small"><?= h($_SESSION['nama_lengkap']) ?></h6>
                        <small class="text-muted small"><?= h($_SESSION['role_name']) ?></small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-hijau-mint text-success d-flex align-items-center justify-content-center fw-bold me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                            <?= strtoupper(substr($_SESSION['nama_lengkap'], 0, 1)) ?>
                        </div>
                        <a href="<?= $base_url ?? '' ?>auth/logout.php" class="text-danger text-decoration-none small fw-bold ms-2">Keluar</a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
