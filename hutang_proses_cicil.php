<?php
include "koneksi.php";

$id = $_POST['id_transaksi'];
$bayar_cicil = (int)$_POST['nominal_bayar'];

// 1. Ambil data lama
$query_lama = mysqli_query($koneksi, "SELECT uang_bayar, kembalian FROM transaksi WHERE id_transaksi = '$id'");
$data_lama = mysqli_fetch_assoc($query_lama);

$uang_bayar_baru = $data_lama['uang_bayar'] + $bayar_cicil;
$kembalian_baru = $data_lama['kembalian'] + $bayar_cicil;

// 2. Update database
// Jika kembalian sudah >= 0, otomatis metode jadi 'Cash' (Lunas)
$status_baru = ($kembalian_baru >= 0) ? "Cash" : "Hutang";

$update = mysqli_query($koneksi, "UPDATE transaksi SET 
    uang_bayar = '$uang_bayar_baru', 
    kembalian = '$kembalian_baru',
    metode_pembayaran = '$status_baru'
    WHERE id_transaksi = '$id'");

if($update){
    echo "<script>alert('Pembayaran cicilan berhasil dicatat!'); window.location='hutang.php';</script>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>