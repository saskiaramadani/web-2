<?php
include 'data-form-belanja.php';

$nama_customer = $_POST['namacus'];
$produk = $_POST['produk'];
$jumlah = $_POST['jumlah'];


$harga_produk = $ar_produk[$produk];
$total_belanja = $harga_produk * $jumlah;


echo "<h1>Detail Belanja</h1>";
echo "<p>Nama Customer: $nama_customer</p>";
echo "<p>Produk Pilihan: $produk</p>";
echo "<p>Harga Satuan: Rp " . number_format($harga_produk, 0, ',', '.') . "</p>";
echo "<p>Jumlah: $jumlah</p>";
echo "<p>Total Belanja: Rp " . number_format($total_belanja, 0, ',', '.') . "</p>";

?>