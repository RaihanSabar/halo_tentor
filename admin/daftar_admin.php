<?php
// includes/koneksi.php
$host = 'localhost'; // Ganti dengan host database Anda
$db = 'db_halotentor'; // Ganti dengan nama database Anda
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

// Proses pendaftaran
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $nama = $_POST['nama']; // Menambahkan field nama
    $email = $_POST['email'];
    $no_tlp = $_POST['no_tlp']; // Menambahkan field no_tlp
    $password = $_POST['password'];

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $message = "Username sudah terdaftar!";
        $error = true;
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan ke database
        $sql = "INSERT INTO admin (username, nama, email, no_tlp, password) VALUES (:username, :nama, :email, :no_tlp, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':nama', $nama); // Mengikat field nama
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':no_tlp', $no_tlp); // Mengikat field no_tlp
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            $message = "Pendaftaran berhasil! Silakan login.";
            $error = false;
        } else {
            $message = "Pendaftaran gagal! Silakan coba lagi.";
            $error = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Admin</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://placehold.co/1920x1080') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container input[type="email"] {
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
        .login-container button:hover {
            background-color: #0056b3;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color:            #f8f9fa;
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
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Pendaftaran Admin</h1>
        <?php if (isset($message)): ?>
            <div class="<?php echo isset($error) ? 'error' : 'success'; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="no_tlp" placeholder="Nomor Telepon" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Daftar</button>
        </form>
    </div>
    <div class="footer">
        &copy; <?php echo date("Y"); ?> Halotentor. All Rights Reserved.
    </div>
</body>
</html>