<?php
// tampil_gambar.php

$host = 'localhost';
$db   = 'db_test';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data gambar berdasarkan urutan order_index ascending
    $stmt = $pdo->query("SELECT image_id, image_path FROM uploaded_images ORDER BY order_index ASC");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal koneksi/database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Gambar</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #fafafa;
    }
    .gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }
    .thumb {
      width: 150px;
      height: 150px;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
    }
    .thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
  </style>
</head>
<body>

  <h1>Daftar Gambar</h1>

  <?php if (empty($images)): ?>
    <p>Tidak ada gambar yang tersedia.</p>
  <?php else: ?>
    <div class="gallery">
      <?php foreach ($images as $img): ?>
        <div class="thumb" data-id="<?= htmlspecialchars($img['image_id']) ?>">
          <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Gambar <?= htmlspecialchars($img['image_id']) ?>" />
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</body>
</html>
