<?php
// mahasiswa/edit.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$title = 'Edit Mahasiswa - SIM Mahasiswa UMB';
$active_menu = 'mahasiswa';
$base_url = '../';

// Fetch mahasiswa data
$stmt = $pdo->prepare("SELECT m.*, f.id as fakultas_id FROM mahasiswa m JOIN program_studi p ON m.prodi_id = p.id JOIN fakultas f ON p.fakultas_id = f.id WHERE m.id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    header("Location: index.php");
    exit();
}

// Fetch data for dropdowns
$fakultas = $pdo->query("SELECT id, nama_fakultas FROM fakultas ORDER BY nama_fakultas ASC")->fetchAll();
$prodi = $pdo->query("SELECT id, nama_prodi, fakultas_id FROM program_studi ORDER BY nama_prodi ASC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npm = $_POST['npm'] ?? '';
    $nama = $_POST['nama_mahasiswa'] ?? '';
    $jk = $_POST['jenis_kelamin'] ?? '';
    $tempat = $_POST['tempat_lahir'] ?? '';
    $tanggal = $_POST['tanggal_lahir'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $email = $_POST['email'] ?? '';
    $hp = $_POST['nomor_hp'] ?? '';
    $prodi_id = $_POST['prodi_id'] ?? '';
    $angkatan = $_POST['angkatan'] ?? '';

    if (empty($npm) || empty($nama) || empty($jk) || empty($prodi_id) || empty($angkatan)) {
        $error = 'Field NPM, Nama, Jenis Kelamin, Prodi, dan Angkatan wajib diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE npm = ? AND id != ?");
        $stmt->execute([$npm, $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'NPM sudah terdaftar!';
        } else {
            $sql = "UPDATE mahasiswa SET npm = ?, nama_mahasiswa = ?, jenis_kelamin = ?, tempat_lahir = ?, tanggal_lahir = ?, alamat = ?, email = ?, nomor_hp = ?, prodi_id = ?, angkatan = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$npm, $nama, $jk, $tempat, $tanggal, $alamat, $email, $hp, $prodi_id, $angkatan, $id])) {
                logActivity($pdo, $_SESSION['user_id'], 'UPDATE', 'mahasiswa', $id, "Mengubah mahasiswa: $nama ($npm)");
                $_SESSION['success'] = 'Data mahasiswa berhasil diperbarui!';
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
    <h4 class="fw-bold text-hijau-tua mb-0">Edit Mahasiswa</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Mahasiswa</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= h($m['npm']) ?></li>
        </ol>
    </nav>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger d-flex align-items-center small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div><?= $error ?></div>
    </div>
<?php endif; ?>

<div class="card card-custom border-0 shadow-sm bg-white">
    <div class="card-body p-4">
        <form action="" method="POST">
            <h6 class="fw-bold mb-3 text-success border-bottom pb-2">Informasi Pribadi</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">NPM</label>
                    <input type="text" name="npm" class="form-control" value="<?= h($m['npm']) ?>" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                    <input type="text" name="nama_mahasiswa" class="form-control" value="<?= h($m['nama_mahasiswa']) ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="Laki-laki" <?= $m['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $m['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= h($m['tempat_lahir']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= h($m['tanggal_lahir']) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2"><?= h($m['alamat']) ?></textarea>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= h($m['email']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Nomor HP/WhatsApp</label>
                    <input type="text" name="nomor_hp" class="form-control" value="<?= h($m['nomor_hp']) ?>">
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-success border-bottom pb-2">Data Akademik</h6>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Fakultas</label>
                    <select id="fakultas_id" class="form-select" required>
                        <option value="">Pilih Fakultas</option>
                        <?php foreach ($fakultas as $f): ?>
                            <option value="<?= $f['id'] ?>" <?= $m['fakultas_id'] == $f['id'] ? 'selected' : '' ?>><?= h($f['nama_fakultas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">Program Studi</label>
                    <select name="prodi_id" id="prodi_id" class="form-select" required>
                        <option value="">Pilih Program Studi</option>
                        <?php foreach ($prodi as $p): ?>
                            <?php if ($p['fakultas_id'] == $m['fakultas_id']): ?>
                                <option value="<?= $p['id'] ?>" <?= $m['prodi_id'] == $p['id'] ? 'selected' : '' ?>><?= h($p['nama_prodi']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Angkatan</label>
                    <input type="number" name="angkatan" class="form-control" value="<?= h($m['angkatan']) ?>" required>
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

<script>
const prodiData = <?= json_encode($prodi) ?>;
const fakSelect = document.getElementById('fakultas_id');
const prodiSelect = document.getElementById('prodi_id');

fakSelect.addEventListener('change', function() {
    const fakId = this.value;
    prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
    
    if (fakId) {
        const filtered = prodiData.filter(p => p.fakultas_id == fakId);
        filtered.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.nama_prodi;
            prodiSelect.appendChild(opt);
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
