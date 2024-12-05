<?php
session_start();
include 'includes/koneksi.php'; // Pastikan untuk menghubungkan ke database

// Handle Read untuk mendapatkan data section berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM sections WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $section = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle Update
if (isset($_POST['update_section'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['hero-image']['name'];
    $target = "../assets/images/" . basename($image);

    if (!empty($image)) {
        move_uploaded_file($_FILES['hero-image']['tmp_name'], $target);
        $sql = "UPDATE sections SET title = :title, content = :content, image = :image WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':image', $image);
    } else {
        $sql = "UPDATE sections SET title = :title, content = :content WHERE id = :id";
        $stmt = $conn->prepare($sql);
    }
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header("Location: index.php"); // Redirect ke halaman index setelah update
}

$conn = null; // Menutup koneksi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Section</title>
    <link rel="stylesheet" href="../style.css"> <!-- Link ke file CSS -->
</head>
<body>
    <div class="admin-dashboard">
        <h1>Edit Section</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $section['id']; ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($section['title']); ?>" required>
            <textarea name="content" required><?php echo htmlspecialchars($section['content']); ?></textarea>
            <input type="file" id="hero-image" name="hero-image" accept="image/*">
            <button type="submit" name="update_section">Update Section</button>
        </form>
    </div>
</body>
</html>