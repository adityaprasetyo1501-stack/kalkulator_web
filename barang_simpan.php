<?php
include 'koneksi.php';

$nama  = $_POST['nama'];
$harga = $_POST['harga'];
$stok  = $_POST['stok'];
// Membuat variabel tanggal saat ini dengan format database
$tanggal = date("Y-m-d H:i:s"); 

// Tambahkan tanggal_buat dan '$tanggal' ke dalam query
mysqli_query($koneksi, "INSERT INTO barang (nama_barang, harga, stok, tanggal_buat) 
                        VALUES ('$nama', '$harga', '$stok', '$tanggal')");

header("location:barang.php");
?>