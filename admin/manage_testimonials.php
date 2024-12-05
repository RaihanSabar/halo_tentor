<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Testimoni</title>
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

// Ambil data testimoni dari database
$stmt = $conn->query("SELECT * FROM testimonial");
$testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h1 class="custom-h1">Kelola Testimoni</h1>

                <h2 class="mt-5 text-center">Daftar Testimoni</h2>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan daftar testimoni
                        foreach ($testimonials as $row) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>
                                    <img src='uploads/{$row['image']}' alt='Testimoni' class='img-thumbnail clickable-image' style='width: 100px; height: auto;' data-toggle='modal' data-target='#imageModal' data-image='uploads/{$row['image']}'>
                                </td>
                                <td>
                                    <a href='edit_testimonial.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                                    <a href='delete_testimonial.php?id={$row['id']}' class='btn btn-danger'>Hapus</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar besar -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Gambar Testimoni</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Gambar Testimoni" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Tambahkan script jQuery dan Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Script untuk menangani klik pada gambar
    $(document).ready(function() {
        $('.clickable-image').click(function() {
            var imageSrc = $(this).data('image'); // Ambil src gambar dari data-image
            $('#modalImage').attr('src', imageSrc); // Set src modal dengan src gambar
        });
    });
</script>