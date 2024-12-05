<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tutor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>  
    /* CSS untuk tabel di tampilan desktop */
.table {
    margin-top: 20px;
    border-collapse: collapse;
    width: 100%;
}

.table th, .table td {
    padding: 15px;
    text-align: left;
    border: 1px solid #dee2e6; /* Warna border */
}

.table th {
    background-color: #343a40; /* Warna latar belakang header */
    color: white; /* Warna teks header */
}

.table tr:nth-child(even) {
    background-color: #f2f2f2; /* Warna latar belakang baris genap */
}

.table tr:hover {
    background-color: #e9ecef; /* Warna latar belakang saat hover */
}

.img-thumbnail {
    border-radius: 0.25rem; /* Radius sudut untuk gambar */
}

.custom-h1 {
    font-size: 2rem; /* Ukuran font untuk judul */
    color: #343a40; /* Warna teks judul */
    text-align: center; /* Pusatkan teks */
    margin-bottom: 30px; /* Jarak bawah */
}

/* Tambahkan margin pada tombol */
.btn {
    margin-right: 5px;
}
</style>  
</head>
<body>

<?php 
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

include 'includes/koneksi.php'; // Sertakan koneksi ke database
include 'includes/header.php'; 
include 'includes/sidebar.php'; // Sertakan sidebar

// Ambil data tutor dari database
$stmt = $conn->query("SELECT * FROM tutors");
$tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <?php include 'includes/sidebar.php'; ?>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-10">
            <div class="container mt-5">
                <h1 class="custom-h1">Kelola Tutor</h1>
                
                <h2 class="mt-5 text-center">Daftar Tutor</h2>
                <div class="table-responsive"> <!-- Tambahkan kelas ini -->
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Tutor</th>
                                <th>Asal Universitas</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Tampilkan daftar tutor
                            foreach ($tutors as $row) {
                                // Pastikan path gambar tutor benar
                                $imagePath = 'uploads/' . htmlspecialchars($row['photo']);
                                
                                // Cek apakah file gambar tutor ada
                                if (!file_exists($imagePath)) {
                                    // Jika gambar tidak ada, gunakan gambar default dari kolom default_photo di database
                                    $imagePath = htmlspecialchars($row['photo']); // Ambil gambar default dari database
                                }

                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['university']}</td>
                                    <td><img src='{$imagePath}' alt='Foto Tutor' class='img-thumbnail' style='width: 100px; height: auto;'></td>
                                    <td>
                                        <a href='edit_tutor.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                                        <a href='delete_tutor.php?id={$row['id']}' class='btn btn-danger'>Hapus</a>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div> <!-- Tutup div .table-responsive -->
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>