<?php
// laporan/rekap_mahasiswa.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$title = 'Rekap Mahasiswa - SIM Mahasiswa UMB';
$active_menu = 'rekap';
$base_url = '../';

// Summary Stats
$stats = [];
$stats['total'] = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();

// Rekap per Prodi
$rekap_prodi = $pdo->query("SELECT p.nama_prodi, f.nama_fakultas, COUNT(m.id) as total 
                            FROM program_studi p 
                            JOIN fakultas f ON p.fakultas_id = f.id 
                            LEFT JOIN mahasiswa m ON m.prodi_id = p.id 
                            GROUP BY p.id 
                            ORDER BY f.nama_fakultas ASC, p.nama_prodi ASC")->fetchAll();

// Rekap per Angkatan
$rekap_angkatan = $pdo->query("SELECT angkatan, COUNT(*) as total 
                               FROM mahasiswa 
                               GROUP BY angkatan 
                               ORDER BY angkatan DESC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-hijau-tua mb-0">Rekapitulasi Mahasiswa</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Rekap</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-light border d-flex align-items-center">
            <i class="bi bi-printer me-2"></i> Cetak Laporan
        </button>
        <a href="export_excel.php" class="btn btn-success d-flex align-items-center">
            <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card card-custom border-0 shadow-sm bg-white">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-success border-bottom pb-2">Ringkasan Statistik</h6>
                <div class="row text-center">
                    <div class="col-md-4 border-end">
                        <div class="text-muted small">Total Mahasiswa Terdaftar</div>
                        <h2 class="fw-bold text-success mb-0"><?= number_format($stats['total']) ?></h2>
                    </div>
                    <div class="col-md-4 border-end">
                        <div class="text-muted small">Program Studi Aktif</div>
                        <h2 class="fw-bold text-primary mb-0"><?= count($rekap_prodi) ?></h2>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Tahun Angkatan Terdata</div>
                        <h2 class="fw-bold text-info mb-0"><?= count($rekap_angkatan) ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom border-0 shadow-sm bg-white h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-success border-bottom pb-2">Rekap per Program Studi</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th class="text-center">Jumlah Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekap_prodi as $r): ?>
                                <tr>
                                    <td><small class="text-muted"><?= h($r['nama_fakultas']) ?></small></td>
                                    <td class="fw-medium"><?= h($r['nama_prodi']) ?></td>
                                    <td class="text-center fw-bold text-success"><?= number_format($r['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-custom border-0 shadow-sm bg-white h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-success border-bottom pb-2">Rekap per Angkatan</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tahun Angkatan</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekap_angkatan as $a): ?>
                                <tr>
                                    <td class="fw-bold">Angkatan <?= h($a['angkatan']) ?></td>
                                    <td class="text-center fw-medium"><?= number_format($a['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    #sidebar, .navbar-admin, .btn, .breadcrumb {
        display: none !important;
    }
    #content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #eee !important;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>
