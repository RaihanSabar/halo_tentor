<?php
session_start(); // Memulai session
include 'includes/koneksi.php'; // Pastikan Anda memiliki file ini untuk koneksi ke database

// Fungsi untuk menangani permintaan reset password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_tlp = $_POST['no_tlp'];

    // Mempersiapkan dan mengeksekusi query untuk memeriksa nomor telepon
    $stmt = $conn->prepare("SELECT * FROM admin WHERE no_tlp = :no_tlp");
    $stmt->bindParam(':no_tlp', $no_tlp);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Setelah memeriksa nomor telepon
    if ($result) {
        // Buat token reset password
        $token = bin2hex(random_bytes(16)); // Membuat token acak
        $current_time = date('Y-m-d H:i:s');
        $expiration_time = date('Y-m-d H:i:s', strtotime('+5 minutes')); // Waktu kadaluarsa 5 menit

        // Simpan token dan waktu kadaluarsa di database
        $query = "UPDATE admin SET reset_token = :token, reset_token_created_at = :created_at, reset_token_expiration = :expiration WHERE no_tlp = :no_tlp";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':created_at', $current_time);
        $stmt->bindParam(':expiration', $expiration_time);
        $stmt->bindParam(':no_tlp', $no_tlp);
        $stmt->execute();

        // Buat link reset password
        $link_reset_password = "http://localhost/halo_tentor/admin/reset_password.php?no_tlp=" . urlencode($no_tlp) . "&token=" . urlencode($token);

        // Membuka link WhatsApp untuk mengirim pesan
        $whatsapp_message = "Link reset password Anda (berlaku selama 5 menit): " . $link_reset_password;
        $whatsapp_link = "https://wa.me/" . urlencode($no_tlp) . "?text=" . urlencode($whatsapp_message);

        // Mengembalikan link WhatsApp sebagai respons
        echo $whatsapp_link; // Mengembalikan link WhatsApp
        exit();
    } else {
        // Jika nomor telepon tidak ditemukan
        $_SESSION['reset_message'] = "Nomor telepon tidak ada.";
        header('Location: lupa_password.php'); // Kembali ke halaman reset password
        exit();
    }

    $stmt->closeCursor();
    $conn = null; // Menutup koneksi
} else {
    // Jika bukan metode POST, arahkan kembali ke halaman reset password
    header('Location: lupa_password.php');
    exit();
}
?>