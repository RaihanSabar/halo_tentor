<?php
session_start();
include 'includes/koneksi.php'; // Menghubungkan ke database

// Cek jika nomor telepon ada di query string
if (!isset($_GET['no_tlp'])) {
    header('Location: lupa_password.php'); // Jika tidak ada nomor telepon, kembali ke halaman lupa password
    exit();
}

$no_tlp = $_GET['no_tlp'];

// Cek apakah nomor telepon ada di database
$stmt = $conn->prepare("SELECT * FROM admin WHERE no_tlp = :no_tlp");
$stmt->bindParam(':no_tlp', $no_tlp);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    // Jika nomor telepon tidak ditemukan
    $_SESSION['reset_message'] = "Nomor telepon tidak valid.";
    header('Location: lupa_password.php'); // Kembali ke halaman lupa password
    exit();
}

// Cek apakah link reset password sudah kadaluarsa
$created_at = $result['reset_token_created_at'];
$created_time = strtotime($created_at);
$current_time = time();
$time_difference = $current_time - $created_time;

if ($time_difference > 300) { // 300 detik = 5 menit
    $_SESSION['reset_message'] = "Link reset password telah kadaluarsa. Silakan mulai dari halaman lupa password.";
    header('Location: lupa_password.php'); // Kembali ke halaman lupa password
    exit();
}

// Proses reset password
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password baru dan konfirmasi password cocok
    if ($new_password !== $confirm_password) {
        $_SESSION['reset_message'] = "Password dan konfirmasi password tidak cocok.";
        header('Location: lupa_password.php'); // Kembali ke halaman lupa password
        exit();
    } else {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database
        $query = "UPDATE admin SET password = :password, reset_token_created_at = NULL WHERE no_tlp = :no_tlp"; // Reset timestamp
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':no_tlp', $no_tlp);

        if ($stmt->execute()) {
            $_SESSION['reset_message'] = "Password berhasil direset. Silakan login dengan password baru Anda.";
            header('Location: login.php'); // Arahkan ke halaman login
            exit();
        } else {
            $_SESSION['reset_message'] = "Terjadi kesalahan saat mereset password.";
            header('Location: lupa_password.php'); // Kembali ke halaman lupa password
            exit();
        }

        $stmt->closeCursor();
        $conn = null; // Menutup koneksi
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        html, body {
            height: 100%; /* Pastikan html dan body memiliki tinggi 100% */
            margin: 0; /* Menghapus margin default */
            font-family: 'Roboto', sans-serif; /* Menggunakan font Roboto */
        }

        body {
            display: flex; /* Menggunakan flexbox */
            flex-direction: column; /* Mengatur arah flex menjadi kolom */
            justify-content: center; /* Mengatur konten agar berada di tengah */
            align-items: center; /* Mengatur konten agar berada di tengah secara horizontal */
            background-color: #1F509A; /* Warna latar belakang */
        }
        .login-container {
            background-color: #f0f4ff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .login-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #000;
        }
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #000;
        }
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .login-container button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>RESET PASSWORD</h2><br>
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="new_password">Password Baru:</label>
            <input type="password" name="new_password" required>
            
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit">Reset Password</button>
        </form>
    </div>
    <div class="footer">
        Copyright © 2024 Halo Tentor | Powered by Halo Tentor
    </div>
</body>
</html>