<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan server database Anda
$username = "username"; // Ganti dengan username database Anda
$password = "password"; // Ganti dengan password database Anda
$dbname = "db_halotentor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses pengunggahan gambar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['hero-image'])) {
    $target_dir = "uploads/"; // Folder untuk menyimpan gambar
    $target_file = $target_dir . basename($_FILES["hero-image"]["name"]);
    
    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($_FILES["hero-image"]["tmp_name"], $target_file)) {
        // Simpan jalur gambar ke database
        $sql = "INSERT INTO hero_images (image_path) VALUES ('$target_file')";
        if ($conn->query($sql) === TRUE) {
            echo "Gambar berhasil diunggah dan disimpan.";
            // Redirect kembali ke halaman dashboard admin
            header("Location: index.php"); // Ganti dengan nama file HTML Anda
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Maaf, terjadi kesalahan saat mengunggah gambar.";
    }
}

$conn->close();
?>