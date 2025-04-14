<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM detail_penjualan WHERE ID_Detail_penjualan = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Ambil harga produk dari tabel produk
    $produkHargaResult = $conn->query("SELECT harga FROM produk WHERE ID_Produk = '{$row['ID_Produk']}'");
    $produkHarga = $produkHargaResult->fetch_assoc()['harga'];
}

// Update data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['ID_Detail_penjualan']);
    $idPelanggan = $conn->real_escape_string($_POST['ID_Pelanggan']);
    $idProduk = $conn->real_escape_string($_POST['ID_Produk']);
    $jumlahBarang = $conn->real_escape_string($_POST['jumlahBarang']);
    $totalHarga = $conn->real_escape_string($_POST['totalHarga']);
    $tanggalPembelian = $conn->real_escape_string($_POST['tanggalPembelian']);

    // Ambil jumlahBarang lama
    $result_old = $conn->query("SELECT jumlahBarang, ID_Produk FROM detail_penjualan WHERE ID_Detail_penjualan = '$id'");
    $row_old = $result_old->fetch_assoc();
    $jumlahBarangLama = $row_old['jumlahBarang'];
    $idProdukLama = $row_old['ID_Produk'];

    // Restore stok lama terlebih dahulu
    $conn->query("UPDATE produk SET stok = stok + $jumlahBarangLama WHERE ID_Produk = '$idProdukLama'");

    // Kurangi stok baru
    $conn->query("UPDATE produk SET stok = stok - $jumlahBarang WHERE ID_Produk = '$idProduk'");

    // Lanjut update data transaksi
    $sql = "UPDATE detail_penjualan SET 
                ID_Pelanggan = '$idPelanggan',
                ID_Produk = '$idProduk',
                jumlahBarang = '$jumlahBarang',
                totalHarga = '$totalHarga',
                tanggalPembelian = '$tanggalPembelian'
            WHERE ID_Detail_penjualan = '$id'";

    // Cek stok tersedia
    $stokResult = $conn->query("SELECT stok FROM produk WHERE ID_Produk = '$idProduk'");
    $stokSekarang = $stokResult->fetch_assoc()['stok'];
    if ($jumlahBarang > ($stokSekarang + $jumlahBarangLama)) {
        die("Stok tidak mencukupi untuk update!");
    }


    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate. <a href='../read_update_and_delete/detail_penjualan.php'>Kembali</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Detail Penjualan</title>
</head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
<script>
    function updateTotalHarga() {
        var jumlahBarang = document.getElementById("jumlahBarang").value;
        var harga = document.getElementById("hargaSatuan").value;
        var totalHarga = jumlahBarang * harga;
        document.getElementById("totalHarga").value = totalHarga;
    }
</script>
<body>
    <h2>Edit Data Detail Penjualan</h2>
    <form method="post">
        <input type="hidden" name="ID_Detail_penjualan" value="<?php echo $row['ID_Detail_penjualan']; ?>">

        <label>ID Pelanggan:</label><br>
        <input type="text" name="ID_Pelanggan" readonly value="<?php echo $row['ID_Pelanggan']; ?>"><br>

        <label>ID Produk:</label><br>
        <input type="text" name="ID_Produk" readonly value="<?php echo $row['ID_Produk']; ?>"><br>

        <label>Jumlah Barang:</label><br>
        <input type="number" name="jumlahBarang" id="jumlahBarang" oninput="updateTotalHarga()" value="<?php echo $row['jumlahBarang']; ?>"><br>

        <input type="hidden" id="hargaSatuan" value="<?php echo $produkHarga; ?>">

        <label>Total Harga:</label><br>
        <input type="number" id="totalHarga" name="totalHarga" readonly value="<?php echo $row['totalHarga']; ?>"><br>

        <label>Tanggal Pembelian:</label><br>
        <input type="text" name="tanggalPembelian" readonly value="<?php echo date('Y-m-d', strtotime($row['tanggalPembelian'])); ?>"><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>