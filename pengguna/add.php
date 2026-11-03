<?php
// pengguna/add.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
}

$title = 'Tambah Pengguna - SIM Mahasiswa UMB';
$active_menu = 'pengguna';
$base_url = '../';

$roles = $pdo->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_lengkap'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role_id = $_POST['role_id'] ?? '';
    $status = $_POST['status'] ?? 'Aktif';

    if (empty($nama) || empty($username) || empty($email) || empty($password) || empty($role_id)) {
        $error = 'Semua field wajib diisi!';
    } else {
        // Check duplicate
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username atau Email sudah digunakan!';
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO pengguna (nama_lengkap, username, email, password_hash, role_id, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$nama, $username, $email, $password_hash, $role_id, $status])) {
                $id = $pdo->lastInsertId();
                logActivity($pdo, $_SESSION['user_id'], 'INSERT', 'pengguna', $id, "Menambahkan pengguna baru: $username");
                $_SESSION['success'] = 'Pengguna berhasil ditambahkan!';
                header("Location: index.php");
                exit();
            } else {
                $error = 'Gagal menyimpan data!';
            }
        }
    }
}

require_once '../includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold text-hijau-tua mb-0">Tambah Pengguna</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success">Pengguna</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap pengguna" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Role</label>
                            <select name="role_id" class="form-select" required>
                                <option value="">Pilih Role</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= h($r['nama_role']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Status Akun</label>
                        <select name="status" class="form-select">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-hijau px-4">
                            <i class="bi bi-save me-2"></i> Simpan Pengguna
                        </button>
                        <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
