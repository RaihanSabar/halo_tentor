<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Cek apakah ID ada di URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data biaya berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM daftar_biaya WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $biaya = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah data ditemukan
    if (!$biaya) {
        $_SESSION['message'] = "Data tidak ditemukan.";
        header("Location: manage_biaya.php");
        exit;
    }
} else {
    $_SESSION['message'] = "ID tidak valid.";
    header("Location: manage_biaya.php");
    exit;
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $jenjang = $_POST['jenjang'];
    $kurikulum = $_POST['kurikulum'];
    $pembelajaran = $_POST['pembelajaran'];
    $biaya_1_bulan = $_POST['1_bulan'];
    $biaya_3_bulan = $_POST['3_bulan'];
    $biaya_6_bulan = $_POST['6_bulan'];

    // Cek apakah kombinasi sudah ada
    $checkSql = "SELECT * FROM daftar_biaya WHERE jenjang = :jenjang AND kurikulum = :kurikulum AND pembelajaran = :pembelajaran AND id != :id";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(':jenjang', $jenjang);
    $checkStmt->bindParam(':kurikulum', $kurikulum);
    $checkStmt->bindParam(':pembelajaran', $pembelajaran);
    $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $checkStmt->execute();

    // Jika data sudah ada
    if ($checkStmt->rowCount() > 0) {
        $_SESSION['message'] = "Data biaya dengan Jenjang, Kurikulum, dan Pembelajaran tersebut sudah ada.";
    } else {
        // Siapkan statement untuk memperbarui data
        $stmt = $conn->prepare("UPDATE daftar_biaya SET jenjang = :jenjang, kurikulum = :kurikulum, pembelajaran = :pembelajaran, 
            `1_bulan` = :biaya_1_bulan, `3_bulan` = :biaya_3_bulan, `6_bulan` = :biaya_6_bulan, last_updated = NOW() WHERE id = :id");
        
        // Bind parameter
        $stmt->bindParam(':jenjang', $jenjang);
        $stmt->bindParam(':kurikulum', $kurikulum);
        $stmt->bindParam(':pembelajaran', $pembelajaran);
        $stmt->bindParam(':biaya_1_bulan', $biaya_1_bulan);
        $stmt->bindParam(':biaya_3_bulan', $biaya_3_bulan);
        $stmt->bindParam(':biaya_6_bulan', $biaya_6_bulan);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Eksekusi statement
        if ($stmt->execute()) {
            $_SESSION['message'] = "Data biaya berhasil diperbarui.";
            header("Location: manage_biaya.php");
            exit;
        } else {
            $_SESSION['message'] = "Terjadi kesalahan saat memperbarui data biaya.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biaya</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 600px; /* Ukuran maksimum kontainer */
            padding: 20px; /* Menambahkan padding */
            margin: auto; /* Memusatkan kontainer */
        }
        .text-center {
            margin-top: 20px; /* Menambahkan margin atas untuk tombol */
        }
        .label-custom {
            font-weight: bold; /* Menebalkan label */
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
                <h1 class="custom-h1">Edit Biaya</h1>
                
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
                            <!-- Form untuk mengedit biaya -->
                            <form method="POST" class="mb-4">
                                <div class="form-group">
                                    <label for="jenjang" class="label-custom">Jenjang</label>
                                    <input type="text" class="form-control" id="jenjang" name="jenjang" value="<?php echo htmlspecialchars($biaya['jenjang']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kurikulum" class="label-custom">Kurikulum</label>
                                    <input type="text" class="form-control" id="kurikulum" name="kurikulum" value="<?php echo htmlspecialchars($biaya['kurikulum']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pembelajaran" class="label-custom">Pembelajaran</label>
                                    <select class="form-control" id="pembelajaran" name="pembelajaran" required>
                                        <option value="" disabled>Pilih Pembelajaran</option>
                                        <option value="OFFLINE" <?php echo ($biaya['pembelajaran'] === 'OFFLINE') ? 'selected' : ''; ?>>OFFLINE</option>
                                        <option value="ONLINE" <?php echo ($biaya['pembelajaran'] === 'ONLINE') ? 'selected' : ''; ?>>ONLINE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="1_bulan" class="label-custom">Biaya 1 Bulan</label>
                                    <input type="number" class="form-control" id="1_bulan" name="1_bulan" value="<?php echo htmlspecialchars($biaya['1_bulan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="3_bulan" class="label-custom">Biaya 3 Bulan</label>
                                    <input type="number" class="form-control" id="3_bulan" name="3_bulan" value="<?php echo htmlspecialchars($biaya['3_bulan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="6_bulan" class="label-custom">Biaya 6 Bulan</label>
                                    <input type="number" class="form-control" id="6_bulan" name="6_bulan" value="<?php echo htmlspecialchars($biaya['6_bulan']); ?>" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                    <a href="manage_biaya.php" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?> <!-- Sertakan footer -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>