<?php
include "koneksi.php";

// 1. Ambil filter dari URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'hari';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

$tgl_sekarang = date('Y-m-d');
$where_search = $search ? " AND t.nama_pelanggan LIKE '%$search%'" : "";

// 2. Tentukan Query
if ($filter == 'minggu') {
    $query_str = "SELECT t.*, dt.jumlah, b.nama_barang, dt.subtotal 
                  FROM transaksi t 
                  JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                  JOIN barang b ON dt.id_barang = b.id_barang
                  WHERE t.tanggal >= DATE_SUB(NOW(), INTERVAL 7 DAY) $where_search
                  ORDER BY t.tanggal DESC";
    $judul = "Laporan Mingguan";
} elseif ($filter == 'bulan') {
    $query_str = "SELECT t.*, dt.jumlah, b.nama_barang, dt.subtotal 
                  FROM transaksi t 
                  JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                  JOIN barang b ON dt.id_barang = b.id_barang
                  WHERE MONTH(t.tanggal) = MONTH(NOW()) AND YEAR(t.tanggal) = YEAR(NOW()) $where_search
                  ORDER BY t.tanggal DESC";
    $judul = "Laporan Bulanan";
} elseif ($filter == 'hutang') {
    $query_str = "SELECT t.*, dt.jumlah, b.nama_barang, dt.subtotal 
                  FROM transaksi t 
                  JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                  JOIN barang b ON dt.id_barang = b.id_barang
                  WHERE t.metode_pembayaran = 'Hutang' $where_search
                  ORDER BY t.tanggal DESC";
    $judul = "Daftar Hutang Pelanggan";
} else {
    $query_str = "SELECT t.*, dt.jumlah, b.nama_barang, dt.subtotal 
                  FROM transaksi t 
                  JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                  JOIN barang b ON dt.id_barang = b.id_barang
                  WHERE DATE(t.tanggal) = CURDATE() $where_search
                  ORDER BY t.tanggal DESC";
    $judul = "Laporan Harian";
}

$data = mysqli_query($koneksi, $query_str);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($judul); ?> - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php">Daftar Barang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php"><strong>📊 Laporan</strong></a>
        <a href="hutang.php">Hutang</a>
    </nav>

    <div class="container">
        <h1><?php echo htmlspecialchars($judul); ?></h1>

        <div style="background: white; padding: 15px; border-radius: 4px; margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="laporan.php?filter=hari" style="padding: 8px 16px; background: <?php echo $filter == 'hari' ? '#3498db' : '#95a5a6'; ?>; color: white; border-radius: 4px; text-decoration: none;">📅 Harian</a>
            <a href="laporan.php?filter=minggu" style="padding: 8px 16px; background: <?php echo $filter == 'minggu' ? '#3498db' : '#95a5a6'; ?>; color: white; border-radius: 4px; text-decoration: none;">📆 Mingguan</a>
            <a href="laporan.php?filter=bulan" style="padding: 8px 16px; background: <?php echo $filter == 'bulan' ? '#3498db' : '#95a5a6'; ?>; color: white; border-radius: 4px; text-decoration: none;">📋 Bulanan</a>
            <a href="hutang.php" style="padding: 8px 16px; background: #e74c3c; color: white; border-radius: 4px; text-decoration: none;">💳 Lihat Hutang</a>
        </div>

        <div class="search-box">
            <form method="GET" action="laporan.php" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <input type="text" name="search" placeholder="Cari Nama Pelanggan..." value="<?= htmlspecialchars($search) ?>" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <button type="submit" style="margin-bottom: 0;">Cari</button>
                <?php if($search != ''): ?>
                    <a href="laporan.php?filter=<?= htmlspecialchars($filter) ?>" style="padding: 8px 15px; background: #95a5a6; color: white; border-radius: 4px; text-decoration: none; display: flex; align-items: center;">[Reset]</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Nama Barang</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                    <th>Metode</th>
                </tr>
            </thead>
            <tbody>
<?php 
$no = 1;
$total_pendapatan = 0;

if (mysqli_num_rows($data) > 0) {
    while($d = mysqli_fetch_array($data)){ 
        if($d['metode_pembayaran'] == 'Hutang'){
            $total_pendapatan += $d['uang_bayar']; 
        } else {
            $total_pendapatan += $d['subtotal'];
        }
?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($d['tanggal'])) ?></td>
                    <td><strong><?= htmlspecialchars($d['nama_pelanggan'] ?? '-'); ?></strong></td>
                    <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                    <td style="text-align: center;"><?= $d['jumlah'] ?> pcs</td>
                    <td style="text-align: right;">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                    <td>
                        <span class="badge <?php 
                            echo $d['metode_pembayaran'] == 'Cash' ? 'badge-success' : 
                                 ($d['metode_pembayaran'] == 'QRIS' ? 'badge-primary' : 'badge-warning');
                        ?>"><?= htmlspecialchars($d['metode_pembayaran']) ?></span>
                    </td>
                </tr>
<?php } 
} else {
    echo '<tr><td colspan="7" style="text-align: center; padding: 30px;">Data tidak ditemukan.</td></tr>';
}
?>
            </tbody>
        </table>

        <?php if (mysqli_num_rows($data) > 0 || $search): ?>
        <div class="section" style="margin-top: 30px;">
            <strong>💰 TOTAL PENDAPATAN: </strong>
            <span style="font-size: 24px; color: #27ae60; font-weight: bold;">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></span>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" style="background: #27ae60; padding: 10px 20px; margin-right: 10px;">🖨️ Cetak Laporan</button>
            <button onclick="window.location.href='laporan.php'" style="background: #95a5a6; padding: 10px 20px;">↻ Reset</button>
        </div>
    </div>
</body>
</html>