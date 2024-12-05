<?php
$host = 'localhost'; // Ganti dengan host database Anda
$db = 'db_halotentor'; // Ganti dengan nama database Anda
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     // Tambahkan ini untuk menguji koneksi
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
