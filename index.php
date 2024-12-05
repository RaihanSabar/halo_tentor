<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halo Tentor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"> <!-- Bootstrap CSS lokal -->
    <link rel="stylesheet" href="style.css"> <!-- CSS kustom -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .dropdown-content {
    display: none; /* Sembunyikan konten dropdown secara default */
    overflow: hidden;
    padding: 10px; /* Anda bisa menyesuaikan padding ini */
    margin-top: 0; /* Jarak atas yang lebih kecil */
    margin-bottom: 5px; /* Mengurangi jarak bawah menjadi 5px */
    color: #333; /* Warna teks dalam dropdown */
    padding-left: 15px; /* Tambahkan padding kiri untuk memberi ruang */
    word-wrap: break-word; /* Memecah kata yang terlalu panjang */
    overflow-wrap: break-word; /* Memecah kata yang terlalu panjang */
}

.dropdown-toggle {
    cursor: pointer; /* Ubah kursor saat hover */
    background-color: #fbff00; /* Warna latar belakang untuk judul dropdown */
    color: #333; /* Warna teks untuk judul dropdown */
    padding: 5px;
    border: 1px solid #ccc; /* Border untuk judul */
    border-radius: 5px; /* Bulatkan sudut */
    margin-bottom: 0; /* Pastikan tidak ada jarak bawah */
    width: 100%; /* Pastikan dropdown toggle memenuhi lebar kontainer */
}

.dropdown-toggle:hover {
    background-color: #fbff50; /* Warna saat hover */
}
        .hero-content {
            text-align: center;
            margin-bottom: 20px;
        }
        .hero-slides {
            position: relative;
            max-width: 100%;
            overflow: hidden;
        }
        .hero-slide {
            display: none; /* Sembunyikan semua slide */
        }
        .hero-slide.active {
            display: block; /* Tampilkan slide aktif */
        }
        .section {
            margin-bottom: 20px; /* Jarak antar section */
        }
        .testimonial {
            text-align: center;
        }
        article, section {
    scroll-margin-top: 80px; /* Atur sesuai dengan tinggi navbar Anda */
    margin-top: 20px;
}
    </style>
</head>

<body>
<?php include 'header.php'; ?>

<article id="home">
<section class="hero">
<?php
include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database

// Ambil data dari tabel hero_content
$stmt = $conn->query("SELECT * FROM hero_content LIMIT 1"); // Ambil satu baris
$heroContent = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data gambar dari tabel hero_images
$query = "SELECT * FROM hero_images";
$stmt = $conn->prepare($query);
$stmt->execute();
$heroImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="hero-content">
        <h1><?php echo htmlspecialchars($heroContent['title']); ?></h1>
        <p><?php echo htmlspecialchars($heroContent['description']); ?></p>
        <a class="cta-button-hero" href="https://wa.me/<?php echo $whatsappNumber; ?>">DAFTAR SEKARANG</a>
    </div>
    <div class="hero-slides">
    <?php foreach ($heroImages as $index => $image): ?>
        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; echo $image['title'] === 'Hero Promo' ? ' promo' : ''; ?>">
            <img src="admin/uploads/<?php echo htmlspecialchars($image['image']); ?>" alt="Hero Image <?php echo $index + 1; ?>" class="img-fluid">
        </div>
    <?php endforeach; ?>
</div>
</section>
</article>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    let slideInterval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.display = (i === index) ? 'block' : 'none';
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function startSlideShow() {
        slideInterval = setInterval(nextSlide, 5000); // Ganti slide setiap 5 detik
    }

    function stopSlideShow() {
        clearInterval(slideInterval);
    }

    // Event listeners untuk gambar hero
    const heroImages = document.querySelectorAll('.hero-slide img');
    heroImages.forEach(image => {
        image.addEventListener('mousedown', stopSlideShow); // Hentikan slideshow saat ditekan
        image.addEventListener('mouseup', startSlideShow); // Mulai kembali slideshow saat dilepaskan
        image.addEventListener('mouseleave', stopSlideShow); // Hentikan slideshow jika mouse keluar
    });

    showSlide(currentSlide);
    startSlideShow(); // Mulai slideshow saat halaman dimuat
