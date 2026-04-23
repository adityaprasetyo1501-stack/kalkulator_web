<?php 
include "koneksi.php"; 

// Mengambil kata kunci pencarian jika ada
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hutang - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php">Daftar Barang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
        <a href="hutang.php"><strong>💳 Daftar Hutang</strong></a>
    </nav>

    <div class="container">
        <h1>Daftar Hutang Pelanggan</h1>

        <div class="search-box">
            <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="cari" placeholder="Cari Nama Pelanggan..." value="<?= htmlspecialchars($cari) ?>" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <button type="submit" style="margin-bottom: 0;">Cari</button>
                <?php if($cari != ''): ?>
                    <a href="hutang.php" style="padding: 8px 15px; background: #95a5a6; color: white; border-radius: 4px; text-decoration: none; display: flex; align-items: center;">[Reset]</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th style="text-align: right;">Total Belanja</th>
                    <th style="text-align: right;">Uang Bayar</th>
                    <th style="text-align: right;">Sisa Hutang</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
<?php
        $no = 1;
        $total_semua_hutang = 0;

        // Query untuk ambil data hutang
        $sql = "SELECT * FROM transaksi WHERE (metode_pembayaran = 'Hutang' OR kembalian < 0)";
        if ($cari != '') {
            $sql .= " AND nama_pelanggan LIKE '%$cari%'";
        }
        $sql .= " ORDER BY tanggal DESC";
        
        $query = mysqli_query($koneksi, $sql);
        
        if(mysqli_num_rows($query) > 0) {
            while($d = mysqli_fetch_array($query)){
                $sisa_hutang = abs($d['kembalian']); // abs() mengubah angka minus jadi positif
                $total_semua_hutang += $sisa_hutang;
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($d['tanggal'])); ?></td>
                    <td><strong><?= htmlspecialchars($d['nama_pelanggan'] ?: 'Umum'); ?></strong></td>
                    <td style="text-align: right;">Rp <?= number_format($d['total'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;">Rp <?= number_format($d['uang_bayar'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><strong style="color: #e74c3c;">Rp <?= number_format($sisa_hutang, 0, ',', '.'); ?></strong></td>
                    <td style="text-align: center;"><span class="badge badge-danger">BELUM LUNAS</span></td>
                    <td style="text-align: center;">
                        <a href="hutang_bayar.php?id=<?= $d['id_transaksi']; ?>" style="background: #3498db; color: white; padding: 6px 12px; text-decoration: none; border-radius: 3px; display: inline-block; font-size: 12px;">Bayar Cicilan</a>
                    </td>
                </tr>
            <?php 
            }
        } else {
            echo "<tr><td colspan='8' style='text-align:center; padding: 30px;'>Tidak ada data hutang pelanggan.</td></tr>";
        }
        ?>
            </tbody>
        </table>

        <?php if(mysqli_num_rows($query) > 0): ?>
        <div class="section" style="margin-top: 30px;">
            <div style="font-size: 18px; margin-bottom: 10px;">
                <strong>📊 Total Seluruh Hutang Belum Terbayar: </strong>
            </div>
            <div style="font-size: 32px; color: #e74c3c; font-weight: bold;">
                Rp <?= number_format($total_semua_hutang, 0, ',', '.'); ?>
            </div>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" style="background: #27ae60; padding: 10px 20px; margin-right: 10px;">🖨️ Cetak</button>
            <button onclick="window.location.href='hutang.php'" style="background: #95a5a6; padding: 10px 20px;">↻ Reset</button>
        </div>
    </div>
</body>
</html>