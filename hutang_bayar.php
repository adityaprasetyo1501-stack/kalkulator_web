<?php 
include "koneksi.php";

if (!isset($_GET['id'])) {
    header("location:hutang.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("location:hutang.php");
    exit;
}

$sisa = abs($data['kembalian']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Hutang - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="hutang.php">← Kembali ke Daftar Hutang</a>
    </nav>

    <div class="container" style="max-width: 500px;">
        <h1>💳 Pembayaran Hutang</h1>
        
        <div class="section" style="border-left: 4px solid #e74c3c;">
            <div style="margin-bottom: 15px;">
                <label style="color: #7f8c8d; font-size: 13px;">NAMA PELANGGAN</label>
                <div style="font-size: 22px; font-weight: bold; color: #2c3e50;">
                    <?= htmlspecialchars($data['nama_pelanggan']) ?>
                </div>
            </div>

            <div style="margin-bottom: 20px; padding: 15px; background: #fff3f3; border: 1px solid #ffc107; border-radius: 4px;">
                <label style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">TOTAL HUTANG SISA</label>
                <div style="font-size: 32px; font-weight: bold; color: #e74c3c;">
                    Rp <?= number_format($sisa, 0, ',', '.') ?>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="color: #7f8c8d; font-size: 13px;">TANGGAL TRANSAKSI</label>
                <div style="font-size: 16px; color: #2c3e50;">
                    <?= date('d/m/Y H:i', strtotime($data['tanggal'])) ?>
                </div>
            </div>
        </div>

        <form action="hutang_proses_cicil.php" method="POST" style="margin-top: 30px;">
            <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($id) ?>">
            
            <div>
                <label for="nominal_bayar">Jumlah Bayar Cicilan (Rp):</label>
                <input type="number" id="nominal_bayar" name="nominal_bayar" 
                       max="<?= $sisa ?>" 
                       placeholder="Masukkan jumlah cicilan..." 
                       required>
                <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                    Maksimal: Rp <?= number_format($sisa, 0, ',', '.') ?>
                </small>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="submit" class="success" style="flex: 1;">✅ Simpan Pembayaran</button>
                <a href="hutang.php" style="flex: 1;"><button type="button" style="width: 100%; background: #95a5a6;">Batal</button></a>
            </div>
        </form>
    </div>
</body>
</html>