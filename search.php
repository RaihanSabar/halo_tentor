<?php
include 'admin/includes/koneksi.php'; // Sertakan koneksi ke database

// Inisialisasi variabel pencarian
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Menyiapkan array untuk keywords
$keywords = [];
if ($search) {
    $keywords = explode(' ', $search);
}

// Fungsi untuk mengambil data berdasarkan jenjang
function getDataByJenjang($conn, $jenjang, $search, $keywords) {
    $query = "SELECT * FROM daftar_biaya WHERE jenjang = :jenjang";
    $params = ['jenjang' => $jenjang];

    if (!empty($search) && strlen($search) >= 1) {
        $conditions = [];
        foreach ($keywords as $index => $keyword) {
            $conditions[] = "(kurikulum LIKE :keyword$index OR pembelajaran LIKE :keyword$index)";
            $params["keyword$index"] = '%' . $keyword . '%';
        }
        $query .= " AND " . implode(' AND ', $conditions);
    }

    $query .= " ORDER BY urutan ASC"; // Mengurutkan berdasarkan kolom urutan

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil data untuk setiap jenjang
$dataTK = getDataByJenjang($conn, 'TK', $search, $keywords);
$dataSD = getDataByJenjang($conn, 'SD', $search, $keywords);
$dataSMP = getDataByJenjang($conn, 'SMP', $search, $keywords);
$dataSMA = getDataByJenjang($conn, 'SMA/K', $search, $keywords);
$dataUMUM = getDataByJenjang($conn, 'UMUM', $search, $keywords);
$dataKHUSUS = getDataByJenjang($conn, 'KHUSUS', $search, $keywords);

// Tampilkan hasil pencarian
echo '<div class="tab-content" id="myTabContent">';
echo '<div class="tab-pane fade show active" id="tk" role="tabpanel" aria-labelledby="tk-tab">';
displayData($dataTK);
echo '</div>';
echo '<div class="tab-pane fade" id="sd" role="tabpanel" aria-labelledby="sd-tab">';
displayData($dataSD);
echo '</div>';
echo '<div class="tab-pane fade" id="smp" role="tabpanel" aria-labelledby="smp-tab">';
displayData($dataSMP);
echo '</div>';
echo '<div class="tab-pane fade" id="sma" role="tabpanel" aria-labelledby="sma-tab">';
displayData($dataSMA);
echo '</div>';
echo '<div class="tab-pane fade" id="umum" role="tabpanel" aria-labelledby="umum-tab">';
displayData($dataUMUM);
echo '</div>';
echo '<div class="tab-pane fade" id="khusus" role="tabpanel" aria-labelledby="khusus-tab">';
displayData($dataKHUSUS);
echo '</div>';
echo '</div>'; // Akhir dari div.tab-content

// Fungsi untuk menampilkan data
function displayData($data) {
    if (empty($data)) {
        echo '<p class="text-center">Tidak ada data untuk ditampilkan.</p>';
        return;
    }

    // Menampilkan tabel dengan kelas responsif
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover">';
    echo '<thead>
            <tr>
                <th class="kurikulum-col">Kurikulum</th>
                <th class="pembelajaran-col">Pembelajaran</th>
                <th class="biaya-col">1 Bulan<br>(9x/Bulan)</th>
                <th class="biaya-col">3 Bulan<br>(13x/Bulan)</th>
                <th class="biaya-col">6 Bulan<br>(26x/Bulan)</th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($data as $row) {
        echo '<tr>
                <td class="kurikulum-col">' . htmlspecialchars($row['kurikulum']) . '</td>
                <td class="pembelajaran-col">' . htmlspecialchars($row['pembelajaran']) . '</td>
                <td>Rp. ' . number_format($row['1_bulan'], 2, ',', '.') . '</td>
                <td>Rp. ' . number_format($row['3_bulan'], 2, ',', '.') . '</td>
                <td>Rp. ' . number_format($row['6_bulan'], 2, ',', '.') . '</td>
              </tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Tutup div responsif
}
?>