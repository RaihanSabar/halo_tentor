<?php
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Cek apakah ID tutor ada di URL
if (!isset($_GET['id'])) {
    header('Location: manage_tutors.php');
    exit;
}

$id = $_GET['id'];

// Hapus tutor dari database
$stmt = $conn->prepare("DELETE FROM tutors WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

// Redirect ke halaman kelola tutor setelah dihapus
header('Location: manage_tutors.php');
exit;
?>