</script>

<article id="kenapa-kami" class="custom-article">
    <section class="kenapa-kami">
        <div class="container d-flex flex-column flex-md-row"> <!-- Tambahkan kelas flex -->
            <div class="image-container"> <!-- Kontainer untuk gambar -->
                <img alt="Gambar ilustrasi mengapa memilih Halo Tentor" class="illustration img-fluid" src="https://media.istockphoto.com/id/969985354/photo/why-choose-us-chalkboard-on-a-wooden-background.jpg?s=612x612&w=0&k=20&c=Ar6vuQNk-SEk6VImylo0ocbvlKksZG9nc6de3_yDFKk=" />
            </div>
            <div class="content-container"> <!-- Kontainer untuk konten -->
                <div class="content">
                    <h2>Kenapa Sih Harus Pilih Halo Tentor?</h2>
                    <p>Halo Tentor menyediakan tentor terpercaya dengan segudang
                    pengalaman mengajar yang baik, karena kami telah merekrut
                    tentor terbaik di bidangnya untuk dapat bergabung bersama kami.</p>
                    <ul>
                        <li>Biaya terjangkau</li>
                        <li>Belajar sistem offline dan online</li>
                        <li>Pilihan kelompok belajar fleksibel</li>
                        <li>Durasi belajar ideal</li>
                        <li>Materi belajar terfokus</li>
                        <li>Tim Tentor berkualitas</li>
                        <li>Monitor dan evaluasi terjadwal</li>
                    </ul>
                    <a href="#" class="read-more">Baca selengkapnya</a> <!-- Tambahkan tulisan navigasi -->    
                </div>
            </div>
        </div>
    </section>
</article>

<section class="section pricing" id="biaya-terjangkau">
    <h2 class="dropdown-toggle">Biaya Terjangkau</h2>
    <div class="dropdown-content">    
        <div class="info-box"> <!-- Kotak untuk konten -->
        <p>Biaya bimbingan belajar di Halo Tentor dapat disesuaikan dengan kebutuhan belajar siswa, yang pastinya terjangkau.</p>
        </div>
        <!-- Kotak Navigasi Rincian Biaya -->
        <div class="card-body text-center">
            <a href="biaya.php" class="btn btn-primary custom-btn">Rincian Biaya</a>
        </div>
    </div>
</section>

<section class="section learning-system">
    <h2 class="dropdown-toggle">Metode Pembelajaran</h2>
    <div class="dropdown-content">
        <div class="row">
            <div class="box col-md-6">
            <h3>Offline</h3>
                <p>Dilakukan jika lokasi rumah siswa di wilayah Jabodetabek, dengan tim tentor pengajar yang akan datang ke rumah siswa.</p>
            </div>
            <div class="box col-md-6">
                <h3>Online</h3>
                <p>Dilakukan jika lokasi rumah siswa di luar Jabodetabek.</p>
            </div>
        </div>
    </div>
</section>

<section class="section flexible-groups">
    <h2 class="dropdown-toggle">Kelompok Belajar Fleksibel</h2>
    <div class="dropdown-content">
        <div class="row">
            <div class="box col-md-6">
                <h3>Perseorangan</h3>
                <p>1 tentor hanya mengajar 1 siswa.</p>
            </div>
            <div class="box col-md-6">
                <h3>Berkelompok</h3>
                <p>1 tentor untuk 1 kelompok <br> belajar dengan maksimal 5 siswa.</p>
            </div>
        </div>
    </div>
</section>

