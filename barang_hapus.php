<?php
include 'koneksi.php';

// Pastikan ada ID yang dikirim melalui URL
if (isset($_GET['id_barang'])) {
    $d = $_GET['id_barang'];

    // Proses hapus
    $hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang = '$d'");

    if ($hapus) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='barang.php';</script>";
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    // Jika mencoba akses file ini tanpa ID, lempar balik ke halaman tampil
    header("location:barang.php");
}
?>