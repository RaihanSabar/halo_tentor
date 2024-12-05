<?php
// data.php
include 'includes/koneksi.php'; // Sertakan koneksi ke database

// Menghitung jumlah tutor
$tutor_stmt = $conn->prepare("SELECT COUNT(*) FROM tutors");
$tutor_stmt->execute();
$tutor_count = $tutor_stmt->fetchColumn();

// Menghitung jumlah materi
$material_stmt = $conn->prepare("SELECT COUNT(*) FROM materi_pelajaran");
$material_stmt->execute();
$material_count = $material_stmt->fetchColumn();

// Menghitung jumlah biaya
$biaya_stmt = $conn->prepare("SELECT COUNT(*) FROM daftar_biaya");
$biaya_stmt->execute();
$biaya_count = $biaya_stmt->fetchColumn();

// Menghitung jumlah testimoni
$testimonial_stmt = $conn->prepare("SELECT COUNT(*) FROM testimonial");
$testimonial_stmt->execute();
$testimonial_count = $testimonial_stmt->fetchColumn();
?>