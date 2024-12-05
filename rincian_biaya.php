<?php
// Sertakan koneksi ke database
include 'admin/includes/koneksi.php';

// Inisialisasi variabel pencarian
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Membangun query pencarian
$query = "SELECT * FROM daftar_biaya WHERE ";
$keywords = explode(' ', $search);
$conditions = [];

foreach ($keywords as $keyword) {
    $conditions[] = "(jenjang LIKE :keyword OR kurikulum LIKE :keyword OR pembelajaran LIKE :keyword)";
}

$query .= implode(' AND ', $conditions); // Menggunakan AND agar semua kata kunci harus ada

// Menyiapkan parameter untuk query
$params = [];
foreach ($keywords as $keyword) {
    $params['keyword'] = '%' . $keyword . '%';
}

// Menyiapkan dan mengeksekusi query
$stmt = $conn->prepare($query);
$stmt->execute($params);
$biaya_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Biaya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"> <!-- Bootstrap CSS lokal -->
    <link rel="stylesheet" href="style.css"> <!-- CSS kustom -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Membuat wrapper memiliki tinggi minimal 100% dari viewport */
        }

        .content {
            flex: 1; /* Membuat konten mengambil ruang yang tersedia */
            margin-top: 50px;
        }

        body {
            padding-top: 120px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 8px;
            padding: 20px;
            background-color: #ffffff; /* Warna latar belakang kontainer putih */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #343a40; /* Warna latar belakang header tabel */
            color: white;
        }

        tr {
            background-color: #f8f9fa; /* Warna latar belakang baris tabel */
        }

        tr:hover {
            background-color: #e2e6ea; /* Warna saat hover */
        }

        .biaya-col {
            width: 150px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <?php include 'header.php'; ?>

    <div class="content">
        <div class="container">
            <h1 class="text-center mb-4 text-primary">Rincian Biaya Bimbingan Belajar</h1>

            <!-- Form Pencarian -->
            <form method="POST" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Jenjang, Kurikulum, atau Pembelajaran" value="<?php echo htmlspecialchars($search); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Jenjang</th>
                        <th>Kurikulum</th>
                        <th>Pembelajaran</th>
                        <th class="biaya-col">1 Bulan (9x/Bulan)</th>
                        <th class="biaya-col">3 Bulan (13x/Bulan)</th>
                        <th class="biaya-col">6 Bulan (26x/Bulan)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($biaya_list) > 0): ?>
                        <?php foreach ($biaya_list as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['jenjang']); ?></td>
                                <td><?php echo htmlspecialchars($row['kurikulum']); ?></td>
                                <td><?php echo htmlspecialchars($row['pembelajaran']); ?></td>
                                <td>Rp. <?php echo number_format($row['1_bulan'], 2, ',', '.'); ?></td>
                                <td>Rp. <?php echo number_format($row['3_bulan'], 2, ',', '.'); ?></td>
                                <td>Rp. <?php echo number_format($row['6_bulan'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data biaya yang tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div> <!-- Akhir dari div.content -->

    <!-- Bagian Footer -->
    <?php include 'footer.php'; ?>
</div> <!-- Akhir dari div.wrapper -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script> <!-- Menggunakan Bootstrap lokal -->
</body>
</html>