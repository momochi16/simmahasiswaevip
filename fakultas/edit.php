<?php
// fakultas/edit.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$title = 'Edit Fakultas - SIM Mahasiswa UMB';
$active_menu = 'fakultas';
$base_url = '../';

// Fetch fakultas data
$stmt = $pdo->prepare("SELECT * FROM fakultas WHERE id = ?");
$stmt->execute([$id]);
$f = $stmt->fetch();

if (!$f) {
    header("Location: index.php");
    exit();
}

// Fetch universitas for dropdown
$universitas = $pdo->query("SELECT id, nama_universitas FROM universitas ORDER BY nama_universitas ASC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $universitas_id = $_POST['universitas_id'] ?? '';
    $kode_fakultas = $_POST['kode_fakultas'] ?? '';
    $nama_fakultas = $_POST['nama_fakultas'] ?? '';

    if (empty($universitas_id) || empty($kode_fakultas) || empty($nama_fakultas)) {
        $error = 'Semua field wajib diisi!';
    } else {
        // Check duplicate excluding self
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fakultas WHERE universitas_id = ? AND kode_fakultas = ? AND id != ?");
        $stmt->execute([$universitas_id, $kode_fakultas, $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Kode fakultas sudah terdaftar di universitas ini!';
        } else {
            $sql = "UPDATE fakultas SET universitas_id = ?, kode_fakultas = ?, nama_fakultas = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$universitas_id, $kode_fakultas, $nama_fakultas, $id])) {
                logActivity($pdo, $_SESSION['user_id'], 'UPDATE', 'fakultas', $id, "Mengubah fakultas: $nama_fakultas");
                $_SESSION['success'] = 'Data fakultas berhasil diperbarui!';
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
    <h4 class="fw-bold text-hijau-tua mb-0">Edit Fakultas</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Fakultas</a></li>
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
    <div class="col-lg-6">
        <div class="card card-custom border-0 shadow-sm bg-white">
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="universitas_id" class="form-label small fw-bold text-muted">Universitas</label>
                        <select name="universitas_id" id="universitas_id" class="form-select" required>
                            <option value="">Pilih Universitas</option>
                            <?php foreach ($universitas as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= $f['universitas_id'] == $u['id'] ? 'selected' : '' ?>><?= h($u['nama_universitas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kode_fakultas" class="form-label small fw-bold text-muted">Kode Fakultas</label>
                        <input type="text" name="kode_fakultas" id="kode_fakultas" class="form-control" value="<?= h($f['kode_fakultas']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="nama_fakultas" class="form-label small fw-bold text-muted">Nama Fakultas</label>
                        <input type="text" name="nama_fakultas" id="nama_fakultas" class="form-control" value="<?= h($f['nama_fakultas']) ?>" required>
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

<?php require_once '../includes/footer.php'; ?>
