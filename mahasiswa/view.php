<?php
// mahasiswa/view.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$title = 'Detail Mahasiswa - SIM Mahasiswa UMB';
$active_menu = 'mahasiswa';
$base_url = '../';

$query = "SELECT m.*, p.nama_prodi, p.jenjang, f.nama_fakultas, u.nama_universitas 
          FROM mahasiswa m 
          JOIN program_studi p ON m.prodi_id = p.id 
          JOIN fakultas f ON p.fakultas_id = f.id 
          JOIN universitas u ON f.universitas_id = u.id 
          WHERE m.id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    header("Location: index.php");
    exit();
}

require_once '../includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold text-hijau-tua mb-0">Detail Mahasiswa</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Mahasiswa</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= h($m['npm']) ?></li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card card-custom border-0 shadow-sm bg-white mb-4">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-hijau-mint text-success d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    <?= strtoupper(substr($m['nama_mahasiswa'], 0, 1)) ?>
                </div>
                <h5 class="fw-bold mb-1"><?= h($m['nama_mahasiswa']) ?></h5>
                <p class="text-success fw-bold mb-3"><?= h($m['npm']) ?></p>
                <div class="d-grid">
                    <a href="edit.php?id=<?= $m['id'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square me-2"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card card-custom border-0 shadow-sm bg-white">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Kontak</h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Email</small>
                    <span class="small"><?= h($m['email'] ?: '-') ?></span>
                </div>
                <div>
                    <small class="text-muted d-block">Nomor HP</small>
                    <span class="small"><?= h($m['nomor_hp'] ?: '-') ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card card-custom border-0 shadow-sm bg-white mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-success border-bottom pb-2">Informasi Akademik</h6>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Universitas</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['nama_universitas']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Fakultas</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['nama_fakultas']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Program Studi</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['nama_prodi']) ?> (<?= h($m['jenjang']) ?>)</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted small">Angkatan</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['angkatan']) ?></div>
                </div>
            </div>
        </div>
        
        <div class="card card-custom border-0 shadow-sm bg-white">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-success border-bottom pb-2">Biodata Pribadi</h6>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Jenis Kelamin</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['jenis_kelamin']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Tempat, Tanggal Lahir</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['tempat_lahir'] ?: '-') ?>, <?= $m['tanggal_lahir'] ? date('d F Y', strtotime($m['tanggal_lahir'])) : '-' ?></div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted small">Alamat</div>
                    <div class="col-sm-8 fw-medium"><?= h($m['alamat'] ?: '-') ?></div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-light border px-4">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
