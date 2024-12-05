<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hero</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">    
    <style>
        .hero-image {
            height: 200px; /* Tinggi gambar di dalam card */
            background-size: cover;
            background-position: center;
        }
        .card {
            margin-bottom: 15px; /* Mengurangi jarak antara card */
        }
        .card-body {
            padding: 10px; /* Mengurangi padding di dalam card */
        }
        
    </style>
</head>

<?php
ob_start(); // Mulai buffering output
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

include 'includes/koneksi.php'; // Sertakan koneksi ke database
include 'includes/header.php'; 
include 'includes/sidebar.php'; // Sertakan sidebar

// Menangani penghapusan gambar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM hero_images WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: manage_hero.php"); // Redirect setelah penghapusan
    exit();
}

// Mengambil semua gambar dari database
$query = "SELECT * FROM hero_images";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil data dari tabel hero_content
$query = "SELECT * FROM hero_content LIMIT 1"; // Ambil satu entri
$stmt = $conn->prepare($query);
$stmt->execute();
$heroContent = $stmt->fetch(PDO::FETCH_ASSOC);

// Menangani pembaruan konten hero
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_hero_content'])) {
    $heroTitle = $_POST['hero_title'];
    $heroDescription = $_POST['hero_description'];

    // Pastikan ID ada dan valid
    if (isset($heroContent['id'])) {
        $updateQuery = "UPDATE hero_content SET title = :title, description = :description WHERE id = :id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':title', $heroTitle);
        $updateStmt->bindParam(':description', $heroDescription);
        $updateStmt->bindParam(':id', $heroContent['id']); // Pastikan ID terikat

        if ($updateStmt->execute()) {
            $message = "Konten hero berhasil diperbarui!";
            // Ambil kembali data terbaru setelah pembaruan
            $stmt->execute();
            $heroContent = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $message = "Error: " . $updateStmt->errorInfo()[2];
        }
    } else {
        $message = "Error: ID konten hero tidak ditemukan.";
    }
}
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
            <h1 class="custom-h1">Kelola Konten Hero</h1>
                <?php if (isset($message)): ?>
                    <div class="alert alert-success text-center" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="hero_title" class="label-custom">Judul</label>
                    <input type="text" class="form-control" id="hero_title" name="hero_title" value="<?php echo htmlspecialchars($heroContent['title']); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="hero_description" class="label-custom">Deskripsi</label>
                    <textarea class="form-control" id="hero_description" name="hero_description" rows="3" required readonly><?php echo htmlspecialchars($heroContent['description']); ?></textarea>
                </div>
                <button type="button" id="edit_button" class="btn btn-secondary" onclick="enableEdit()">Edit</button>
                <button type="submit" name="update_hero_content" class="btn btn-primary" style="display: none;" id="update_button">Perbarui Konten Hero</button>
                <button type="button" id="cancel_button" class="btn btn-danger" style="display: none;" onclick="cancelEdit()">Batal</button>
            </form>
        </div>
    </div>
</div>

                    <h2 class="mt-5 text-center">Daftar Gambar Hero</h2>  
<div class="row">
    <?php foreach ($results as $row): ?>
        <div class="col-12 col-sm-6 col-md-4"> <!-- Menggunakan kelas responsif -->
            <div class="card">
                <div class="hero-image" style="background-image: url('uploads/<?php echo htmlspecialchars($row['image']); ?>'); height: 200px; background-size: cover; background-position: center; cursor: pointer;" 
                     onclick="openModal('<?php echo htmlspecialchars($row['image']); ?>')"></div>
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: black;"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <a href="edit_hero_images.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');">Hapus</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

                    <div class="text-center mt-4">
                        <a href="add_hero_images.php" class="btn btn-success">Tambah Gambar Hero</a>
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
                    <h5 class="modal-title" id="imageModalLabel">Gambar Hero</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Gambar Hero" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
function enableEdit() {
    document.getElementById('hero_title').removeAttribute('readonly');
    document.getElementById('hero_description').removeAttribute('readonly');
    document.getElementById('edit_button').style.display = 'none'; // Sembunyikan tombol Edit
    document.getElementById('cancel_button').style.display = 'inline-block'; // Tampilkan tombol Batal
    document.getElementById('update_button').style.display = 'inline-block'; // Tampilkan tombol Perbarui
}

function cancelEdit() {
    document.getElementById('hero_title').setAttribute('readonly', true);
    document.getElementById('hero_description').setAttribute('readonly', true);
    document.getElementById('edit_button').style.display = 'inline-block'; // Tampilkan tombol Edit
    document.getElementById('cancel_button').style.display = 'none'; // Sembunyikan tombol Batal
    document.getElementById('update_button').style.display = 'none'; // Sembunyikan tombol Perbarui
}

function openModal(imageSrc) {
    document.getElementById('modalImage').src = 'uploads/' + imageSrc;
    $('#imageModal').modal('show'); // Tampilkan modal
}
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

<?php include 'includes/footer.php'; ?>
</html>