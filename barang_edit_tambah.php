<?php 
include 'koneksi.php';

// Check if the button was actually clicked
if (isset($_POST['proses_update'])) {
    
    // Get all data from $_POST (including the hidden id_barang)
    $d            = $_POST['id_barang']; 
    $nama_barang    = $_POST['nama_barang'];
    $harga          = $_POST['harga'];
    $stok           = $_POST['stok'];
    $tanggal        = $_POST['tanggal_buat'];

    $update = mysqli_query($koneksi, "UPDATE barang SET 
                nama_barang = '$nama_barang',
                harga = '$harga',
                stok = '$stok',
                tanggal_buat = '$tanggal'
                WHERE id_barang = '$d'") or die(mysqli_error($koneksi));

    if ($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='barang.php';</script>";
    } else {
        echo "Gagal mengupdate data.";
    }
} else {
    // If someone tries to access this file directly without clicking submit
    header("location:barang.php");
}
?>