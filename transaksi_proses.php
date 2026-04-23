<?php
include "koneksi.php";

$nama_barang_array = $_POST['nama_barang']; 
$jumlah_array      = $_POST['jumlah'];      
$metode            = $_POST['metode'];
$bayar             = (int)$_POST['bayar']; 
$nama_pelanggan    = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']); 

$total_semua = 0;
$data_valid = [];

for ($i = 0; $i < count($nama_barang_array); $i++) {
    $nama = $nama_barang_array[$i];
    $qty  = $jumlah_array[$i];
    $query_b = mysqli_query($koneksi, "SELECT * FROM barang WHERE nama_barang = '$nama'");
    $data_b  = mysqli_fetch_assoc($query_b);
    if ($data_b) {
        $subtotal = $data_b['harga'] * $qty;
        $total_semua += $subtotal;
        $data_valid[] = ['id_barang' => $data_b['id_barang'], 'harga' => $data_b['harga'], 'jumlah' => $qty, 'subtotal' => $subtotal];
    }
}

// Ganti logika pengecekan uang kurang
if ($metode != 'Hutang' && $bayar < $total_semua) {
    echo "<script>alert('Gagal! Uang kurang'); window.history.back();</script>";
    exit;
}

$kembalian = $bayar - $total_semua;

// Pastikan variabel $nama_pelanggan diisi 'Umum' jika kosong sebelum simpan ke DB
$pelanggan_db = !empty($nama_pelanggan) ? $nama_pelanggan : 'Umum';

// Simpan ke database menggunakan $pelanggan_db
$query_ins = "INSERT INTO transaksi (tanggal, nama_pelanggan, total, metode_pembayaran, uang_bayar, kembalian) 
              VALUES (NOW(), '$pelanggan_db', '$total_semua', '$metode', '$bayar', '$kembalian')";

if(mysqli_query($koneksi, $query_ins)){
    $id_transaksi_baru = mysqli_insert_id($koneksi);

    foreach ($data_valid as $item) {
        mysqli_query($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, harga, subtotal) 
                                VALUES ('$id_transaksi_baru', '$item[id_barang]', '$item[jumlah]', '$item[harga]', '$item[subtotal]')");
        mysqli_query($koneksi, "UPDATE barang SET stok = stok - $item[jumlah] WHERE id_barang = '$item[id_barang]'");
    }

    // --- LETAKKAN KODE BARU ANDA DI SINI (MENGGANTIKAN REDIRECT LAMA) ---
    
    $struk_items = [];
    for ($i = 0; $i < count($nama_barang_array); $i++) {
        $nama = $nama_barang_array[$i];
        $qty  = $jumlah_array[$i];
        
        $q_harga = mysqli_query($koneksi, "SELECT harga FROM barang WHERE nama_barang = '$nama'");
        $d_harga = mysqli_fetch_assoc($q_harga);
        $sub = $d_harga['harga'] * $qty;

        // Format: Nama Barang|Qty|Subtotal
        $struk_items[] = $nama . "|" . $qty . "|" . $sub;
    }

    $daftar_struk = implode("||", $struk_items);

    // Cek Nama Pelanggan untuk Struk
    $nama_p = !empty($nama_pelanggan) ? $nama_pelanggan : 'Umum';

    echo "<script>
            alert('Transaksi Berhasil!'); 
            window.location='transaksi.php?pesan=berhasil&items=" . urlencode($daftar_struk) . "&total=$total_semua&bayar=$bayar&kembali=$kembalian&pelanggan=" . urlencode($nama_p) . "';
          </script>";
    // --- AKHIR KODE BARU ---

} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>