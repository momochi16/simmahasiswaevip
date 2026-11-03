<?php
// pengguna/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
}

$title = 'Manajemen Pengguna - SIM Mahasiswa UMB';
$active_menu = 'pengguna';
$base_url = '../';

$query = "SELECT p.*, r.nama_role 
          FROM pengguna p 
          JOIN roles r ON p.role_id = r.id 
          ORDER BY p.id DESC";
$pengguna = $pdo->query($query)->fetchAll();

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-hijau-tua mb-0">Manajemen Pengguna</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
            </ol>
        </nav>
    </div>
    <a href="add.php" class="btn btn-hijau d-flex align-items-center">
        <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show small" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card card-custom border-0 shadow-sm bg-white">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username / Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengguna as $index => $p): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light text-success d-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <?= strtoupper(substr($p['nama_lengkap'], 0, 1)) ?>
                                    </div>
                                    <div class="fw-medium"><?= h($p['nama_lengkap']) ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold"><?= h($p['username']) ?></div>
                                <div class="small text-muted"><?= h($p['email']) ?></div>
                            </td>
                            <td><span class="badge bg-info-subtle text-info"><?= h($p['nama_role']) ?></span></td>
                            <td>
                                <span class="badge <?= $p['status'] === 'Aktif' ? 'bg-success' : 'bg-danger' ?> px-2">
                                    <?= h($p['status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <?php if ($p['username'] !== 'admin'): ?>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus" 
                                                onclick="confirmDelete(<?= $p['id'] ?>, '<?= h($p['nama_lengkap']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-0">
                Apakah Anda yakin ingin menghapus akun <span id="deleteItemName" class="fw-bold"></span>?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus Akun</a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('confirmDeleteBtn').href = 'delete.php?id=' + id;
    var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    myModal.show();
}
</script>

<?php require_once '../includes/footer.php'; ?>
