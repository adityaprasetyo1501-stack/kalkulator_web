<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php">Daftar Barang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan Penjualan</a>
    </nav>

    <div class="container" style="max-width: 500px;">
        <h1>Edit Data Barang</h1>

<?php 
    include 'koneksi.php';
    $id = $_GET['id_barang'] ?? '';
    
    if (empty($id)) {
        echo '<div class="alert alert-danger">ID Barang tidak valid.</div>';
        echo '<a href="barang.php"><button>Kembali</button></a>';
        exit;
    }
    
    $query_mysql = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id'") or die(mysqli_error($koneksi));
    $count = mysqli_num_rows($query_mysql);
    
    if ($count > 0) {
        $data = mysqli_fetch_array($query_mysql);
?>
        <form action="barang_edit_tambah.php" method="post">
            <div>
                <label for="nama_barang">Nama Barang:</label>
                <input type="hidden" name="id_barang" value="<?php echo htmlspecialchars($data['id_barang']); ?>">
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>
            </div>
            
            <div>
                <label for="harga">Harga:</label>
                <input type="number" id="harga" name="harga" value="<?php echo htmlspecialchars($data['harga']); ?>" required>
            </div>

            <div>
                <label for="stok">Stok:</label>
                <input type="number" id="stok" name="stok" value="<?php echo htmlspecialchars($data['stok']); ?>" required>
            </div>

            <div>
                <label for="tanggal_buat">Tanggal Buat:</label>
                <input type="text" id="tanggal_buat" name="tanggal_buat" value="<?php echo htmlspecialchars($data['tanggal_buat']); ?>" readonly>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="submit" name="proses_update" class="success" style="flex: 1;">Simpan Perubahan</button>
                <a href="barang.php" style="flex: 1;"><button type="button" style="width: 100%; background: #95a5a6;">Batal</button></a>
            </div>
        </form>
<?php 
    } else {
        echo '<div class="alert alert-danger">Data barang tidak ditemukan.</div>';
        echo '<a href="barang.php"><button>Kembali ke Daftar Barang</button></a>';
    }
?>
    </div>
</body>
</html>