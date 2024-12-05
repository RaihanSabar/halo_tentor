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

// Cek apakah password telah dikonfirmasi
if (!isset($_SESSION['password_confirmed']) || $_SESSION['password_confirmed'] !== true) {
    header("Location: setting_confirm.php"); // Arahkan kembali ke konfirmasi password
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ambil password admin dari database
    $sql = "SELECT password FROM admin WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $aid, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password saat ini
    if (password_verify($current_password, $admin['password'])) {
        // Cek apakah password baru dan konfirmasi password cocok
        if ($new_password === $confirm_password) {
            // Cek apakah password baru sama dengan password saat ini
            if ($new_password === $current_password) {
                $error_message = "Password baru tidak boleh sama dengan password saat ini.";
            } else {
                // Hash password baru
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password di database
                $update_sql = "UPDATE admin SET password = :new_password WHERE id = :id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(':new_password', $hashed_password, PDO::PARAM_STR);
                $update_stmt->bindParam(':id', $aid, PDO::PARAM_INT);

                if ($update_stmt->execute()) {
                    $success_message = "Password berhasil diperbarui.";
                } else {
                    $error_message = "Terjadi kesalahan saat memperbarui password. Silakan coba lagi.";
                }
            }
        } else {
            $error_message = "Password baru dan konfirmasi password tidak cocok.";
        }
    } else {
        $error_message = "Password saat ini salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        
        .form-container {
            max-width: 300px; /* Atur lebar maksimum kotak menjadi lebih kecil */
            margin: auto; /* Pusatkan kotak */
        }
        label {
            display: block; /* Pastikan label ditampilkan sebagai blok */
            margin-bottom: 0.5rem; /* Jarak antara label dan input */
            color: black;
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
        <h1 class="text-center">Pengaturan Password</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="current_password">Password Saat Ini</label>
            <div class="input-group">
                <input type="password" class="form-control" id="current_password" name="current_password" required>
                <div class="input-group-append">
                    <span class="input-group-text" onclick="togglePasswordVisibility('current_password', this)"><i class="fas fa-eye"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="new_password">Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <div class="input-group-append">
                    <span class="input-group-text" onclick="togglePasswordVisibility('new_password', this)"><i class="fas fa-eye"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <div class="input-group-append">
                    <span class="input-group-text" onclick="togglePasswordVisibility('confirm_password', this)"><i class="fas fa-eye"></i></span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Perbarui Password</button>
    </form>
</div>
</div>

<!-- Include Footer -->
<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
function togglePasswordVisibility(inputId, icon) {
    var input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text"; // Ubah ke teks
        icon.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Ganti ikon
    } else {
        input.type = "password"; // Kembalikan ke password
        icon.innerHTML = '<i class="fas fa-eye"></i>'; // Ganti ikon kembali
    }
}
</script>
</body>
</html>