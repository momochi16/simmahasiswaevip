<?php
// mahasiswa/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$title = 'Data Mahasiswa - SIM Mahasiswa UMB';
$active_menu = 'mahasiswa';
$base_url = '../';

$search = $_GET['search'] ?? '';
$fakultas_filter = $_GET['fakultas_id'] ?? '';
$prodi_filter = $_GET['prodi_id'] ?? '';
$angkatan_filter = $_GET['angkatan'] ?? '';

$where = [];
$params = [];

if ($search) {
    $where[] = "(m.nama_mahasiswa LIKE ? OR m.npm LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($fakultas_filter) {
    $where[] = "f.id = ?";
    $params[] = $fakultas_filter;
}

if ($prodi_filter) {
    $where[] = "m.prodi_id = ?";
    $params[] = $prodi_filter;
}

if ($angkatan_filter) {
    $where[] = "m.angkatan = ?";
    $params[] = $angkatan_filter;
}

$where_sql = count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "";

$query = "SELECT m.*, p.nama_prodi, f.nama_fakultas 
          FROM mahasiswa m 
          JOIN program_studi p ON m.prodi_id = p.id 
          JOIN fakultas f ON p.fakultas_id = f.id 
          $where_sql 
          ORDER BY m.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$mahasiswa = $stmt->fetchAll();

$all_fakultas = $pdo->query("SELECT id, nama_fakultas FROM fakultas ORDER BY nama_fakultas ASC")->fetchAll();
$all_prodi = $pdo->query("SELECT id, nama_prodi, fakultas_id FROM program_studi ORDER BY nama_prodi ASC")->fetchAll();
$all_angkatan = $pdo->query("SELECT DISTINCT angkatan FROM mahasiswa ORDER BY angkatan DESC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-hijau-tua mb-0">Data Mahasiswa</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
            </ol>
        </nav>
    </div>
    <a href="add.php" class="btn btn-hijau d-flex align-items-center">
        <i class="bi bi-plus-lg me-2"></i> Tambah Mahasiswa
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
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari NPM atau nama..." value="<?= h($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="fakultas_id" id="fakultas_filter" class="form-select">
                    <option value="">Semua Fakultas</option>
                    <?php foreach ($all_fakultas as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= $fakultas_filter == $f['id'] ? 'selected' : '' ?>><?= h($f['nama_fakultas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="prodi_id" id="prodi_filter" class="form-select">
                    <option value="">Semua Prodi</option>
                    <?php foreach ($all_prodi as $p): ?>
                        <option value="<?= $p['id'] ?>" data-fakultas="<?= $p['fakultas_id'] ?>" <?= $prodi_filter == $p['id'] ? 'selected' : '' ?>><?= h($p['nama_prodi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="angkatan" class="form-select">
                    <option value="">Angkatan</option>
                    <?php foreach ($all_angkatan as $a): ?>
                        <option value="<?= $a['angkatan'] ?>" <?= $angkatan_filter == $a['angkatan'] ? 'selected' : '' ?>><?= h($a['angkatan']) ?></option>
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
                        <th>NPM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($mahasiswa) > 0): ?>
                        <?php foreach ($mahasiswa as $index => $m): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><span class="fw-bold text-success"><?= h($m['npm']) ?></span></td>
                                <td class="fw-medium"><?= h($m['nama_mahasiswa']) ?></td>
                                <td>
                                    <small class="text-muted d-block"><?= h($m['nama_fakultas']) ?></small>
                                    <?= h($m['nama_prodi']) ?>
                                </td>
                                <td><?= h($m['angkatan']) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="view.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-info text-white" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus" 
                                                onclick="confirmDelete(<?= $m['id'] ?>, '<?= h($m['nama_mahasiswa']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small">Data tidak ditemukan</td>
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
                Apakah Anda yakin ingin menghapus data mahasiswa <span id="deleteItemName" class="fw-bold"></span>?
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

// Simple dynamic prodi filter
const fakFilter = document.getElementById('fakultas_filter');
const prodiFilter = document.getElementById('prodi_filter');
const allProdiOpts = Array.from(prodiFilter.options);

fakFilter.addEventListener('change', function() {
    const fakId = this.value;
    prodiFilter.innerHTML = '';
    allProdiOpts.forEach(opt => {
        if (!fakId || opt.value === "" || opt.dataset.fakultas == fakId) {
            prodiFilter.appendChild(opt.cloneNode(true));
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
