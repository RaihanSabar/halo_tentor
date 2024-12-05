<?php 
include 'includes/koneksi.php'; // Sertakan koneksi ke database
include 'includes/header.php'; 

// Cek apakah ID materi ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data materi berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM materi_pelajaran WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $materi = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah data ditemukan
    if (!$materi) {
        echo "<p>Materi tidak ditemukan.</p>";
        exit;
    }
} else {
    echo "<p>ID tidak valid.</p>";
    exit;
}

// Proses form jika ada pengiriman data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tingkat = $_POST['tingkat'];
    $materi_text = $_POST['materi'];

    // Update data materi
    $stmt = $conn->prepare("UPDATE materi_pelajaran SET tingkat = :tingkat, materi = :materi WHERE id = :id");
    $stmt->bindParam(':tingkat', $tingkat);
    $stmt->bindParam(':materi', $materi_text);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Jika update berhasil, tampilkan pesan dan redirect
        echo "<script>
                alert('Materi berhasil diperbarui.');
                window.location.href = 'manage_materi.php';
              </script>";
        exit;
    } else {
        echo "<p>Terjadi kesalahan saat memperbarui materi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi Pelajaran</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS untuk memastikan footer berada di bawah halaman */
        html, body {
    height: 100%; /* Pastikan body dan html memiliki tinggi 100% */
    margin: 0; /* Menghilangkan margin default */
    display: flex;
    flex-direction: column; /* Mengatur arah flex menjadi kolom */
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
<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?> <!-- Menyertakan sidebar -->

        <div class="col-md-10 mt-5 content-container"> <!-- Menggunakan class content-container -->
            <h1 class="text-center mb-4 custom-h1">Edit Materi Pelajaran</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="tingkat" class="label-custom">Tingkat</label>
                            <input type="text" class="form-control" id="tingkat" name="tingkat" value="<?php echo htmlspecialchars($materi['tingkat']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="materi" class="label-custom">Materi</label>
                            <textarea class="form-control" id="materi" name="materi" rows="5" required><?php echo htmlspecialchars($materi['materi']); ?></textarea>
                            <small class="text-primary">Harap gunakan &lt;-- &lt;br&gt; --&gt; untuk berpindah baris (terlihat rapih)</small>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            <a href="manage_materi.php" class="btn btn-secondary">Batal</a>
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