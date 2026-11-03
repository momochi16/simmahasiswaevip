<?php
// dashboard.php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if logged in
checkLogin();

$title = 'Dashboard - SIM Mahasiswa UMB';
$active_menu = 'dashboard';

// Fetch summary data
$total_mahasiswa = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
$total_prodi = $pdo->query("SELECT COUNT(*) FROM program_studi")->fetchColumn();
$total_fakultas = $pdo->query("SELECT COUNT(*) FROM fakultas")->fetchColumn();

require_once 'includes/header.php';
?>

<div class="mb-4">
    <small class="text-success fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Dashboard</small>
    <h3 class="fw-bold text-hijau-tua mt-1">Selamat datang, <?= h($_SESSION['nama_lengkap']) ?></h3>
    <p class="text-muted small"><?= h($_SESSION['role_name']) ?> · Seluruh Program Studi</p>
</div>

<div class="row g-4 mb-4">
    <!-- Card 1: Total Mahasiswa -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom card-summary bg-white border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="summary-label">Total Mahasiswa</div>
                    <div class="summary-value"><?= number_format($total_mahasiswa) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Program Studi -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom card-summary bg-white border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <div class="summary-label">Program Studi</div>
                    <div class="summary-value"><?= number_format($total_prodi) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card 3: Fakultas -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom card-summary bg-white border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-bank"></i>
                </div>
                <div>
                    <div class="summary-label">Fakultas</div>
                    <div class="summary-value"><?= number_format($total_fakultas) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Hak Akses (Row 2 as per reference) -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom card-summary bg-white border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <div class="summary-label">Hak Akses</div>
                    <div class="summary-value" style="font-size: 1.4rem;"><?= h($_SESSION['role_name']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Sistem -->
<div class="card card-custom border-0 shadow-sm bg-white">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Informasi Sistem</h5>
        
        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="text-muted small mb-1">Universitas</div>
                <div class="fw-bold">Universitas Muhammadiyah Bengkulu</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Role</div>
                <div class="fw-bold"><?= h($_SESSION['role_name']) ?></div>
            </div>
        </div>
        
        <hr class="my-3 opacity-10">
        
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="text-muted small mb-1">Username</div>
                <div class="fw-bold"><?= h($_SESSION['username']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status</div>
                <div class="fw-bold text-success">Aktif</div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
