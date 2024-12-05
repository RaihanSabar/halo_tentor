<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Ganti dengan path CSS Anda -->
    <title>Admin Panel</title>
    <style>
        /* CSS untuk sidebar */
        .sidebar {
            height: 100vh; /* Tinggi sidebar */
            position: fixed; /* Menempel di sisi kiri */
            left: 0; /* Mengatur posisi kiri */
            top: 0; /* Mengatur posisi atas */
            width: 200px; /* Lebar sidebar */
            padding: 20px; /* Padding untuk sidebar */
            background-color: #add8e6; /* Warna latar belakang sidebar */
            border-right: 2px solid #97c6f8; /* Batas kanan sidebar */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Bayangan sidebar */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            transition: left 0.3s ease;
            z-index: 1000; /* Pastikan sidebar berada di atas elemen lain */
        }

        .header {
            background-color: #F5F5F7; /* Warna latar belakang header */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan bawah header */
            position: fixed; /* Menempel di atas */
            top: 0; /* Menempel di atas */
            left: 200px; /* Mengatur posisi kiri agar tidak menutupi sidebar */
            right: 0; /* Mengatur posisi kanan */
            z-index: 999; /* Memastikan header berada di bawah sidebar */
            height: 75px; /* Tinggi header */
        }

        /* Media query untuk sidebar pada layar kecil */
        @media (max-width: 768px) {
            .sidebar {
                left: -200px; /* Sembunyikan sidebar di luar layar */
            }
            .sidebar.collapsed {
                left: 0; /* Tampilkan sidebar saat terkolaps */
            }
            .header {
                left: 0; /* Mengatur posisi kiri header ke 0 */
                width: 100%; /* Mengatur lebar header menjadi 100% */
            }
        }

        /* Gaya untuk tautan di sidebar */
        .sidebar a {
            color: #333; /* Warna teks tautan */
            text-decoration: none; /* Menghilangkan garis bawah */
            padding: 10px; /* Padding untuk tautan */
            display: flex; /* Menggunakan flexbox untuk tata letak */
            align-items: center; /* Vertikal center */
            background-color: #e0f7fa; /* Warna latar belakang tautan */
            border-radius: 4px; /* Sudut membulat */
            margin-bottom: 10px; /* Jarak antar tautan */
            transition: background-color 0.3s; /* Transisi untuk efek hover */
        }

        .sidebar a.active {
            background-color: #97c6f8; /* Warna latar belakang saat aktif */
        }

        .sidebar i {
            margin-right: 10px; /* Margin kanan untuk ikon */
        }

        /* Gaya untuk logo di sidebar */
        .sidebar-logo {
            display: flex; /* Menggunakan flexbox untuk tata letak */
            justify-content: center; /* Rata tengah */
            margin-bottom: 20px; /* Jarak bawah untuk logo */
            width: 100%; /* Lebar logo diatur menjadi 100% */
            height: auto; /* Tinggi otomatis mengikuti lebar */
        }

        .sidebar-logo img {
            width: 100%; /* Mengatur lebar gambar menjadi 100% dari lebar kontainer */
            height: auto; /* Tinggi gambar otomatis untuk menjaga aspek rasio */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../assets/images/logo.png" alt="Halo Tentor Logo" height="75">
        </div>
        <div class="mb-3">
            <a href="index.php" class="text-dark <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
            </a>
        </div>
        <div class="mb-3">
            <a href="manage_hero.php" class="text-dark <?php echo basename($_SERVER['PHP_SELF']) == 'manage_hero.php' ? 'active' : ''; ?>">
                <i class="fas fa-image"></i> Kelola Hero
            </a>
        </div>
        <div class="mb-3">
            <a href="manage_materi.php" class="text-dark <?php echo basename($_SERVER['PHP_SELF']) == 'manage_materi.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> Kelola Materi
            </a>
        </div>
        <!--<div class="mb-3">
            <a href="manage_tutors.php" class="text-dark <?php echo basename($_SERVER['PHP_SELF']) == 'manage_tutors.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i> Kelola Tutor
            </a>
        </div>-->
        <div class="mb-3">
            <a href="manage_biaya.php" class="text-dark <?php echo basename($_SERVER['PHP_SELF']) == 'manage_biaya.php' ? 'active' : ''; ?>">
                <i class="fas fa-money-bill-wave"></i> Kelola Biaya
            </a>
        </div>
        <div class="mb-3">
            <a href="manage_testimonials.php" class="text-dark <?php echo basename($_SERVER['PHP_SELF']) == 'manage_testimonials.php' ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i> Kelola Testimoni
            </a>
        </div>
    </div>

    

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed'); // Tambahkan atau hapus kelas 'collapsed'
        }

        // Menambahkan event listener untuk resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');

            // Jika ukuran layar lebih besar dari 768px, pastikan sidebar terbuka
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('collapsed');
            }
        });

        // Memastikan sidebar dalam posisi yang benar saat halaman dimuat
        window.addEventListener('load', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('collapsed'); // Pastikan sidebar terbuka pada desktop
            }
        });
    </script>
</body>
</html>