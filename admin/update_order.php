<?php
session_start();
include 'includes/koneksi.php'; // Sertakan koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderData = $_POST['order'];

    if (!empty($orderData) && is_array($orderData)) {
        foreach ($orderData as $item) {
            $id = intval($item['id']);
            $order = intval($item['order']);

            // Update urutan di database
            $stmt = $conn->prepare("UPDATE daftar_biaya SET urutan = :order WHERE id = :id");
            $stmt->execute(['order' => $order, 'id' => $id]);
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid order data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>