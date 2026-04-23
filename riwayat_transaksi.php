<?php
include "koneksi.php";

// Perbaikan query: mengambil kolom dari tabel yang benar
$data = mysqli_query($koneksi,"
SELECT 
    dt.id_detail,
    b.nama_barang,
    dt.jumlah,
    dt.harga,
    dt.subtotal,
    t.uang_bayar,
    t.kembalian,
    t.tanggal,
    t.metode_pembayaran
FROM detail_transaksi dt
JOIN barang b ON dt.id_barang = b.id_barang
JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
ORDER BY t.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php">Daftar Barang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan Penjualan</a>
        <a href="riwayat_transaksi.php"><strong>📜 Riwayat</strong></a>
    </nav>

    <div class="container">
        <h1>📜 Riwayat Transaksi</h1>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                    <th style="text-align: right;">Uang Bayar</th>
                    <th style="text-align: right;">Kembalian</th>
                    <th>Metode</th>
                </tr>
            </thead>
            <tbody>
<?php 
$no = 1;
$count = mysqli_num_rows($data);

if ($count > 0) {
    while($d = mysqli_fetch_array($data)){
        $kembalian_class = $d['kembalian'] < 0 ? 'text-danger' : 'text-success';
?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($d['tanggal'])) ?></td>
                    <td><strong><?= htmlspecialchars($d['nama_barang']) ?></strong></td>
                    <td style="text-align: right;">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                    <td style="text-align: center;"><?= $d['jumlah'] ?></td>
                    <td style="text-align: right;">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                    <td style="text-align: right;">Rp <?= number_format($d['uang_bayar'], 0, ',', '.') ?></td>
                    <td style="text-align: right;"><span class="<?= $kembalian_class ?>"><strong>Rp <?= number_format($d['kembalian'], 0, ',', '.') ?></strong></span></td>
                    <td>
                        <span class="badge <?php 
                            echo $d['metode_pembayaran'] == 'Cash' ? 'badge-success' : 
                                 ($d['metode_pembayaran'] == 'QRIS' ? 'badge-primary' : 'badge-warning');
                        ?>"><?= htmlspecialchars($d['metode_pembayaran']) ?></span>
                    </td>
                </tr>
<?php } 
} else {
    echo '<tr><td colspan="9" style="text-align: center; padding: 30px;">Belum ada riwayat transaksi.</td></tr>';
}
?>
            </tbody>
        </table>

        <div style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" style="background: #27ae60; padding: 10px 20px; margin-right: 10px;">🖨️ Cetak</button>
            <a href="transaksi.php"><button style="background: #3498db; padding: 10px 20px;">Buat Transaksi Baru</button></a>
        </div>
    </div>
</body>
</html>