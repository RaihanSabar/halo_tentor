<?php
session_start(); // Memulai session
error_reporting(0); // Menonaktifkan laporan kesalahan

// Mengimpor file koneksi
include 'includes/koneksi.php'; // Pastikan koneksi ke database sudah benar

// Cek jika ada pesan dari session
if (isset($_SESSION['reset_message'])) {
    $reset_message = $_SESSION['reset_message'];
    unset($_SESSION['reset_message']); // Hapus pesan setelah ditampilkan
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); // Cek apakah checkbox di centang

    // Query untuk mengambil data admin berdasarkan username
    $sql = "SELECT id, username, email, password FROM admin WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ); // Ambil satu baris data

    // Cek apakah username ada
    if ($result) {
        // Verifikasi password
        if (password_verify($password, $result->password)) {
            $_SESSION['loggedin'] = true; // Set session login
            $_SESSION['username'] = $username; // Simpan username ke session
            $_SESSION['id'] = $result->id; // Simpan ID ke session

            // Jika "Ingat Saya" dicentang, set cookie
            if ($remember) {
                setcookie("user_login", $username, time() + (10 * 365 * 24 * 60 * 60)); // Cookie username
                setcookie("userpassword", $password, time() + (10 * 365 * 24 * 60 * 60)); // Cookie password
            } else {
                // Jika tidak dicentang, hapus cookie
                if (isset($_COOKIE["user_login"])) {
                    setcookie("user_login", "", time() - 3600); // Hapus cookie username
                }
                if (isset($_COOKIE["userpassword"])) {
                    setcookie("userpassword", "", time() - 3600); // Hapus cookie password
                }
            }

            // Redirect ke index.php setelah login berhasil
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        } else {
            $error_message = "Username atau password salah!";
        }
    } else {
        $error_message = "Username atau password salah!";
    }
}

// Periksa cookie saat memuat halaman
$username = '';
$password = '';
if (isset($_COOKIE['user_login'])) {
    $username = $_COOKIE['user_login'];
}
if (isset($_COOKIE['userpassword'])) {
    $password = $_COOKIE['userpassword'];
}

$conn = null; // Menutup koneksi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        .login-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .login-container img {
    max-width: 100%; /* Memastikan gambar tidak melampaui lebar kontainer */
    height: auto; /* Memastikan tinggi gambar proporsional */
    margin-bottom: 20px; /* Jarak di bawah gambar */
    display: block; /* Menjadikan gambar sebagai blok untuk menghindari masalah dengan elemen inline */
    visibility: visible; /* Pastikan gambar terlihat */
    opacity: 1; /* Pastikan gambar sepenuhnya terlihat */
    transition: opacity 0.3s ease; /* Tambahkan efek transisi jika diperlukan */
}
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .login-container input[type="text"],
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
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            font-size: 12px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .input-group {
    position: relative; /* Membuat posisi relatif untuk elemen anak */
}

.input-group input[type="password"] {
    padding-right: 40px; /* Memberikan ruang untuk ikon */
}

.input-group-text {
    position: absolute; /* Memposisikan ikon di dalam input */
    right: 10px; /* Jarak dari kanan */
    top: 35%; /* Ubah nilai ini untuk mengatur posisi vertikal */
    transform: translateY(-50%); /* Pusatkan secara vertikal */
    cursor: pointer; /* Menunjukkan bahwa ini bisa diklik */
    background: none; /* Menghilangkan latar belakang */
    border: none; /* Menghilangkan border */
    color: #000; /* Mengubah warna ikon jika perlu */
}
    </style>
</head>
<body>
<div class="login-container">
    <h1><i class="fas fa-user"></i> LOGIN ADMIN</h1><br>
    <a href="../index.php">
        <img src="../assets/images/logo.png" alt="Logo" style="max-width: 100%; height: auto; margin-bottom: 20px;">
    </a>
    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['login_message'])): ?>
        <div class="success"><?php echo $_SESSION['login_message']; unset($_SESSION['login_message']); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="username" value="<?php echo htmlspecialchars($username); ?>" required>
        
        <label for="password">Password</label>
        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="password" value="<?php echo htmlspecialchars($password); ?>" required>
            <span class="input-group-text" id="togglePassword" onclick="togglePasswordVisibility()">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        
        <label>
            <input type="checkbox" name="remember" <?php echo (isset($_COOKIE['user_login'])) ? 'checked' : ''; ?>> Ingat Saya
        </label>
        <button type="submit" name="login">LOGIN</button>
    </form>
    <p><a href="lupa_password.php">Lupa Password?</a></p>
</div>
    <div class="footer">
        Copyright © 2024 Halo Tentor | Powered by Halo Tentor
    </div>

    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePassword').querySelector('i');

        // Cek tipe input password
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'; // Ubah menjadi text
            toggleIcon.classList.remove('fa-eye'); // Ganti ikon mata
            toggleIcon.classList.add('fa-eye-slash'); // Ganti ikon mata tertutup
        } else {
            passwordInput.type = 'password'; // Kembalikan ke password
            toggleIcon.classList.remove('fa-eye-slash'); // Ganti ikon mata
            toggleIcon.classList.add('fa-eye'); // Ganti ikon mata terbuka
        }
    }
</script>
</body>
</html>