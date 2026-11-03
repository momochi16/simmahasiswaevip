<?php
// program_studi/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$title = 'Data Program Studi - SIM Mahasiswa UMB';
$active_menu = 'program_studi';
$base_url = '../';

$search = $_GET['search'] ?? '';
$fakultas_filter = $_GET['fakultas_id'] ?? '';
$where = [];
$params = [];

if ($search) {
    $where[] = "(p.nama_prodi LIKE ? OR p.kode_prodi LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($fakultas_filter) {
    $where[] = "p.fakultas_id = ?";
    $params[] = $fakultas_filter;
}

$where_sql = count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "";

$query = "SELECT p.*, f.nama_fakultas, u.nama_universitas 
          FROM program_studi p 
          JOIN fakultas f ON p.fakultas_id = f.id 
          JOIN universitas u ON f.universitas_id = u.id 
          $where_sql 
          ORDER BY p.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$prodi = $stmt->fetchAll();

$all_fakultas = $pdo->query("SELECT id, nama_fakultas FROM fakultas ORDER BY nama_fakultas ASC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-hijau-tua mb-0">Data Program Studi</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
            </ol>
        </nav>
    </div>
    <a href="add.php" class="btn btn-hijau d-flex align-items-center">
        <i class="bi bi-plus-lg me-2"></i> Tambah Program Studi
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
        <form action="" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama prodi..." value="<?= h($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="fakultas_id" class="form-select">
                    <option value="">Semua Fakultas</option>
                    <?php foreach ($all_fakultas as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= $fakultas_filter == $f['id'] ? 'selected' : '' ?>><?= h($f['nama_fakultas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" type="submit">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Fakultas</th>
                        <th>Kode Prodi</th>
                        <th>Nama Program Studi</th>
                        <th>Jenjang</th>
                        <th>Status</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($prodi) > 0): ?>
                        <?php foreach ($prodi as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><small class="text-muted d-block"><?= h($p['nama_universitas']) ?></small><?= h($p['nama_fakultas']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= h($p['kode_prodi']) ?></span></td>
                                <td class="fw-medium"><?= h($p['nama_prodi']) ?></td>
                                <td><?= h($p['jenjang']) ?></td>
                                <td>
                                    <span class="badge <?= $p['status_prodi'] === 'Aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> px-2 py-1">
                                        <?= h($p['status_prodi']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus" 
                                                onclick="confirmDelete(<?= $p['id'] ?>, '<?= h($p['nama_prodi']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted small">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
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
                Apakah Anda yakin ingin menghapus program studi <span id="deleteItemName" class="fw-bold"></span>?
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
