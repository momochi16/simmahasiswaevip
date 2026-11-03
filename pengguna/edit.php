<?php
// pengguna/edit.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
}

$title = 'Edit Pengguna - SIM Mahasiswa UMB';
$active_menu = 'pengguna';
$base_url = '../';

$stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    header("Location: index.php");
}

$roles = $pdo->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_lengkap'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role_id = $_POST['role_id'] ?? '';
    $status = $_POST['status'] ?? 'Aktif';

    if (empty($nama) || empty($username) || empty($email) || empty($role_id)) {
        $error = 'Semua field (kecuali password) wajib diisi!';
    } else {
        // Check duplicate
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username atau Email sudah digunakan oleh akun lain!';
        } else {
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE pengguna SET nama_lengkap = ?, username = ?, email = ?, password_hash = ?, role_id = ?, status = ? WHERE id = ?";
                $params = [$nama, $username, $email, $password_hash, $role_id, $status, $id];
            } else {
                $sql = "UPDATE pengguna SET nama_lengkap = ?, username = ?, email = ?, role_id = ?, status = ? WHERE id = ?";
                $params = [$nama, $username, $email, $role_id, $status, $id];
            }
            
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                logActivity($pdo, $_SESSION['user_id'], 'UPDATE', 'pengguna', $id, "Mengubah data pengguna: $username");
                $_SESSION['success'] = 'Data pengguna berhasil diperbarui!';
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
    <h4 class="fw-bold text-hijau-tua mb-0">Edit Pengguna</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Pengguna</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= h($p['username']) ?></li>
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
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= h($p['nama_lengkap']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= h($p['username']) ?>" <?= $p['username'] === 'admin' ? 'readonly' : '' ?> required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= h($p['email']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                            <small class="text-muted" style="font-size: 0.7rem;">Biarkan kosong jika tidak ingin mengubah password.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Role</label>
                            <select name="role_id" class="form-select" required <?= $p['username'] === 'admin' ? 'disabled' : '' ?>>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= $p['role_id'] == $r['id'] ? 'selected' : '' ?>><?= h($r['nama_role']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($p['username'] === 'admin'): ?>
                                <input type="hidden" name="role_id" value="<?= $p['role_id'] ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Status Akun</label>
                        <select name="status" class="form-select" <?= $p['username'] === 'admin' ? 'disabled' : '' ?>>
                            <option value="Aktif" <?= $p['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Tidak Aktif" <?= $p['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                        <?php if ($p['username'] === 'admin'): ?>
                            <input type="hidden" name="status" value="<?= $p['status'] ?>">
                        <?php endif; ?>
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
