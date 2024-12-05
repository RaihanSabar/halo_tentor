<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Inisialisasi variabel pencarian
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Membangun query pencarian
$query = "SELECT * FROM daftar_biaya";
$params = [];

// Jika ada pencarian, tambahkan kondisi WHERE
if ($search !== '') {
    $query .= " WHERE ";
    $keywords = explode(' ', $search);
    $conditions = [];
    $keywordParams = [];

    foreach ($keywords as $index => $keyword) {
        $conditions[] = "(jenjang LIKE :keyword_" . $index . " OR kurikulum LIKE :keyword_" . $index . " OR pembelajaran LIKE :keyword_" . $index . ")";
        $keywordParams['keyword_' . $index] = '%' . $keyword . '%';
    }

    $query .= implode(' AND ', $conditions); // Menggunakan AND agar semua kata kunci harus ada
    $params = array_merge($params, $keywordParams);
}

// Ambil data biaya dari database
$stmt = $conn->prepare($query); // Siapkan query
$stmt->execute($params); // Eksekusi dengan parameter
$biaya = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi array untuk mengelompokkan data
$groupedBiaya = [];

// Mengelompokkan biaya berdasarkan jenjang
foreach ($biaya as $row) {
    $groupedBiaya[$row['jenjang']][] = $row; // Menambahkan biaya ke array berdasarkan jenjang
}

// Menyusun ulang data berdasarkan urutan yang diinginkan
$orderedJenjang = ['TK', 'SD', 'SMP', 'SMA/K', 'UMUM', 'Khusus'];
$orderedGroupedBiaya = [];
foreach ($orderedJenjang as $jenjang) {
    if (isset($groupedBiaya[$jenjang])) {
        $orderedGroupedBiaya[$jenjang] = $groupedBiaya[$jenjang];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<?php include 'includes/header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Biaya</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .table th, .table td {
            white-space: nowrap;
        }
        .table .biaya-col {
            width: 150px;
        }
        .table .last-updated-col {
            width: 80px;
        }
        .search-input {
            width: 400px !important; /* Atur lebar search bar */
        }
        @media (max-width: 768px) {
            .search-input {
                width: 100% !important; /* Lebar penuh di perangkat kecil */
            }
        }
        .title-container {
            text-align: center; /* Pusatkan judul */
            margin-bottom: 20px; /* Jarak bawah untuk judul */
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="col-md-9 offset-md-3"> <!-- Menggeser konten ke kanan sesuai dengan sidebar -->
            <div class="d-flex justify-content-center"> <!-- Pusatkan konten -->
                <div style="width: 100%; max-width: 1200px;"> <!-- Atur lebar maksimum sesuai kebutuhan -->
                    <!-- Judul -->
                    <div class="title-container">
                    <h1 class="custom-h1">Kelola Biaya</h1>
                    </div>

                    <!-- Wrapper untuk tombol Ekspor -->
                    <div class="d-flex justify-content-end mb-2">
                        <a href="export_biaya.php" class="btn btn-success" title="Ekspor ke Excel">
                            <i class="fas fa-file-excel"></i> Ekspor ke Excel
                        </a>
                    </div>

                    <!-- Form Pencarian -->
<div class="d-flex justify-content-end align-items-center mb-3">
    <form method="POST" class="mb-0">
        <div class="input-group">
            <input type="text" name="search" class="form-control search-input" placeholder="Cari berdasarkan Jenjang, Kurikulum, atau Pembelajaran" value="<?php echo htmlspecialchars($search); ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </div>
    </form>
</div>

                    <!-- Menampilkan pesan jika ada -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php
                            echo $_SESSION['message'];
                            unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Wrapper untuk tabel -->
                    <div class="d-flex justify-content-center">
                        <div style="width: 100%;"> <!-- Atur lebar sesuai kebutuhan -->
                            <?php foreach ($orderedGroupedBiaya as $jenjang => $rows): ?>
                                <h3><?php echo htmlspecialchars($jenjang); ?></h3> <!-- Tampilkan Jenjang -->
                                <div class="table-responsive"> <!-- Tambahkan kelas table-responsive -->
                                    <table class="table table-bordered" id="biayaTable">
                                        <thead>
                                            <tr>
                                                <th>Kurikulum</th>
                                                <th>Pembelajaran</th>
                                                <th class="biaya-col">1 Bulan</th>
                                                <th class="biaya-col">3 Bulan</th>
                                                <th class="biaya-col">6 Bulan</th>
                                                <th class="last-updated-col">Terakhir <br>Diperbarui</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                       <?php foreach ($rows as $row): ?>
                                                <tr data-id="<?php echo $row['id']; ?>">
                                                    <td><?php echo htmlspecialchars($row['kurikulum']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['pembelajaran']); ?></td>
                                                    <td class="biaya-col">Rp <?php echo number_format($row['1_bulan'], 2); ?></td>
                                                    <td class="biaya-col">Rp <?php echo number_format($row['3_bulan'], 2); ?></td>
                                                    <td class="biaya-col">Rp <?php echo number_format($row['6_bulan'], 2); ?></td>
                                                    <td class="last-updated-col">
                                                        <?php 
                                                        $lastUpdated = new DateTime($row['last_updated']);
                                                        echo $lastUpdated->format('Y-m-d') . '<br>' . $lastUpdated->format('H:i:s'); 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="edit_biaya.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                                        <a href="delete_biaya.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?');">Hapus</a>
                                                        <button class="btn btn-info btn-move-up" title="Pindah ke atas"><i class="fas fa-arrow-up"></i></button>
                                                        <button class="btn btn-info btn-move-down" title="Pindah ke bawah"><i class="fas fa-arrow-down"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div> <!-- Tutup div.table-responsive -->
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <a href="add_biaya.php" class="btn btn-primary" style="background-color: #28A745">Tambah Biaya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function updateOrderInDatabase() {
            var orderData = [];
            $('#biayaTable tbody tr').each(function(index) {
                var id = $(this).data('id');
                orderData.push({ id: id, order: index + 1 });
            });

            $.ajax({
                url: 'update_order.php', // URL ke file PHP yang mengupdate urutan
                type: 'POST', // Metode pengiriman
                data: { order: orderData }, // Data yang dikirim
                success: function(response) {
                    console.log(response); // Untuk debugging
                },
                error: function(xhr, status, error) {
                    console.error(error); // Untuk debugging
                }
            });
        }

        // Fungsi untuk memindahkan baris ke atas
        $('.btn-move-up').click(function() {
            var row = $(this).closest('tr');
            var prevRow = row.prev('tr');
            if (prevRow.length) {
                row.insertBefore(prevRow);
                updateOrderInDatabase(); // Update urutan di database
            }
        });

        // Fungsi untuk memindahkan baris ke bawah
        $('.btn-move-down').click(function() {
            var row = $(this).closest('tr');
            var nextRow = row.next('tr');
            if (nextRow.length) {
                row.insertAfter(nextRow);
                updateOrderInDatabase(); // Update urutan di database
            }
        });
    });
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>