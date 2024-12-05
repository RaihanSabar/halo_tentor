<?php
include 'includes/koneksi.php'; // Sertakan koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Array untuk menyimpan ID dan nama file gambar
    $ids = [];
    $images = [];

    // Proses untuk setiap gambar
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["testimonialImage$i"]) && $_FILES["testimonialImage$i"]['error'] == 0) {
            $image = $_FILES["testimonialImage$i"];
            $ids[$i] = $_POST["id$i"]; // Ambil ID dari input tersembunyi

            // Validasi file gambar (ekstensi dan ukuran)
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
            
            if (in_array($fileExtension, $allowedExtensions)) {
                // Tentukan nama file dan lokasi penyimpanan
                $fileName = uniqid() . '.' . $fileExtension;
                $uploadPath = 'uploads/' . $fileName;

                // Pindahkan file ke folder uploads
                if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                    // Simpan informasi ke dalam array untuk disimpan ke database
                    $images[$i] = $fileName;
                } else {
                    echo "Terjadi kesalahan saat mengunggah gambar $i.";
                }
            } else {
                echo "Format file untuk gambar $i tidak diperbolehkan. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
            }
        } else {
            // Jika tidak ada file yang diupload, ambil nama file yang sudah ada
            $images[$i] = null; // Atau bisa diisi dengan nama file yang sudah ada jika ingin mempertahankan gambar lama
        }
    }

    // Update database untuk setiap gambar
    for ($i = 1; $i <= 3; $i++) {
        if ($images[$i] !== null) {
            $stmt = $conn->prepare("UPDATE testimonials SET image = :image WHERE id = :id");
            $stmt->bindParam(':image', $images[$i]);
            $stmt->bindParam(':id', $ids[$i]);
            $stmt->execute();
        }
    }

    // Redirect atau beri pesan sukses
    header('Location: manage_testimonials.php');
    exit();
}
?>