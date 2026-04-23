<?php
include 'koneksi.php';

if (isset($_POST['update_stok'])) {
    $id_barang     = $_POST['id_barang'];
    $jumlah_tambah = $_POST['jumlah_tambah'];

    // Menambah stok: stok baru = stok lama + jumlah input
    $query = mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah_tambah WHERE id_barang = '$id_barang'");

    if ($query) {
        header("location:barang.php?pesan=stok_berhasil");
    } else {
        echo "Gagal update stok: " . mysqli_error($koneksi);
    }
} else {
    header("location:barang.php");
}
?>