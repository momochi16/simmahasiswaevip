<?php
// auth/logout.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    // Log activity
    logActivity($pdo, $_SESSION['user_id'], 'LOGOUT', 'pengguna', $_SESSION['user_id'], 'User logout successfully');
    
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Redirect to login page
header("Location: login.php");
exit();
?>
