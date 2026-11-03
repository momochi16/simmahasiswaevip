<?php
// universitas/edit.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$title = 'Edit Universitas - SIM Mahasiswa UMB';
$active_menu = 'universitas';
$base_url = '../';

$stmt = $pdo->prepare("SELECT * FROM universitas WHERE id = ?");
$stmt->execute([$id]);
$u = $stmt->fetch();

if (!$u) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode_universitas'] ?? '';
    $nama = $_POST['nama_universitas'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $email = $_POST['email'] ?? '';
    $telp = $_POST['nomor_telepon'] ?? '';

    if (empty($kode) || empty($nama)) {
        $error = 'Kode dan Nama Universitas wajib diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM universitas WHERE kode_universitas = ? AND id != ?");
        $stmt->execute([$kode, $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Kode universitas sudah digunakan!';
        } else {
            $sql = "UPDATE universitas SET kode_universitas = ?, nama_universitas = ?, alamat = ?, email = ?, nomor_telepon = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$kode, $nama, $alamat, $email, $telp, $id])) {
                logActivity($pdo, $_SESSION['user_id'], 'UPDATE', 'universitas', $id, "Mengubah universitas: $nama");
                $_SESSION['success'] = 'Data universitas berhasil diperbarui!';
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
    <h4 class="fw-bold text-hijau-tua mb-0">Edit Universitas</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Universitas</a></li>
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
                        <div class="col-md-4 mb-3">
                            <label for="kode_universitas" class="form-label small fw-bold text-muted">Kode Universitas</label>
                            <input type="text" name="kode_universitas" id="kode_universitas" class="form-control" value="<?= h($u['kode_universitas']) ?>" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nama_universitas" class="form-label small fw-bold text-muted">Nama Universitas</label>
                            <input type="text" name="nama_universitas" id="nama_universitas" class="form-control" value="<?= h($u['nama_universitas']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label small fw-bold text-muted">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3"><?= h($u['alamat']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= h($u['email']) ?>">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="nomor_telepon" class="form-label small fw-bold text-muted">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" value="<?= h($u['nomor_telepon']) ?>">
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

<?php require_once '../includes/footer.php'; ?>