<section class="section ideal-duration">
    <h2 class="dropdown-toggle">Durasi Belajar Ideal</h2>
    <div class="dropdown-content">
    <div class="info-box"> <!-- Kotak untuk konten -->
        <p>Durasi belajar dilakukan selama 90 menit per pertemuan.
            Namun, jika memerlukan pertambahan jam per pertemuan,
            orang tua siswa bisa menghubungi tim admin Halo Tentor.</p>
    </div>
    </div>
</section>

<section class="section focused-material">
    <h2 class="dropdown-toggle">Materi Belajar Terfokus</h2>
    <div class="dropdown-content">
    <div class="info-box"> <!-- Kotak untuk konten -->
        <p>Materi yang diajarkan terfokus sesuai dengan kebutuhan materi pelajaran siswa di sekolah.</p>
    </div>
    </div>
</section>

<section class="section quality-tutors">
    <h2 class="dropdown-toggle">Tim Tentor Berkualitas</h2>
    <div class="dropdown-content">
    <div class="info-box"> <!-- Kotak untuk konten -->
        <p>Setiap pengajar di Halo Tentor adalah mereka yang telah
            memenuhi persyaratan menjadi tim pengajar di Halo Tentor.</p>
            </div>
        <!--<div class="tutor-features row">
        <?php 
        include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database

        // Ambil data tutor dari database
        $stmt = $conn->query("SELECT * FROM tutors");
        $tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tampilkan data tutor dalam bentuk card
        foreach ($tutors as $tutor) {
            // Pastikan path gambar tutor benar
            $imagePath = 'admin/uploads/' . htmlspecialchars($tutor['photo']);
            
            // Cek apakah file gambar tutor ada
            if (!file_exists($imagePath)) {
                // Jika gambar tidak ada, gunakan gambar default dari kolom default_photo di database
                $imagePath = htmlspecialchars($tutor['photo']); // Ambil gambar default dari database
            }

            echo "<div class='tutor-feature col-md-4 mb-4'>
                    <div class='card'>
                        <img alt='Foto tutor' src='{$imagePath}' class='card-img-top img-fluid' />
                        <div class='card-body'>
                            <h5 class='card-title'>{$tutor['nama']}</h5>
                            <p class='card-text'>{$tutor['university']}</p>
                        </div>
                    </div>
                </div>";
        }
        ?>
                </div>-->
    </div>
</section>

<section class="section monitoring-evaluation">
    <h2 class="dropdown-toggle">Monitor dan Evaluasi Terjadwal</h2>
    <div class="dropdown-content">
    <div class="info-box"> <!-- Kotak untuk konten -->
        <p>Setiap siswa di Halo Tentor akan memiliki <strong>Buku Pemantauan Hasil Belajar</strong>
        yang akan diisi setiap selesai pertemuan.</p>
    </div>
    </div>
</section>

<script>
    // Ambil semua elemen dengan kelas dropdown-toggle
    const dropdowns = document.querySelectorAll('.dropdown-toggle');

    // Tambahkan event listener untuk setiap dropdown
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function() {
            // Ambil konten dropdown yang sesuai
            const content = this.nextElementSibling;

            // Toggle antara menampilkan dan menyembunyikan konten
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    });

    // Fungsi untuk membuka atau menutup semua dropdown
    document.querySelector('.read-more').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah perilaku default anchor

        // Cek apakah semua dropdown-content sedang terbuka
        const allOpen = Array.from(dropdowns).every(dropdown => {
            const content = dropdown.nextElementSibling;
            return content.style.display === "block";
        });

        // Jika semua dropdown terbuka, tutup semua
        if (allOpen) {
            dropdowns.forEach(dropdown => {
                const content = dropdown.nextElementSibling;
                content.style.display = "none"; // Setiap konten dropdown menjadi tidak terlihat
            });
        } else {
            // Tampilkan semua dropdown-content
            dropdowns.forEach(dropdown => {
                const content = dropdown.nextElementSibling;
                content.style.display = "block"; // Setiap konten dropdown menjadi terlihat
            });
        }
    });
</script>

