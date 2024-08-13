<?php
require_once "services/database.php";
session_start();

if ($_SESSION["is_login"] == false) {
  header("location: login.php");
  exit();
}

// Mendapatkan data meja dari URL
$no_meja = isset($_GET['no_meja']) ? $_GET['no_meja'] : '';
$nama_pelanggan = isset($_GET['nama_pelanggan']) ? $_GET['nama_pelanggan'] : '';
$jumlah_orang = isset($_GET['jumlah_orang']) ? $_GET['jumlah_orang'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $no_meja = $_POST['no_meja'];
  $nama_pelanggan = $_POST['nama_pelanggan'];
  $jumlah_orang = $_POST['jumlah_orang'];

  // Validasi input
  if (!empty($nama_pelanggan) && is_numeric($jumlah_orang)) {
    $update_meja_query = "UPDATE meja SET nama_pelanggan=?, jumlah_orang=? WHERE no_meja=?";
    $stmt = $db->prepare($update_meja_query);
    $stmt->bind_param('sis', $nama_pelanggan, $jumlah_orang, $no_meja);

    if ($stmt->execute()) {
      header("Location: index.php");
      exit();
    } else {
      $error_message = "Gagal mengupdate data meja.";
    }
  } else {
    $error_message = "Input tidak valid.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Edit Meja</title>
</head>

<body>
  <?php include("layouts/header.php") ?>

  <div class="super-center">
    <h1>Edit Meja <?= htmlspecialchars($no_meja) ?></h1>

    <?php if (isset($error_message)) { ?>
      <p class="error"><?= $error_message ?></p>
    <?php } ?>

    <form action="edit_meja.php" method="post" class="edit-form">
      <input type="hidden" name="no_meja" value="<?= htmlspecialchars($no_meja) ?>">

      <label for="nama_pelanggan">Nama Pelanggan:</label>
      <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="<?= htmlspecialchars($nama_pelanggan) ?>" required>

      <label for="jumlah_orang">Jumlah Orang:</label>
      <input type="number" id="jumlah_orang" name="jumlah_orang" value="<?= htmlspecialchars($jumlah_orang) ?>" required>

      <button type="submit">Simpan Perubahan</button>
    </form>
  </div>
</body>

</html>