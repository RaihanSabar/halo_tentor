<?php 
include 'includes/koneksi.php'; // Sertakan koneksi ke database
include 'includes/header.php'; 

// Cek apakah ID materi ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data materi berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM materi_pelajaran WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<p>Materi berhasil dihapus.</p>";
    } else {
        echo "<p>Terjadi kesalahan saat menghapus materi.</p>";
    }
} else {
    echo "<p>ID tidak valid.</p>";
}

// Tautan untuk kembali ke daftar materi
echo "<a href='manage_materi.php' class='btn btn-primary'>Kembali ke Daftar Materi</a>";

include 'includes/footer.php'; 
?>