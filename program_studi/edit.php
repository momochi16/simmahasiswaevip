<?php
// program_studi/edit.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$title = 'Edit Program Studi - SIM Mahasiswa UMB';
$active_menu = 'program_studi';
$base_url = '../';

// Fetch prodi data
$stmt = $pdo->prepare("SELECT p.*, f.universitas_id FROM program_studi p JOIN fakultas f ON p.fakultas_id = f.id WHERE p.id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    header("Location: index.php");
    exit();
}

// Fetch data for dropdowns
$universitas = $pdo->query("SELECT id, nama_universitas FROM universitas ORDER BY nama_universitas ASC")->fetchAll();
$fakultas = $pdo->query("SELECT id, nama_fakultas, universitas_id FROM fakultas ORDER BY nama_fakultas ASC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fakultas_id = $_POST['fakultas_id'] ?? '';
    $kode = $_POST['kode_prodi'] ?? '';
    $nama = $_POST['nama_prodi'] ?? '';
    $jenjang = $_POST['jenjang'] ?? '';
    $status = $_POST['status_prodi'] ?? 'Aktif';

    if (empty($fakultas_id) || empty($kode) || empty($nama) || empty($jenjang)) {
        $error = 'Semua field wajib diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM program_studi WHERE fakultas_id = ? AND kode_prodi = ? AND id != ?");
        $stmt->execute([$fakultas_id, $kode, $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Kode prodi sudah terdaftar di fakultas ini!';
        } else {
            $sql = "UPDATE program_studi SET fakultas_id = ?, kode_prodi = ?, nama_prodi = ?, jenjang = ?, status_prodi = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$fakultas_id, $kode, $nama, $jenjang, $status, $id])) {
                logActivity($pdo, $_SESSION['user_id'], 'UPDATE', 'program_studi', $id, "Mengubah prodi: $nama");
                $_SESSION['success'] = 'Data program studi berhasil diperbarui!';
                header("Location: index.php");
                exit();
            } else {
                $error = 'Gagal memperbarui data!';
            }
        }
    }
}

require_once '../includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold text-hijau-tua mb-0">Edit Program Studi</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Program Studi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger d-flex align-items-center small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div><?= $error ?></div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom border-0 shadow-sm bg-white">
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="universitas_id" class="form-label small fw-bold text-muted">Universitas</label>
                            <select id="universitas_id" class="form-select" required>
                                <option value="">Pilih Universitas</option>
                                <?php foreach ($universitas as $u): ?>
                                    <option value="<?= $u['id'] ?>" <?= $p['universitas_id'] == $u['id'] ? 'selected' : '' ?>><?= h($u['nama_universitas']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fakultas_id" class="form-label small fw-bold text-muted">Fakultas</label>
                            <select name="fakultas_id" id="fakultas_id" class="form-select" required>
                                <option value="">Pilih Fakultas</option>
                                <?php foreach ($fakultas as $f): ?>
                                    <?php if ($f['universitas_id'] == $p['universitas_id']): ?>
                                        <option value="<?= $f['id'] ?>" <?= $p['fakultas_id'] == $f['id'] ? 'selected' : '' ?>><?= h($f['nama_fakultas']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kode_prodi" class="form-label small fw-bold text-muted">Kode Prodi</label>
                            <input type="text" name="kode_prodi" id="kode_prodi" class="form-control" value="<?= h($p['kode_prodi']) ?>" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nama_prodi" class="form-label small fw-bold text-muted">Nama Program Studi</label>
                            <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" value="<?= h($p['nama_prodi']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="jenjang" class="form-label small fw-bold text-muted">Jenjang</label>
                            <select name="jenjang" id="jenjang" class="form-select" required>
                                <option value="">Pilih Jenjang</option>
                                <option value="D3" <?= $p['jenjang'] === 'D3' ? 'selected' : '' ?>>D3</option>
                                <option value="S1" <?= $p['jenjang'] === 'S1' ? 'selected' : '' ?>>S1</option>
                                <option value="S2" <?= $p['jenjang'] === 'S2' ? 'selected' : '' ?>>S2</option>
                                <option value="S3" <?= $p['jenjang'] === 'S3' ? 'selected' : '' ?>>S3</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="status_prodi" class="form-label small fw-bold text-muted">Status</label>
                            <select name="status_prodi" id="status_prodi" class="form-select">
                                <option value="Aktif" <?= $p['status_prodi'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Tidak Aktif" <?= $p['status_prodi'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-hijau px-4">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const fakultasData = <?= json_encode($fakultas) ?>;
const uniSelect = document.getElementById('universitas_id');
const fakSelect = document.getElementById('fakultas_id');

uniSelect.addEventListener('change', function() {
    const uniId = this.value;
    fakSelect.innerHTML = '<option value="">Pilih Fakultas</option>';
    
    if (uniId) {
        const filtered = fakultasData.filter(f => f.universitas_id == uniId);
        filtered.forEach(f => {
            const opt = document.createElement('option');
            opt.value = f.id;
            opt.textContent = f.nama_fakultas;
            fakSelect.appendChild(opt);
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
