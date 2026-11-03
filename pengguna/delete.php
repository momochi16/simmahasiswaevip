<?php
// pengguna/delete.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT username, nama_lengkap FROM pengguna WHERE id = ?");
    $stmt->execute([$id]);
    $u = $stmt->fetch();

    if ($u && $u['username'] !== 'admin') {
        $stmt = $pdo->prepare("DELETE FROM pengguna WHERE id = ?");
        if ($stmt->execute([$id])) {
            logActivity($pdo, $_SESSION['user_id'], 'DELETE', 'pengguna', $id, "Menghapus akun: {$u['nama_lengkap']} ({$u['username']})");
            $_SESSION['success'] = "Akun {$u['nama_lengkap']} berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data!";
        }
    }
}

header("Location: index.php");
exit();
?>
