<?php
// fakultas/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$title = 'Data Fakultas - SIM Mahasiswa UMB';
$active_menu = 'fakultas';
$base_url = '../';

// Search and Filter
$search = $_GET['search'] ?? '';
$where = '';
$params = [];

if ($search) {
    $where = " WHERE f.nama_fakultas LIKE ? OR f.kode_fakultas LIKE ? ";
    $params = ["%$search%", "%$search%"];
}

$query = "SELECT f.*, u.nama_universitas 
          FROM fakultas f 
          JOIN universitas u ON f.universitas_id = u.id 
          $where 
          ORDER BY f.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$fakultas = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-hijau-tua mb-0">Data Fakultas</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fakultas</li>
            </ol>
        </nav>
    </div>
    <a href="add.php" class="btn btn-hijau d-flex align-items-center">
        <i class="bi bi-plus-lg me-2"></i> Tambah Fakultas
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
        <div class="row mb-4">
            <div class="col-md-4">
                <form action="" method="GET" class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama fakultas..." value="<?= h($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Universitas</th>
                        <th>Kode Fakultas</th>
                        <th>Nama Fakultas</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($fakultas) > 0): ?>
                        <?php foreach ($fakultas as $index => $f): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= h($f['nama_universitas']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= h($f['kode_fakultas']) ?></span></td>
                                <td class="fw-medium"><?= h($f['nama_fakultas']) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus" 
                                                onclick="confirmDelete(<?= $f['id'] ?>, '<?= h($f['nama_fakultas']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted small">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-0">
                Apakah Anda yakin ingin menghapus fakultas <span id="deleteItemName" class="fw-bold"></span>? Data yang dihapus tidak dapat dikembalikan.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus Data</a>
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
