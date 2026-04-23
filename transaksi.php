<?php include "koneksi.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .baris-barang {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            align-items: flex-end;
        }

        .baris-barang label {
            font-size: 13px;
            margin-bottom: 3px;
        }

        .baris-barang input,
        .baris-barang select {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        <a href="barang.php">Daftar Barang</a>
        <a href="transaksi.php"><strong>💳 Transaksi</strong></a>
        <a href="laporan.php">Laporan</a>
        <a href="hutang.php">Hutang</a>
    </nav>

    <div class="container">
<?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil'): ?>
        <div class="struk-box">
            <div class="struk-header">
                <strong>TOKO REZEKI</strong><br>
                Bantul, DIY<br>
                <small><?= date('d/m/Y H:i') ?></small>
            </div>
            
            <div class="struk-line"></div>
            <div class="flex-row">
                <span>Pelanggan:</span>
                <span><?php echo htmlspecialchars($_GET['pelanggan'] ?? 'Umum'); ?></span>
            </div>
            <div class="struk-line"></div>

<?php 
// Mengambil data barang yang sudah diformat dari proses
$items = explode("||", $_GET['items']);
$total_items = 0;
foreach($items as $item): 
    if (empty($item)) continue;
    $detail = explode("|", $item);
    if (count($detail) < 3) continue;
    $harga_satuan = $detail[2] / $detail[1];
    $total_items++;
?>
            <div class="flex-row" style="margin-bottom: 5px;">
                <span style="flex: 1;"><?= htmlspecialchars($detail[0]) ?></span>
                <span style="text-align: right; min-width: 100px;">
                    Rp <?= number_format($harga_satuan, 0, ',', '.') ?> x<?= $detail[1] ?>
                </span>
            </div>
<?php endforeach; ?>
            
            <div class="struk-line"></div>
            <div class="flex-row" style="font-weight: bold; margin-bottom: 5px;">
                <span>TOTAL</span>
                <span>Rp <?= number_format($_GET['total'], 0, ',', '.') ?></span>
            </div>
            <div class="flex-row" style="font-weight: bold; margin-bottom: 5px;">
                <span>BAYAR</span>
                <span>Rp <?= number_format($_GET['bayar'], 0, ',', '.') ?></span>
            </div>
            <div class="flex-row" style="font-weight: bold;">
                <span>KEMBALI</span>
                <span>Rp <?= number_format($_GET['kembali'], 0, ',', '.') ?></span>
            </div>

            <?php if($_GET['kembali'] < 0): ?>
                <div style="text-align: center; margin-top: 10px; background: #fff3cd; padding: 10px; border-radius: 3px; border: 1px solid #ffc107; color: #856404; font-weight: bold;">
                    ⚠️ TAGIHAN HUTANG
                </div>
            <?php endif; ?>

            <div class="struk-line"></div>
            <div class="struk-header">
                TERIMA KASIH<br>
                SELAMAT BELANJA KEMBALI
            </div>
        </div>
        
        <div class="no-print" style="text-align: center; margin: 20px 0;">
            <button onclick="window.print()" style="background: #27ae60; padding: 12px 30px; font-size: 16px; margin-right: 10px;">🖨️ Cetak Struk</button>
            <a href="transaksi.php"><button style="background: #3498db; padding: 12px 30px; font-size: 16px;">+ Transaksi Baru</button></a>
        </div>
        <hr>
<?php endif; ?>

        <h1>Input Transaksi Baru</h1>
        
        <form method="POST" action="transaksi_proses.php">
            <div id="wadah_input">
                <div class="baris-barang">
                    <div>
                        <label>Nama Pelanggan (Opsional)</label>
                        <input type="text" name="nama_pelanggan" placeholder="Nama pelanggan...">
                    </div>
                    <div>
                        <label>Barang</label>
                        <input list="barang_list" name="nama_barang[]" class="pilih-barang" placeholder="Pilih barang..." required>
                    </div>
                    <div>
                        <label>Jumlah</label>
                        <input type="number" name="jumlah[]" placeholder="Qty" min="1" required>
                    </div>
                </div>
            </div>
            
            <div style="margin: 20px 0;">
                <button type="button" onclick="tambahBaris()" style="background: #27ae60;">+ Tambah Barang Lagi</button>
            </div>
            
            <hr style="margin: 30px 0;">
            
            <div style="background: white; padding: 20px; border-radius: 4px; border-left: 4px solid #3498db;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label>Metode Pembayaran</label>
                        <select name="metode" required>
                            <option value="Cash">💵 Cash</option>
                            <option value="QRIS">📱 QRIS</option>
                            <option value="Hutang">📋 Hutang</option>
                        </select>
                    </div>
                    
                    <div>
                        <label>Jumlah Bayar (Rp)</label>
                        <input type="number" name="bayar" placeholder="Masukkan jumlah bayar" required>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="success" style="flex: 1; padding: 15px; font-size: 16px;">✅ Simpan Transaksi</button>
            </div>
        </form>
    </div>

    <datalist id="barang_list">
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
        while($d = mysqli_fetch_array($data)){
            echo "<option value='" . htmlspecialchars($d['nama_barang']) . "'>Rp " . number_format($d['harga'],0,',','.') . "</option>";
        }
        ?>
    </datalist>

    <script>
        function tambahBaris() {
            var wadah = document.getElementById("wadah_input");
            var div = document.createElement("div");
            div.innerHTML = `
                <div class="baris-barang">
                    <div>
                        <label>Barang</label>
                        <input list="barang_list" name="nama_barang[]" class="pilih-barang" placeholder="Pilih barang..." required>
                    </div>
                    <div>
                        <label>Jumlah</label>
                        <input type="number" name="jumlah[]" placeholder="Qty" min="1" required>
                    </div>
                    <button type="button" onclick="this.closest('.baris-barang').remove()" style="background: #e74c3c;">Hapus</button>
                </div>
            `;
            wadah.appendChild(div);
        }
    </script>
</body>
</html>