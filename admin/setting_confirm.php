<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

// Koneksi ke database
include 'includes/koneksi.php'; // Sertakan koneksi ke database

$aid = $_SESSION['id']; // Ambil ID admin dari sesi

// Inisialisasi jumlah percobaan jika belum ada
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

// Cek jika sudah mencapai batas percobaan
if ($_SESSION['attempts'] >= 5) {
    header("Location: lupa_password.php"); // Arahkan ke halaman lupa password
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];

    // Ambil password admin dari database
    $sql = "SELECT password FROM admin WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $aid, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password saat ini
    if (password_verify($current_password, $admin['password'])) {
        // Jika password benar, reset jumlah percobaan dan simpan status di sesi
        $_SESSION['attempts'] = 0; // Reset attempts
        $_SESSION['password_confirmed'] = true; // Set status konfirmasi
        header("Location: settings.php");
        exit;
    } else {
        $_SESSION['attempts']++; // Tambah jumlah percobaan
        $error_message = "Password saat ini salah. Percobaan ke-" . $_SESSION['attempts'] . " dari 5.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 300px; /* Atur lebar maksimum kotak menjadi lebih kecil */
            margin: auto; /* Pusatkan kotak */
        }
        body {
            margin: 0; /* Menghapus margin default */
            padding: 0; /* Menghapus padding default */
            display: flex;
            flex-direction: column; /* Mengatur layout menjadi kolom */
            min-height: 100vh; /* Memastikan body minimal setinggi viewport */
        }
        .wrapper {
            flex: 1; /* Membuat wrapper mengisi ruang yang tersisa */
            display: flex;
            flex-direction: column; /* Mengatur layout menjadi kolom */
            justify-content: center; /* Pusatkan konten secara vertikal */
        }
    </style>
</head>
<body>
    <!-- Include Headbar -->
    <?php include 'includes/header.php'; ?>

    <!-- Include Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <div class="wrapper">
        <div class="setting-container mt-5 main-container">
            <h1 class="text-center">Konfirmasi Password</h1>
            
            <div class="form-container">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Konfirmasi</button>
                </form>

                <!-- Tombol Lupa Password -->
                <div class="text-center mt-3">
                    <a href="lupa_password.php" class="btn btn-link">Lupa Password?</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>