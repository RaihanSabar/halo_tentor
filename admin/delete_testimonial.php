<?php
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Cek apakah ID testimoni ada di URL
if (!isset($_GET['id'])) {
    header('Location: manage_testimonials.php');
    exit;
}

$id = $_GET['id'];

// Hapus testimoni dari database
$stmt = $conn->prepare("DELETE FROM testimonial WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

// Redirect ke halaman kelola testimoni setelah dihapus
header('Location: manage_testimonials.php');
exit;
?>