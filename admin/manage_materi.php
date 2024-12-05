<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi Pelajaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

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

// Ambil data materi pelajaran dari database
$stmt = $conn->query("SELECT * FROM materi_pelajaran");
$materi = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h1 class="custom-h1">Kelola Materi Pelajaran</h1>

                <h2 class="mt-5 text-center">Daftar Materi Pelajaran</h2>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tingkat</th>
                            <th>Materi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan daftar materi pelajaran
                        foreach ($materi as $row) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['tingkat']}</td>
                                <td>{$row['materi']}</td>
                                <td>
                                    <a href='edit_materi.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                                    <a href='delete_materi.php?id={$row['id']}' class='btn btn-danger' onclick=\"return confirm('Apakah Anda yakin ingin menghapus materi ini?');\">Hapus</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <a href="add_materi.php" class="btn btn-success">Tambah Materi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>