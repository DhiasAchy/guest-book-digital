<?php
// Koneksi database
$host = 'localhost';
$db   = 'db_test';
$user = 'root';
$pass = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Batasan
  $maxFiles = 3;
  $maxSizePerFile = 1.5 * 1024 * 1024; // 1.5 MB
  $maxTotalSize = 4.5 * 1024 * 1024;   // 4.5 MB total
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

  if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
    $total = count($_FILES['images']['name']);

    // Validasi jumlah file
    if ($total > $maxFiles) {
      http_response_code(400);
      echo json_encode(['status' => 'error', 'message' => 'Maksimal upload 3 gambar.']);
      exit;
    }

    // Hitung total ukuran file gabungan
    $totalSize = 0;
    for ($i = 0; $i < $total; $i++) {
      $totalSize += $_FILES['images']['size'][$i];
    }
    if ($totalSize > $maxTotalSize) {
      http_response_code(400);
      echo json_encode(['status' => 'error', 'message' => 'Total ukuran file melebihi 4.5 MB.']);
      exit;
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    for ($i = 0; $i < $total; $i++) {
      $tmpName = $_FILES['images']['tmp_name'][$i];
      $fileName = basename($_FILES['images']['name'][$i]);
      $fileSize = $_FILES['images']['size'][$i];
      $fileType = mime_content_type($tmpName);

      // Validasi tipe file
      if (!in_array($fileType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "File '$fileName' bukan gambar yang diperbolehkan."]);
        exit;
      }

      // Validasi ukuran per file
      if ($fileSize > $maxSizePerFile) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "File '$fileName' melebihi 1.5 MB."]);
        exit;
      }

      // Simpan file dengan nama unik
      $uniqueName = uniqid() . '_' . $fileName;
      $targetFile = $uploadDir . $uniqueName;

      if (move_uploaded_file($tmpName, $targetFile)) {
        // Simpan path ke database
        $stmt = $pdo->prepare("INSERT INTO uploaded_images (image_id, image_path) VALUES (:id, :path)");
        $stmt->execute([
          ':id' => uniqid(),
          ':path' => $targetFile
        ]);
      } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => "Gagal menyimpan file '$fileName'."]);
        exit;
      }
    }

    echo json_encode(['status' => 'success', 'message' => 'File berhasil diupload dan disimpan.']);
  } else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diupload.']);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
