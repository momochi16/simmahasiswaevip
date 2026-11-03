<?php
// mahasiswa/delete.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT nama_mahasiswa, npm FROM mahasiswa WHERE id = ?");
    $stmt->execute([$id]);
    $m = $stmt->fetch();

    if ($m) {
        $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
        if ($stmt->execute([$id])) {
            logActivity($pdo, $_SESSION['user_id'], 'DELETE', 'mahasiswa', $id, "Menghapus mahasiswa: {$m['nama_mahasiswa']} ({$m['npm']})");
            $_SESSION['success'] = "Data mahasiswa {$m['nama_mahasiswa']} berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data!";
        }
    }
}

header("Location: index.php");
exit();
?>
