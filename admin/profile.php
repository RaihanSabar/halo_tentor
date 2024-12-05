<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

// Koneksi ke database
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Ambil data admin dari database
$aid = $_SESSION['id']; // Ambil ID admin dari sesi
$sql = "SELECT * FROM admin WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $aid, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_tlp = $_POST['no_tlp'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/"; // Folder untuk menyimpan gambar
    $target_file = $target_dir . basename($image);
    
    // Cek apakah ada gambar yang diupload
    if (!empty($image)) {
        // Pindahkan file gambar ke folder uploads
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        
        // Update dengan gambar
        $sql = "UPDATE admin SET username = :username, nama = :nama, email = :email, no_tlp = :no_tlp, image = :image WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':image', $image);
    } else {
        // Update tanpa mengubah kolom image
        $sql = "UPDATE admin SET username = :username, nama = :nama, email = :email, no_tlp = :no_tlp WHERE id = :id";
        $stmt = $conn->prepare($sql); // Perbaiki di sini
    }

    // Binding parameter yang sama untuk kedua query
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':no_tlp', $no_tlp);
    $stmt->bindParam(':id', $aid, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $success_message = "Profil berhasil diperbarui.";
    } else {
        $error_message = "Gagal memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .main-container {
            margin-left: 0; /* Menghilangkan margin untuk responsivitas */
            padding: 20px;
            width: 100%; /* Pastikan lebar 100% */
            max-width: 800px; /* Atur lebar maksimum kontainer */
            margin: 0 auto; /* Pusatkan kontainer */
        }
        label {
            color: #000; /* Mengatur warna teks label menjadi hitam */
            font-weight: bold;
            font-size: 16px;
        }

        .file-upload-area {
            border-radius: 4px; /* Sudut melengkung */
            background-color: #0000; /* Warna latar belakang */
            display: inline-block; /* Membuat area menyesuaikan dengan konten */
            max-width: 100px;
            max-height: 10px;
        }

        .file-upload-area input[type="file"] {
            cursor: default; /* Ubah kursor saat hover untuk input file */
        }

        .file-name-display {
            display: block;
            width: 300px; /* Lebar untuk tampilan nama file */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-left: 0px;
        }

        #file-name {
            color: #333; /* Ubah warna teks menjadi lebih gelap agar             terlihat */
            font-size: 14px; /* Ukuran font yang lebih kecil */
            margin-left: 10px; /* Jarak antara input file dan nama file */
        }

        @media (min-width: 768px) {
            .main-container {
                margin-left: 500px; /* Memberikan ruang untuk sidebar pada layar besar */
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?> <!-- Menyertakan header.php -->

    <!-- Include Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <div class="container mt-5 main-container">
        <h1 class="text-center">Profil Admin</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($admin['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="no_tlp">Nomor Telepon</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+</span>
                    </div>
                    <input type="text" class="form-control" id="no_tlp" name="no_tlp" value="<?php echo htmlspecialchars($admin['no_tlp']); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="image">Gambar Profil</label>
                <div class="input-group">
                    <?php if (!empty($admin['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($admin['image']); ?>" alt="Gambar Profil" class="img-thumbnail img-fluid" style="width: 100px; height: auto; margin-right: 10px;">
                    <?php else: ?>
                        <img src="default-profile.png" alt="Gambar Profil" class="img-thumbnail img-fluid" style="width: 100px; height: auto; margin-right: 10px;">
                    <?php endif; ?>
                    <div class="file-upload-area">
                        <input type="file" class="form-control-file" id="image" name="image" onchange="updateFileName()">
                        <span id="file-name" class="file-name-display"></span>
                    </div>
                </div>
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Profil</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateFileName() {
            const input = document.getElementById('image');
            const fileNameDisplay = document.getElementById('file-name');

            if (input.files.length > 0) {
                const fileName = input.files[0].name;
                fileNameDisplay.textContent = fileName;
                fileNameDisplay.title = fileName; // Set the title for hover tooltip on the displayed file name
            } else {
                fileNameDisplay.textContent = '';
                fileNameDisplay.title = ''; // Clear title if no file is selected
            }
        }
    </script>
</body>
</html>