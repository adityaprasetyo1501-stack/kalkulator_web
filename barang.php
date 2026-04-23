<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php"><strong>📦 Daftar Barang</strong></a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan Penjualan</a>
        <a href="hutang.php">Cek Hutang</a>
    </nav>

    <div class="container">
        <h1>Daftar Barang</h1>
        
        <div style="margin-bottom: 20px;">
            <a href="barang_tambah.php" style="background: #27ae60; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-block;">+ Tambah Barang Baru</a>
        </div>

        <table> 
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center;">Tambah Stok</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
<?php
include 'koneksi.php';

$data = mysqli_query($koneksi, "SELECT * FROM barang");
$count = mysqli_num_rows($data);

if ($count > 0) {
    while ($d = mysqli_fetch_array($data)) {
?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($d['nama_barang']); ?></strong></td>
                    <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td style="text-align: center;"><span class="badge badge-info"><?php echo $d['stok']; ?></span></td>
                    
                    <td style="text-align: center;">
                        <form action="stok_tambah_proses.php" method="POST" style="margin: 0; display: flex; gap: 5px; justify-content: center; align-items: center;">
                            <input type="hidden" name="id_barang" value="<?php echo $d['id_barang']; ?>">
                            <input type="number" name="jumlah_tambah" min="1" style="width: 60px; margin-bottom: 0;" required>
                            <input type="submit" name="update_stok" value="Tambah" style="margin-bottom: 0; background: #3498db; padding: 8px 15px;">
                        </form>
                    </td>

                    <td style="text-align: center;">
                        <a href="barang_edit.php?id_barang=<?php echo $d['id_barang']; ?>" style="background: #3498db; color: white; padding: 6px 12px; border-radius: 3px; text-decoration: none; margin-right: 5px;">Edit</a>
                        <a href="barang_hapus.php?id_barang=<?php echo $d['id_barang']; ?>" style="background: #e74c3c; color: white; padding: 6px 12px; border-radius: 3px; text-decoration: none;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
<?php } 
} else {
    echo '<tr><td colspan="5" style="text-align: center; padding: 30px;">Tidak ada data barang. <a href="barang_tambah.php">Tambah barang sekarang</a></td></tr>';
}
?>
            </tbody>
        </table>
    </div>
</body>
</html>