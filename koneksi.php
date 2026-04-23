<?php
$koneksi = mysqli_connect("localhost", "root", "", "kalkulator_penjualan");

if (!$koneksi) {
    echo "Koneksi gagal: " . mysqli_connect_error();
}
?>