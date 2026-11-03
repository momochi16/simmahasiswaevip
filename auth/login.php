<?php
// auth/login.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT p.*, r.nama_role 
                              FROM pengguna p 
                              JOIN roles r ON p.role_id = r.id 
                              WHERE p.username = ? OR p.email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] === 'Aktif') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role_name'] = $user['nama_role'];
                
                // Log activity
                logActivity($pdo, $user['id'], 'LOGIN', 'pengguna', $user['id'], 'User login successfully');

                header("Location: ../dashboard.php");
                exit();
            } else {
                $error = 'Akun Anda tidak aktif. Silakan hubungi administrator.';
            }
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Mahasiswa UMB</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border: 1px solid #eee;
        }
        .login-logo {
            width: 70px;
            height: 70px;
            margin-bottom: 20px;
        }
        .btn-hijau {
            background-color: #198754;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-hijau:hover {
            background-color: #157347;
            color: white;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #198754;
        }
        .input-group-text {
            background-color: transparent;
            border-left: none;
            cursor: pointer;
        }
        .password-field {
            border-right: none;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center">
            <img src="../assets/logo.png" alt="Logo UMB" class="login-logo">
            <h4 class="fw-bold mb-1">Selamat Datang</h4>
            <p class="text-muted small mb-4">Silakan login untuk mengakses sistem</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?= $error ?></div>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label small fw-bold">Username atau Email</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control password-field" id="password" name="password" placeholder="Masukkan password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-hijau rounded-8 mb-3">Login</button>
            </div>
            <div class="text-center">
                <a href="../index.php" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
            </div>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
