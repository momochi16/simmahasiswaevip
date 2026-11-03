<?php
// includes/functions.php

/**
 * Log activity to audit_log table
 */
function logActivity($pdo, $pengguna_id, $aktivitas, $nama_tabel = null, $data_id = null, $keterangan = null) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $sql = "INSERT INTO audit_log (pengguna_id, aktivitas, nama_tabel, data_id, keterangan, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pengguna_id, $aktivitas, $nama_tabel, $data_id, $keterangan, $ip_address, $user_agent]);
}

/**
 * Sanitize output
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit();
    }
}

/**
 * Check if user has admin role
 */
function isAdmin() {
    return isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'Admin';
}

/**
 * CSRF Token Generation
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token Validation
 */
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
