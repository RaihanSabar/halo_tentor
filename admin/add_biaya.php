<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Menangani penambahan biaya
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_biaya'])) {
    $jenjang = $_POST['jenjang'];
    $kurikulum = $_POST['kurikulum'];
    $pembelajaran = $_POST['pembelajaran'];
    $biaya1_bulan = $_POST['1_bulan'];
    $biaya3_bulan = $_POST['3_bulan'];
    $biaya6_bulan = $_POST['6_bulan'];

    // Query untuk mengecek apakah data sudah ada
    $checkSql = "SELECT * FROM daftar_biaya WHERE jenjang = ? AND kurikulum = ? AND pembelajaran = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(1, $jenjang);
    $checkStmt->bindParam(2, $kurikulum);
    $checkStmt->bindParam(3, $pembelajaran);
    $checkStmt->execute();

    // Jika data sudah ada
    if ($checkStmt->rowCount() > 0) {
        $_SESSION['message'] = "Data biaya dengan Jenjang, Kurikulum, dan Pembelajaran tersebut sudah ada.";
    } else {
        // Query untuk menambahkan biaya
        $sql = "INSERT INTO daftar_biaya (jenjang, kurikulum, pembelajaran, `1_bulan`, `3_bulan`, `6_bulan`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $jenjang);
        $stmt->bindParam(2, $kurikulum);
        $stmt->bindParam(3, $pembelajaran);
        $stmt->bindParam(4, $biaya1_bulan);
        $stmt->bindParam(5, $biaya3_bulan);
        $stmt->bindParam(6, $biaya6_bulan);
        $stmt->execute();

        // Set session flash message
        $_SESSION['message'] = "Biaya telah berhasil ditambahkan!";
        
        // Redirect ke halaman manage_biaya setelah penambahan
        header("Location: manage_biaya.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Biaya</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-group {
            margin-bottom: 1rem; /* Mengurangi jarak antar elemen */
        }
        .container {
            max-width: 600px; /* Ukuran maksimum kontainer */
            padding: 20px; /* Menambahkan padding */
            margin: auto; /* Memusatkan kontainer */
        }
        .text-center {
            margin-top: 20px; /* Menambahkan margin atas untuk tombol */
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <!-- Konten Utama -->
            <div class="col-md-10">
                <h1 class="custom-h1">Tambah Biaya</h1>
                
                <!-- Menampilkan pesan jika ada -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
                        ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="container mt-5">
                    <div class="card">
                        <div class="card-body">
                            <!-- Form untuk menambah biaya -->
                            <form method="POST" class="mb-4">
                                <div class="form-group">
                                    <label for="jenjang" class="label-custom">Jenjang</label>
                                    <input type="text" class="form-control" id="jenjang" name="jenjang" required>
                                </div>
                                <div class="form-group">
                                    <label for="kurikulum" class="label-custom">Kurikulum</label>
                                    <input type="text" class="form-control" id="kurikulum" name="kurikulum" required>
                                </div>
                                <div class="form-group">
                                    <label for="pembelajaran" class="label-custom">Pembelajaran</label>
                                    <select class="form-control" id="pembelajaran" name="pembelajaran" required>
                                        <option value="" disabled selected>Pilih Pembelajaran</option>
                                        <option value="OFFLINE">OFFLINE</option>
                                        <option value="ONLINE">ONLINE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="1_bulan" class="label-custom">Biaya 1 Bulan</label>
                                    <input type="text" class="form-control" id="1_bulan" name="1_bulan" required oninput="formatCurrency(this)">
                                </div>
                                <div class="form-group">
                                    <label for="3_bulan"class="label-custom" >Biaya 3 Bulan</label>
                                    <input type="text" class="form-control" id="3_bulan" name="3_bulan" required oninput="formatCurrency(this)">
                                </div>
                                <div class="form-group">
                                    <label for="6_bulan" class="label-custom">Biaya 6 Bulan</label>
                                    <input type="text" class="form-control" id="6_bulan" name="6_bulan" required oninput="formatCurrency(this)">
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="add_biaya" class="btn btn-primary">Tambah Biaya</button>
                                    <a href="manage_biaya.php" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?> <!-- Sertakan footer -->

    <script>
        function formatCurrency(input) {
            // Menghapus karakter non-digit
            let value = input.value.replace(/[^0-9]/g, '');
            // Hanya format jika panjang value >= 4
            if (value.length >= 4) {
                // Format menjadi angka dengan pemisah ribuan
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            // Set nilai input
            input.value = value;
        }
    </script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>