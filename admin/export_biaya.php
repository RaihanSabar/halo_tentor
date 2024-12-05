<?php
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Ambil data biaya dari database
$stmt = $conn->query("SELECT * FROM daftar_biaya");
$biaya = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set header untuk file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="daftar_biaya.xls"');

// Tampilkan data dalam format tabel
echo "<table border='1'>
    <tr>
        <th>ID</th>
        <th>Jenjang</th>
        <th>Kurikulum</th>
        <th>Pembelajaran</th>
        <th>1 Bulan</th>
        <th>3 Bulan</th>
        <th>6 Bulan</th>
        <th>Terakhir Diperbarui</th>
    </tr>";

foreach ($biaya as $row) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['jenjang']}</td>
        <td>{$row['kurikulum']}</td>
        <td>{$row['pembelajaran']}</td>
        <td>Rp " . number_format($row['1_bulan'], 2) . "</td>
        <td>Rp " . number_format($row['3_bulan'], 2) . "</td>
        <td>Rp " . number_format($row['6_bulan'], 2) . "</td>
        <td>" . (new DateTime($row['last_updated']))->format('Y-m-d H:i:s') . "</td>
    </tr>";
}

echo "</table>";
exit;
?>