<?php
session_start(); // Memulai session
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Cek apakah ID testimoni ada di URL
if (!isset($_GET['id'])) {
    header('Location: manage_testimonials.php');
    exit;
}

$id = $_GET['id'];

// Ambil data testimoni berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM testimonial WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$testimonial = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah data testimoni ditemukan
if (!$testimonial) {
    header('Location: manage_testimonials.php');
    exit;
}

// Proses update data testimoni
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $image = $_FILES['image']['name'];

    // Jika gambar baru diupload, proses upload
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$image");
        $stmt = $conn->prepare("UPDATE testimonial SET title = :title, image = :image WHERE id = :id");
        $stmt->bindParam(':image', $image);
    } else {
        $stmt = $conn->prepare("UPDATE testimonial SET title = :title WHERE id = :id");
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $_SESSION['message'] = "Testimoni berhasil diperbarui.";
    header('Location: manage_testimonials.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Testimoni</title>
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

        .img-preview {
            width: auto; /* Lebar otomatis */
            height: auto; /* Tinggi otomatis */
            max-width: 5cm; /* Lebar maksimum 5 cm */
            max-height: 10cm; /* Tinggi maksimum 10 cm */
            object-fit: cover; /* Memastikan gambar tidak terdistorsi */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?> <!-- Menyertakan sidebar -->

        <div class="col-md-10 mt-5 content-container"> <!-- Menggunakan class content-container -->
            <h1 class="text-center mb-4 custom-h1">Edit Testimoni</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="label-custom">Judul Testimoni</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($testimonial['title']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="image" class="label-custom">Gambar Testimoni (kosongkan jika tidak ingin mengubah)</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                            <img src="uploads/<?= htmlspecialchars($testimonial['image']) ?>" alt="Gambar Testimoni" class="img-preview mt-2" data-toggle="modal" data-target="#imageModal">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            <a href="manage_testimonials.php" class="btn btn-secondary">Kembali</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Gambar Testimoni</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="uploads/<?= htmlspecialchars($testimonial['image']) ?>" alt="Gambar Testimoni" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Menambahkan event listener untuk input file
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Menampilkan gambar yang dipilih di img-preview
                const imgPreview = document.querySelector('.img-preview');
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block'; // Menampilkan gambar

                // Menampilkan gambar di modal
                const modalImage = document.getElementById('modalImage');
                modalImage.src = e.target.result; // Mengubah src modal dengan gambar yang dipilih
                $('#imageModal').modal('show'); // Menampilkan modal
            }
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>