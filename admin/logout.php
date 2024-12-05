<?php
session_start(); // Memulai session

// Hapus semua session
$_SESSION = array(); // Menghapus semua variabel session

// Hapus cookie jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy(); // Menghancurkan session

// Redirect ke halaman login
header("Location: login.php");
exit();
?>