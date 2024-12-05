<?php
session_start(); // Memulai sesi di awal file

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Mengatur waktu aktivitas
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1000)) {
    session_unset(); // Menghapus semua sesi
    session_destroy(); // Menghancurkan sesi
    header("Location: login.php"); // Arahkan ke halaman login
    exit;
}
$_SESSION['last_activity'] = time(); // Perbarui waktu aktivitas

// Kode lainnya untuk menampilkan halaman admin
include 'includes/koneksi.php'; // Mengimpor koneksi
include 'data.php'; // Mengimpor data dari file data.php
include 'includes/header.php'; // Sertakan header
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Halo Tentor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">    <!-- CSS kustom -->
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Menggunakan font Roboto */
            margin: 0;
            padding-top: 75px; /* Memberikan ruang untuk header */
            display: flex; /* Menggunakan flexbox untuk tata letak */
            flex-direction: column; /* Mengatur arah kolom */
            min-height: 100vh; /* Memastikan body memiliki tinggi minimum 100% dari viewport */
        }
        
        .content {
            margin-left: 200px; /* Sesuaikan dengan lebar sidebar */
            padding: 20px;
            flex-grow: 1; /* Mengisi ruang yang tersisa */
            padding-bottom: 80px; /* Memberikan ruang untuk footer */
        }
        .content h1 {
            color: #F1F1F1; /* Mengubah warna teks h1 menjadi hitam */
        }
        .card {
            transition: transform 0.2s;
            background-color: #F1F1F1;
            border-radius: 10px;
            margin-bottom: 20px;
            width: 100%; /* Lebar kartu 100% */
            height: 150px; /* Atur tinggi kartu agar lebih pendek */
            position: relative; /* Pastikan kartu memiliki posisi relatif */
            display: flex; /* Gunakan flexbox untuk isi kartu */
            flex-direction: column; /* Atur isi kartu dalam kolom */
            justify-content: center; /* Pusatkan isi kartu secara vertikal */
            align-items: center; /* Pusatkan isi kartu secara horizontal */
            text-align: center; /* Pusatkan teks di dalam kartu */
        }
        .card-title {
    font-size: 1.5rem; /* Ukuran font untuk judul kartu */
    font-weight: bold; /* Membuat judul lebih tebal */
    margin-bottom: 10px; /* Jarak bawah untuk judul */
    color: #000;
}

.card-text {
    font-size: 1rem; /* Ukuran font untuk teks dalam kartu */
    margin-bottom: 10px; /* Jarak bawah untuk teks */
    color: #000;
}


        .card:hover {
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            
            .content {
                margin-left: 0; /* Menghapus margin kiri pada layar kecil */
                padding: 10px; /* Mengurangi padding untuk perangkat kecil */
                padding-bottom: 60px; /* Memberikan ruang untuk footer */
            }
            .card {
                width: 100%; /* Lebar kartu 100% pada layar kecil */
                height: auto; /* Tinggi otomatis untuk responsivitas */
            }
        }
        
    </style>
</head>
<body>

    <?php include 'includes/sidebar.php'; ?> <!-- Sertakan sidebar di sini -->

<div class="content">
<h1 class="custom-h1">Dashboard Admin Halo Tentor</h1>    
    <!-- Tampilkan pesan login jika ada -->
    <?php
    if (isset($_SESSION['login_message'])) {
        echo "<div class='alert alert-success text-center' role='alert'>" . $_SESSION['login_message'] . "</div>";
        unset($_SESSION['login_message']); // Hapus pesan setelah ditampilkan
    }
    ?>
    
    <div class="row mt-4">
        <div class="col-12 col-sm-6 col-md-4 mb-4"> <!-- Menggunakan kelas kolom responsif -->
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Kelola Hero</h5>
                    <p class="card-text">Kelola tampilan hero untuk tampilan website.</p>
                    <a href="manage_hero.php" class="btn btn-primary">Kelola Hero</a>
                </div>
            </div>
        </div>
        <!--<div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Kelola Tutor</h5>
                    <p class="card-text"><?php echo $tutor_count; ?> Tutor Terdaftar</p>
                    <a href="manage_tutors.php" class="btn btn-primary">Kelola Tutor</a>
                </div>
            </div>
        </div>-->
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Kelola Materi</h5>
                    <p class="card-text"><?php echo $material_count; ?> Materi Tersedia</p>
                    <a href="manage_materi.php" class="btn btn-primary">Kelola Materi</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Kelola Biaya</h5>
                    <p class="card-text"><?php echo $biaya_count; ?> Biaya Kelas</p>
                    <a href="manage_biaya.php" class="btn btn-primary">Kelola Biaya</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Kelola Testimoni</h5>
                    <p class="card-text"><?php echo $testimonial_count; ?> Testimoni Tersedia</p>
                    <a href="manage_testimonials.php" class="btn btn-primary">Kelola Testimoni</a>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php include 'includes/footer.php'; ?> <!-- Sertakan footer di sini -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>