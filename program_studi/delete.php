<?php
// program_studi/delete.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT nama_prodi FROM program_studi WHERE id = ?");
    $stmt->execute([$id]);
    $name = $stmt->fetchColumn();

    if ($name) {
        $stmt = $pdo->prepare("DELETE FROM program_studi WHERE id = ?");
        if ($stmt->execute([$id])) {
            logActivity($pdo, $_SESSION['user_id'], 'DELETE', 'program_studi', $id, "Menghapus prodi: $name");
            $_SESSION['success'] = "Data program studi $name berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data!";
        }
    }
}

header("Location: index.php");
exit();
?>
