<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Cek apakah ID ada di URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Siapkan statement untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM daftar_biaya WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Pastikan ID diikat sebagai integer

    // Eksekusi statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Data biaya berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan saat menghapus data biaya.";
    }
} else {
    $_SESSION['message'] = "ID tidak valid.";
}

// Arahkan kembali ke halaman kelola biaya
header("Location: manage_biaya.php");
exit;
?>