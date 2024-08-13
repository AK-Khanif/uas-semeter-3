<?php
require_once "services/database.php";
session_start();

if ($_SESSION["is_login"] == false) {
  header("location: login.php");
}

define("APP_NAME", "KANGRESTO - WEBSITE PENERIMAAN TAMU");

$select_meja_query = "SELECT * FROM meja";
$count_meja_query = "SELECT COUNT(status) as total_count, SUM(status=1) as total_row FROM meja";

$select_meja = $db->query($select_meja_query);
$count_meja = $db->query($count_meja_query);

$status = $count_meja->fetch_assoc();
$jumlah_meja = $status["total_count"];
$meja_isi = $status["total_row"];

$is_full = false;

if ($jumlah_meja == $meja_isi) {
  $is_full = true;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title> <?= APP_NAME ?> </title>
</head>

<body>
  <?php include("layouts/header.php") ?>

  <br />

  <?php
  $sisa_meja = $jumlah_meja - $meja_isi;
  if ($is_full) {
    echo "<h1 align='center'>MEJA PENUH</h1>";
  } else {
    echo "<h1 align='center'>$sisa_meja Meja kosong</h1>";
  }
  ?>

  <div class="container">
    <?php
    foreach ($select_meja as $meja) {
    ?>
      <div class="card">
        <b><?= $meja['tipe_meja'] . " " . $meja['no_meja'] ?></b>
        <p>
          <?= is_null($meja['nama_pelanggan']) && is_null($meja['jumlah_orang']) ? "Meja kosong" : $meja['nama_pelanggan'] . " - " . $meja['jumlah_orang'] . " orang" ?>
        </p>
        <?php if (is_null($meja['nama_pelanggan']) && is_null($meja['jumlah_orang'])) : ?>
          <form action="meja.php" method="get">
            <input type="hidden" name="no_meja" value="<?= $meja['no_meja'] ?>">
            <button type="submit" class="btn-update">Update</button>
          </form>
        <?php else : ?>
          <form action="meja.php" method="get">
            <input type="hidden" name="no_meja" value="<?= $meja['no_meja'] ?>">
            <input type="hidden" name="nama_pelanggan" value="<?= $meja['nama_pelanggan'] ?>">
            <button type="submit" class="btn-complete">Selesaikan Pesanan</button>
          </form>
          <form action="edit_meja.php" method="get">
            <input type="hidden" name="no_meja" value="<?= $meja['no_meja'] ?>">
            <input type="hidden" name="nama_pelanggan" value="<?= $meja['nama_pelanggan'] ?>">
            <input type="hidden" name="jumlah_orang" value="<?= $meja['jumlah_orang'] ?>">
            <button type="submit" class="btn-edit">Edit</button>
          </form>
        <?php endif; ?>
      </div>
    <?php } ?>
  </div>
</body>

</html>