<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php">Daftar Barang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan Penjualan</a>
    </nav>

    <div class="container" style="max-width: 500px;">
        <h1>Tambah Barang Baru</h1>
        
        <form method="POST" action="barang_simpan.php">
            <div>
                <label for="nama">Nama Barang:</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan nama barang" required>
            </div>

            <div>
                <label for="harga">Harga:</label>
                <input type="number" name="harga" id="harga" step="1" placeholder="Masukkan harga barang" required>
            </div>

            <div>
                <label for="stok">Stok:</label>
                <input type="number" name="stok" id="stok" placeholder="Masukkan jumlah stok awal" required>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="submit" class="success" style="flex: 1;">Simpan Barang</button>
                <a href="barang.php" style="flex: 1;"><button type="button" style="width: 100%; background: #95a5a6;">Batal</button></a>
            </div>
        </form>
    </div>
</body>
</html>