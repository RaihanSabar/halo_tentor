<?php
session_start(); // Memulai session
include 'includes/koneksi.php'; // Pastikan Anda memiliki file ini untuk koneksi ke database

// Cek jika ada pesan kesalahan dari session
if (isset($_SESSION['reset_message'])) {
    $reset_message = $_SESSION['reset_message'];
    unset($_SESSION['reset_message']); // Hapus pesan setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Admin</title>
    <style>
        html, body {
    height: 100%; /* Pastikan html dan body memiliki tinggi 100% */
    margin: 0; /* Menghapus margin default */
    font-family: 'Roboto', sans-serif; /* Menggunakan font Roboto */
}

body {
    display: flex; /* Menggunakan flexbox */
    flex-direction: column; /* Mengatur arah flex menjadi kolom */
    justify-content: center; /* Mengatur konten agar berada di tengah */
    align-items: center; /* Mengatur konten agar berada di tengah secara horizontal */
    background-color: #1F509A; /* Warna latar belakang */
}
        .reset-container {
            background-color: #f0f4ff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .reset-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #000;
        }
        .reset-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #000;
        }
        .reset-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .reset-container button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            font-size: 12px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>LUPA PASSWORD</h2><br>
        <?php if (isset($reset_message)): ?>
            <div class="error"><?php echo $reset_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="send_link.php" target="_blank" onsubmit="openWhatsApp(); return false;">
            <label for="no_tlp">Nomor Telepon:</label>
            <input type="text" name="no_tlp" placeholder="Masukkan Nomor Telepon" required>
            <button type="submit">Kirim Link Reset Password</button>
        </form>
    </div>
    <div class="footer">
        Copyright © 2024 Halo Tentor | Powered by Halo Tentor
    </div>
</body>

<script>
    function openWhatsApp() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        const no_tlp = formData.get('no_tlp');

        // Mengirim permintaan ke send_link.php
        fetch('send_link.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Mengambil URL WhatsApp dari response
            const url = new URL(data);
            window.open(url, '_blank'); // Membuka link di jendela baru
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
    
</html>