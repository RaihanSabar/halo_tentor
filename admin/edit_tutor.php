<?php
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Cek apakah ID tutor ada di URL
if (!isset($_GET['id'])) {
    header('Location: manage_tutors.php');
    exit;
}

$id = $_GET['id'];

// Ambil data tutor berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM tutors WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$tutor = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah data tutor ditemukan
if (!$tutor) {
    header('Location: manage_tutors.php');
    exit;
}

// Proses update data tutor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $university = $_POST['university'];
    $photo = $_FILES['photo']['name'];

    // Jika foto baru diupload, proses upload
    if ($photo) {
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/$photo");
        $stmt = $conn->prepare("UPDATE tutors SET nama = :nama, university = :university, photo = :photo WHERE id = :id");
        $stmt->bindParam(':photo', $photo);
    } else {
        $stmt = $conn->prepare("UPDATE tutors SET nama = :nama, university = :university WHERE id = :id");
    }

    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':university', $university);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: manage_tutors.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tutor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Tutor</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama">Nama Tutor</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($tutor['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label for="university">Asal Universitas</label>
            <input type="text" class="form-control" id="university" name="university" value="<?= htmlspecialchars($tutor['university']) ?>" required>
        </div>
        <div class="form-group">
            <label for="photo">Foto Tutor (kosongkan jika tidak ingin mengubah)</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="manage_tutors.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>