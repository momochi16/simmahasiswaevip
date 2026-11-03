<?php
// universitas/delete.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT nama_universitas FROM universitas WHERE id = ?");
    $stmt->execute([$id]);
    $name = $stmt->fetchColumn();

    if ($name) {
        $stmt = $pdo->prepare("DELETE FROM universitas WHERE id = ?");
        if ($stmt->execute([$id])) {
            logActivity($pdo, $_SESSION['user_id'], 'DELETE', 'universitas', $id, "Menghapus universitas: $name");
            $_SESSION['success'] = "Data universitas $name berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data!";
        }
    }
}

header("Location: index.php");
exit();
?>
