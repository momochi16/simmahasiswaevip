<?php
// role/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}

$title = 'Manajemen Role - SIM Mahasiswa UMB';
$active_menu = 'role';
$base_url = '../';

$roles = $pdo->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-hijau-tua mb-0">Manajemen Role</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Role</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card card-custom border-0 shadow-sm bg-white">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Role</th>
                        <th>Keterangan</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $index => $r): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><span class="badge bg-success-subtle text-success px-3 py-2"><?= h($r['nama_role']) ?></span></td>
                            <td class="small text-muted"><?= h($r['keterangan']) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border" title="Akses Dibatasi" disabled>
                                    <i class="bi bi-lock-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info mt-3 mb-0 small" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            Data role adalah konfigurasi sistem utama dan tidak dapat diubah secara bebas melalui interface ini untuk menjaga stabilitas sistem.
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
