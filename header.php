<?php
include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database

// Ambil data admin dari database
$stmt = $conn->query("SELECT no_tlp FROM admin LIMIT 1"); // Ambil satu data admin
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$whatsappNumber = $admin['no_tlp']; // Simpan nomor telepon ke variabel
?>

<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand logo" href="index.php">
                <img src="assets/images/logo.png" alt="Halo Tentor Logo" height="75" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#kenapa-kami">Mengapa Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#materi-pelajaran">Materi Pelajaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="biaya.php">Biaya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#testimoni">Testimoni</a>
                    </li>
                </ul>
                <div class="ml-auto">
                    <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Kak,%20saya%20mau%20mendaftar%20di%20Halo%20Tentor" class="whatsapp-button btn btn-success">
                        <img src="assets/images/whatsapp-icon.png" alt="WhatsApp Icon" height="20"> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarNav = document.querySelector('#navbarNav');

        navbarToggler.addEventListener('click', function() {
            navbarNav.classList.toggle('show'); // Toggle class show untuk navbar

            // Ubah ikon hamburger menjadi simbol X
            const togglerIcon = navbarToggler.querySelector('.navbar-toggler-icon');
            if (navbarNav.classList.contains('show')) {
                togglerIcon.classList.add('x'); // Tambahkan kelas x untuk simbol X
            } else {
                togglerIcon.classList.remove('x'); // Hapus kelas x untuk kembali ke hamburger
            }
        });

        const navbarLinks = document.querySelectorAll('.navbar-nav .nav-link');

        navbarLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                // Hapus kelas active dari semua link
                navbarLinks.forEach(nav => nav.classList.remove('active'));
                
                // Tambahkan kelas active ke link yang diklik
                this.classList.add('active');

                const target = this.getAttribute('href');

                // Jika link mengarah ke #home
                if (target === 'index.php#home') {
                    event.preventDefault(); // Mencegah perilaku default
                    window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll halus ke atas
                }
            });
        });

        // Mengatur kelas aktif berdasarkan URL saat ini
        const currentUrl = window.location.href;
        navbarLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
            }
        });

        // Menutup navbar jika klik di luar navbar
        document.addEventListener('click', function(event) {
            if (!navbarNav.contains(event.target) && !navbarToggler.contains(event.target)) {
                if (navbarNav.classList.contains('show')) {
                    navbarNav.classList.remove('show'); // Menutup navbar
                    navbarToggler.querySelector('.navbar-toggler-icon').classList.remove('x'); // Kembali ke ikon hamburger
                }
            }
        });

        // Menutup navbar saat menggulir
        window.addEventListener('scroll', function() {
            if (navbarNav.classList.contains('show')) {
                navbarNav.classList.remove('show'); // Menutup navbar
                navbarToggler.querySelector('.navbar-toggler-icon').classList.remove('x'); // Kembali ke ikon hamburger
            }
        });
        window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 50) { // Jika scroll lebih dari 50px
        header.classList.add('header-scrolled'); // Tambahkan kelas untuk transparansi
    } else {
        header.classList.remove('header-scrolled'); // Hapus kelas jika scroll kurang dari 50px
    }
});
    });
</script>
