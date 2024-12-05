<?php
// Sertakan koneksi ke database
include 'admin/includes/koneksi.php';

// Inisialisasi variabel pencarian
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Menyiapkan array untuk keywords
$keywords = [];
if ($search) {
    $keywords = explode(' ', $search);
}

// Fungsi untuk mengambil data berdasarkan jenjang
function getDataByJenjang($conn, $jenjang, $search, $keywords) {
    $query = "SELECT * FROM daftar_biaya WHERE jenjang = :jenjang";
    $params = ['jenjang' => $jenjang];

    if ($search) {
        $conditions = [];
        foreach ($keywords as $index => $keyword) {
            $conditions[] = "(kurikulum LIKE :keyword$index OR pembelajaran LIKE :keyword$index)";
            $params["keyword$index"] = '%' . $keyword . '%';
        }
        $query .= " AND " . implode(' AND ', $conditions);
    }

    // Tambahkan pengurutan berdasarkan kolom urutan
    $query .= " ORDER BY urutan ASC"; // Mengurutkan berdasarkan kolom urutan

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil data untuk setiap jenjang
$dataTK = getDataByJenjang($conn, 'TK', $search, $keywords);
$dataSD = getDataByJenjang($conn, 'SD', $search, $keywords);
$dataSMP = getDataByJenjang($conn, 'SMP', $search, $keywords);
$dataSMA = getDataByJenjang($conn, 'SMA/K', $search, $keywords);
$dataUMUM = getDataByJenjang($conn, 'UMUM', $search, $keywords);
$dataKHUSUS = getDataByJenjang($conn, 'KHUSUS', $search, $keywords);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Biaya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>    
    <!-- Bootstrap CSS Lokal -->
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"> <!-- Bootstrap CSS lokal -->

    <link rel="stylesheet" href="style.css"> <!-- Link ke file CSS -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            color: #000;
            font-family: 'Roboto', sans-serif;

        }

        .wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Memastikan wrapper memiliki tinggi minimal 100% dari viewport */
}

        body {
            padding-top: 120px;
        }

        .text-center {
    font-size: 32px;
    margin-top: 10px;
    margin-bottom: 10px;
    font-weight: bold;
}

.biaya-container {
    max-width: 1000px; /* Ubah max-width menjadi lebih kecil */
    margin: 20px auto; /* Margin atas/bawah 20px, dan auto untuk kiri/kanan */
    margin-top: 15px; /* Tambahkan margin-top untuk jarak dari judul */
    border-radius: 8px;
    padding: 20px; /* Padding dalam kontainer */
    background-color: #ffffff; /* Warna latar belakang kontainer putih */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow-x: auto; /* Tambahkan ini untuk mengatasi overflow horizontal */
    display: block; /* Pastikan kontainer biaya adalah elemen blok */
}

footer {
    margin-top: auto; /* Memastikan footer berada di bagian bawah */
}

        .table-responsive {
            margin-bottom: 1.5rem; /* Jarak bawah untuk tabel responsif */
        }

        .table th, .table td {
            white-space: nowrap; /* Mencegah teks di dalam sel tabel membungkus */  
        }

        .table thead th {
            text-align: center; /* Pusatkan secara horizontal */
            vertical-align: middle; /* Pusatkan secara vertikal */
        }
        
        /* Gaya untuk tab yang aktif */
.nav-tabs .nav-link.active {
    background-color: #3F47CC; /* Warna biru untuk latar belakang */
    color: white; /* Warna putih untuk teks */
}

.nav-tabs .nav-link.active:hover {
    color: #ffffff; /* Warna putih untuk teks */
    background: #1F75FE;
}

/* Gaya untuk tab yang tidak aktif */
.nav-tabs .nav-link {
    color: #3F47CC; /* Warna biru untuk teks tab yang tidak aktif */
}

.biaya-container {
    width: 100%;
}

