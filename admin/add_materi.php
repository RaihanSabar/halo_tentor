<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi Pelajaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container-fluid {
            flex: 1; /* Membuat kontainer utama mengisi ruang yang tersisa */
        }

        .content-container {
            max-width: 800px; /* Atur lebar maksimum sesuai kebutuhan */
            margin: auto; /* Untuk memusatkan kontainer */
        }
    </style>    
</head>
<body>

<?php 
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'includes/koneksi.php'; 
include 'includes/header.php'; 
include 'includes/sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tingkat = $_POST['tingkat'];
    $materi = $_POST['materi'];

    if (!empty($tingkat) && !empty($materi)) {
        $stmt = $conn->prepare("INSERT INTO materi_pelajaran (tingkat, materi) VALUES (:tingkat, :materi)");
        $stmt->bindParam(':tingkat', $tingkat);
        $stmt->bindParam(':materi', $materi);

        if ($stmt->execute()) {
            header("Location: manage_materi.php");
            exit;
        } else {
            $error = "Terjadi kesalahan saat menambahkan materi.";
        }
    } else {
        $error = "Semua field harus diisi.";
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"><?php include 'includes/sidebar.php'; ?></div>
        <div class="col-md-10 mt-5 content-container"> <!-- Menggunakan class content-container -->
            <h1 class="text-center mb-4 custom-h1">Tambah Materi Pelajaran</h1>

            <div class="card">
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="tingkat" class="label-custom">Tingkat</label>
                            <input type="text" class="form-control" id="tingkat" name="tingkat" required>
                        </div>
                        <div class="form-group">
                            <label for="materi" class="label-custom">Materi</label>
                            <textarea class="form-control" id="materi" name="materi" rows="5" required></textarea>
                            <small class="text-primary">Harap gunakan &lt;-- &lt;br&gt; --&gt; untuk berpindah baris (terlihat rapih)</small>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Simpan Materi</button>
                            <a href="manage_materi.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>