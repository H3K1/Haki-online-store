<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data pelanggan dan produk
$pelanggan_result = $conn->query("SELECT ID_Pelanggan, email FROM pelanggan WHERE ID_Pelanggan NOT IN (SELECT ID_Pelanggan FROM penjual)");

$produk_result = $conn->query("SELECT ID_Produk, namaProduk, harga FROM produk");

// Tambahkan data baru jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelanggan = $_POST['ID_Pelanggan'];
    $id_produk = $_POST['ID_Produk'];
    $jumlahBarang = $_POST['jumlahBarang'];
    $metodePembayaran = $_POST['metode_pembayaran'];
    
    // Ambil harga dan stok produk dari database
    $produk_info = $conn->query("SELECT harga, stok FROM produk WHERE ID_Produk = '$id_produk'");
    $produk_row = $produk_info->fetch_assoc();
    $harga = $produk_row['harga'];
    $stok = $produk_row['stok'];
    
    // Cek apakah stok mencukupi
    if ($jumlahBarang > $stok) {
        echo "<p style='color:red;'>Stok tidak mencukupi! Stok tersedia: $stok</p>";
    } else {
        // Hitung total harga
        $totalHarga = $jumlahBarang * $harga;

        // Insert ke detail_penjualan
        $sql_insert = "INSERT INTO detail_penjualan (ID_Pelanggan, ID_Produk, jumlahBarang, totalHarga, tanggalPembelian, metode_pembayaran) 
                       VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iiids", $id_pelanggan, $id_produk, $jumlahBarang, $totalHarga, $metodePembayaran);

        if ($stmt->execute()) {
            // Kurangi stok produk
            $update_stok = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE ID_Produk = ?");
            $update_stok->bind_param("ii", $jumlahBarang, $id_produk);
            $update_stok->execute();
            $update_stok->close();

            echo "Data berhasil ditambahkan! <a href='../read_update_and_delete/detail_penjualan.php'>Kembali</a>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Detail Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
    <script>
        function updateTotalHarga() {
            var produkSelect = document.getElementById("ID_Produk");
            var jumlahBarang = document.getElementById("jumlahBarang").value;
            var harga = produkSelect.options[produkSelect.selectedIndex].getAttribute("data-harga");
            var totalHarga = jumlahBarang * harga;
            document.getElementById("totalHarga").value = totalHarga;
        }
    </script>
</head>
<body>
    <h2>Tambah Data Detail Penjualan</h2>
    <form method="POST">
        <label>Pilih Pelanggan:</label>
        <select name="ID_Pelanggan" required>
            <?php while ($row = $pelanggan_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['ID_Pelanggan']; ?>">
                    <?php echo $row['email']; ?>
                </option>
            <?php } ?>
        </select><br>

        <label>Pilih Produk:</label>
        <select name="ID_Produk" id="ID_Produk" required onchange="updateTotalHarga()">
            <?php
            // Reset data produk_result
            $produk_result->data_seek(0);
            while ($row = $produk_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['ID_Produk']; ?>" data-harga="<?php echo $row['harga']; ?>">
                    <?php echo $row['namaProduk']; ?>
                </option>
            <?php } ?>
        </select><br>

        <label>Jumlah Barang:</label>
        <input type="number" name="jumlahBarang" id="jumlahBarang" required oninput="updateTotalHarga()"><br>
        
        <label>Total Harga:</label>
        <input type="number" name="totalHarga" id="totalHarga" required readonly><br>

        <!-- ✅ Tambahkan metode pembayaran -->
        <label>Metode Pembayaran:</label>
        <select name="metode_pembayaran" required>
            <option value="">-- Pilih Metode Pembayaran --</option>
            <option value="TUNAI">Tunai</option>
            <option value="KARTU_KREDIT">Kartu Kredit</option>
            <option value="DEBIT">Debit</option>
            <option value="TRANSFER_BANK">Transfer Bank</option>
            <option value="E_WALLET">E-Wallet</option>
        </select><br><br>

        <button type="submit">Tambah Data</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>