/* Gaya untuk tab saat hover */
.nav-tabs .nav-link:hover {
    background-color: #3F47CC; /* Warna latar belakang saat hover */
    color: #ffffff;
}

        @media (max-width: 768px) {
            .biaya-container {
                width: 90%; /* Lebar kontainer menjadi 90% dari lebar layar */
                padding: 10px; /* Padding yang lebih kecil */
            }

            .table {
                font-size: 14px; /* Ukuran font tabel lebih kecil */
            }

            .table th, .table td {
                padding: 8px; /* Padding yang lebih kecil di dalam sel tabel */
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <?php include 'header.php'; ?>

    <div class="container">
        <!-- Judul dengan teks putih -->
        <h1 class="text-center mb-4" style="color: white; padding: 20px;">
            Rincian Biaya Bimbingan Belajar
        </h1>
        
        <div class="biaya-container">
            <!-- Form Pencarian -->
            <form id="searchForm" method="POST" class="mb-4 mt-3"> <!-- Tambahkan mt-3 di sini -->
    <div class="input-group">
        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Cari berdasarkan Kurikulum atau Pembelajaran" value="<?php echo htmlspecialchars($search); ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </div>
</form>

            <!-- Tab Jenjang Pendidikan -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tk-tab" data-toggle="tab" href="#tk" role="tab" aria-controls="tk" aria-selected="true">TK</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sd-tab" data-toggle="tab" href="#sd" role="tab" aria-controls="sd" aria-selected="false">SD</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="smp-tab" data-toggle="tab" href="#smp" role="tab" aria-controls="smp" aria-selected="false">SMP</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sma-tab" data-toggle="tab" href="#sma" role="tab" aria-controls="sma" aria-selected="false">SMA/K</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="umum-tab" data-toggle="tab" href="#umum" role="tab" aria-controls="umum" aria-selected="false">UMUM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="khusus-tab" data-toggle="tab" href="#khusus" role="tab" aria-controls="khusus" aria-selected="false">KHUSUS</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tk" role="tabpanel" aria-labelledby="tk-tab">
                    <?php displayData($dataTK); ?>
                </div>
                <div class="tab-pane fade" id="sd" role="tabpanel" aria-labelledby="sd-tab">
                    <?php displayData($dataSD); ?>
                </div>
                <div class="tab-pane fade" id="smp" role="tabpanel" aria-labelledby="smp-tab">
                    <?php displayData($dataSMP); ?>
                </div>
                <div class="tab-pane fade" id="sma" role="tabpanel" aria-labelledby="sma-tab">
                    <?php displayData($dataSMA); ?>
                    </div>
                <div class="tab-pane fade" id="umum" role="tabpanel" aria-labelledby="umum-tab">
                    <?php displayData($dataUMUM); ?>
                </div>
                <div class="tab-pane fade" id="khusus" role="tabpanel" aria-labelledby="khusus-tab">
                    <?php displayData($dataKHUSUS); ?>
                </div>
            </div> <!-- Akhir dari div.tab-content -->
        </div> <!-- Akhir dari div.biaya-container -->
    </div> <!-- Akhir dari div.container -->

    

<!-- Skrip JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script> <!-- Menggunakan Bootstrap lokal -->

<style>
    /* Media query untuk mengubah ukuran font pada layar kecil */
    @media (max-width: 576px) {
        h1 {
            font-size: 1.5rem; /* Ukuran font lebih kecil pada layar kecil */
        }
    }
</style>

<script>
$(document).ready(function() {
    // Pencarian otomatis saat mengetik
    $('#searchInput').on('input', function() {
        var search = $(this).val();
        $.ajax({
            url: 'search.php', // Ganti dengan nama file PHP yang akan menangani pencarian
            type: 'POST',
            data: { search: search },
            success: function(data) {
                // Tampilkan hasil pencarian di dalam tab yang sesuai
                $('#myTabContent').html(data);
            }
        });
    });

    // Pencarian saat tombol "Cari" diklik
    $('#searchForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah form dari pengiriman default
        var search = $('#searchInput').val();
        $.ajax({
            url: 'search.php', // Ganti dengan nama file PHP yang akan menangani pencarian
            type: 'POST',
            data: { search: search },
            success: function(data) {
                // Tampilkan hasil pencarian di dalam tab yang sesuai
                $('#myTabContent').html(data);
            }
        });
    });
});
</script>
</div> <!-- Akhir dari .wrapper -->

    <!-- Bagian Footer -->
    <?php include 'footer.php'; ?>
</body>


</body>
</html>

<?php
function displayData($data) {
    if (empty($data)) {
        echo '<p class="text-center mt-2" style="font-size: 16px; font-weight: normal;">Tidak ada data untuk ditampilkan.</p>';
        return;
    }

    // Menampilkan tabel dengan kelas responsif
    echo '<div class="table-responsive">'; // Tambahkan div ini
    echo '<table class="table table-bordered table-hover">';
    echo '<thead>
            <tr>
                <th class="kurikulum-col">Kurikulum</th>
                <th class="pembelajaran-col">Pembelajaran</th>
                <th class="biaya-col">1 Bulan<br>(9x/Bulan)</th>
                <th class="biaya-col">3 Bulan<br>(13x/Bulan)</th>
                <th class="biaya-col">6 Bulan<br>(26x/Bulan)</th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($data as $row) {
        echo '<tr>
                <td class="kurikulum-col">' . htmlspecialchars($row['kurikulum']) . '</td>
                <td class="pembelajaran-col">' . htmlspecialchars($row['pembelajaran']) . '</td>
                <td>Rp. ' . number_format($row['1_bulan'], 2, ',', '.') . '</td>
                <td>Rp. ' . number_format($row['3_bulan'], 2, ',', '.') . '</td>
                <td>Rp. ' . number_format($row['6_bulan'], 2, ',', '.') . '</td>
              </tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Tutup div responsif
}
?>

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