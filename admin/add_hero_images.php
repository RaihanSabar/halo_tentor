<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Gambar Hero</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">    
    <style>
        /* CSS untuk memastikan footer berada di bagian bawah */
        html, body {
            height: 100%; /* Mengatur tinggi 100% */
            margin: 0; /* Menghilangkan margin default */
            display: flex; /* Menggunakan flexbox */
            flex-direction: column; /* Mengatur arah kolom */
        }

        .container-fluid {
            flex: 1; /* Mengisi sisa ruang */
        }

        footer {
            background-color: #f8f9fa; /* Warna latar belakang footer */
            padding: 10px; /* Padding untuk footer */
            text-align: center; /* Rata tengah */
        }

        .image-container {
            position: relative;
            margin-right: 20px;
        }

        .preview-container {
            padding: 10px;
            position: relative;
            margin-left: 25px;
            margin-top: -30px;
            display: none;
        }
    </style>
</head>

<?php
ob_start(); // Memulai output buffering
session_start();
include 'includes/koneksi.php'; // Sertakan koneksi ke database
include 'includes/header.php'; 
include 'includes/sidebar.php'; // Sertakan sidebar

// Menangani penambahan gambar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_image'])) {
    $title = $_POST['title'];
    
    // Menangani unggahan file
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $fileName;

        // Pindahkan file ke direktori tujuan
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Menyimpan informasi gambar ke database
            $query = "INSERT INTO hero_images (title, image) VALUES (:title, :image)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':image', $fileName);

            if ($stmt->execute()) {
                // Redirect ke halaman manage_hero.php setelah sukses
                header("Location: manage_hero.php");
                exit(); // Pastikan untuk menghentikan eksekusi skrip setelah redirect
            } else {
                $message = "Error: " . $stmt->errorInfo()[2];
            }
        } else {
            $message = "Error: Gagal mengunggah file.";
        }
    } else {
        $message = "Error: Tidak ada file yang diunggah.";
    }
}
ob_end_flush(); // Mengakhiri output buffering
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <!-- Konten Utama -->
            <div class="col-md-10">
                <h1 class="custom-h1">Tambah Gambar Hero</h1>
                <?php if (isset($message)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="container mt-5">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title" class="label-custom">Judul</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                <div class="form-group">
                                    <label for="image" class="label-custom">Unggah Gambar</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                                </div>

                                <div class="preview-container" id="preview-container">
                                    <small class="form-text text-muted">Gambar yang dipilih:</small>
                                    <img id="preview" src="" alt="Preview Gambar" style="height: 5cm; width: auto; display: none; border: 1px solid #ccc; border-radius: 4px;">
                                </div>

                                <button type="submit" name="add_image" class="btn btn-primary">Tambah Gambar</button>
                                <a href="manage_hero.php" class="btn btn-secondary">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('preview-container');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Tampilkan gambar preview
                previewContainer.style.display = 'block'; // Tampilkan kontainer gambar yang dipilih
            }

            if (file) {
                reader.readAsDataURL(file); // Membaca file sebagai URL data
            } else {
                preview.src = ''; // Reset jika tidak ada file
                preview.style.display = 'none'; // Sembunyikan gambar preview
                previewContainer.style.display = 'none'; // Sembunyikan kontainer gambar yang dipilih
            }
        }
    </script>
</body>

<?php include 'includes/footer.php'; ?>
</html>