<?php
// fakultas/delete.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    // Get name for logging
    $stmt = $pdo->prepare("SELECT nama_fakultas FROM fakultas WHERE id = ?");
    $stmt->execute([$id]);
    $name = $stmt->fetchColumn();

    if ($name) {
        $stmt = $pdo->prepare("DELETE FROM fakultas WHERE id = ?");
        if ($stmt->execute([$id])) {
            logActivity($pdo, $_SESSION['user_id'], 'DELETE', 'fakultas', $id, "Menghapus fakultas: $name");
            $_SESSION['success'] = "Data fakultas $name berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data!";
        }
    }
}

header("Location: index.php");
exit();
?>