<article id="materi-pelajaran" class="custom-article">
    <section class="section">
        <h2>Materi Pelajaran</h2>
        <?php 
        include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database

        // Ambil data materi pelajaran dari database
        $stmt = $conn->query("SELECT * FROM materi_pelajaran");
        $materi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?> 

<div class="container mt-5">
    <div class="features"> <!-- Mengganti row dengan features -->
        <?php
        // Tampilkan materi pelajaran dalam bentuk kartu
        foreach ($materi as $row) {
            echo "<div class='feature'>
                    <h3>{$row['tingkat']}</h3>
                    <ul>";
            // Memisahkan berdasarkan <br> dan menghilangkan tag HTML
            $materiList = explode("<br>", $row['materi']); 
            foreach ($materiList as $item) {
                // Trim untuk menghapus spasi di awal dan akhir
                $item = trim($item);
                if (!empty($item)) { // Hanya menampilkan item yang tidak kosong
                    echo "<li>" . htmlspecialchars($item) . "</li>"; // Menghindari XSS
                }
            }
            echo "</ul>
                    </div>";
        }
        ?>
    </div>
</div>
    </section>
</article>

<section class="section tutors"> 
    <article id="read-more" class="tutors-content">
        <h2 style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 0)">Cara Mendaftar</h2>
        <p>Daftarkan putra putri Anda sekarang juga untuk mengikuti bimbingan belajar privat dan berkualitas dari Halo Tentor.</p>
        <a class="cta-button btn btn-primary" href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Kak,%20saya%20mau%20mendaftar%20di%20Halo%20Tentor" target="_blank">DAFTAR SEKARANG</a>
    </article>
</section>

<article id="testimoni" class="custom-article">
    <section class="section testimonials">
        <h2>Testimoni</h2><br>
        <div class="container">
            <div class="row justify-content-center"> <!-- Menambahkan justify-content-center untuk memusatkan -->
                <?php 
                // Ambil data testimoni dari database
                include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database
                $stmt = $conn->query("SELECT * FROM testimonial");
                $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Debugging: Cek apakah data testimoni ada
                if (empty($testimonials)) {
                    echo "Tidak ada testimoni ditemukan.";
                } else {
                    // Tampilkan daftar testimoni dalam bentuk gambar
                    foreach ($testimonials as $row) {
                        $imagePath = 'admin/uploads/' . $row['image'];
                        echo "<div class='col-12 col-sm-6 col-md-4 mb-4'> <!-- Menggunakan kelas responsif -->
                                <div class='testimonial'>
                                    <img src='$imagePath' class='testimonial-img img-fluid' alt='Testimoni'>
                                </div>
                              </div>";
                    }
                }
                ?>
            </div>
        </div>
    </section>
</article>

<script>
    const navbar = document.querySelector('.navbar');

    function updateNavbar() {
        if (window.location.hash === '#home') {
            navbar.classList.remove('navbar-dark'); // Kembalikan ke warna default
        } else {
            navbar.classList.add('navbar-dark'); // Ubah menjadi biru tua
        }
    }

    // Panggil fungsi saat halaman dimuat
    updateNavbar();

    // Tambahkan event listener untuk mendeteksi perubahan hash
    window.addEventListener('hashchange', updateNavbar);
</script>

<!-- Bagian Footer -->
<?php include 'footer.php'; ?>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbarLinks = document.querySelectorAll('.navbar-nav .nav-link');

            navbarLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    const targetId = this.getAttribute('href'); // Ambil href dari link
                    const targetElement = document.querySelector(targetId); // Ambil elemen target

                    if (targetElement) {
                        event.preventDefault(); // Mencegah perilaku default

                        // Scroll ke posisi target dengan offset
                        const offset = 70; // Ubah nilai ini sesuai kebutuhan
                        const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                        const offsetPosition = elementPosition - offset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth' // Efek scroll halus
                        });
                    }
                });
            });
        });
    </script>
    
</body>
</html>
