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
$penjual_result = $conn->query("SELECT ID_Penjual FROM penjual");
$produk_result = $conn->query("SELECT ID_Produk, namaProduk, harga FROM produk");
$pelanggan_result = $conn->query("SELECT ID_Pelanggan, email FROM pelanggan");

// Tambahkan data baru jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_penjual = $_POST['ID_Penjual'];
    $nama_produk = $_POST['namaProduk'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $stmt = $conn->prepare("INSERT INTO produk(ID_Penjual, namaProduk, deskripsi, stok, harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $id_penjual, $nama_produk ,$deskripsi, $stok, $harga);
    
    if ($stmt->execute()) {
        echo "Data berhasil ditambahkan! <a href='../read_update_and_delete/produk.php'>Kembali</a>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
</head>
<body>
    <h2>Tambah Data Produk</h2>
    <form method="POST">
        <label>Pilih Penjual:</label>
        <select name="ID_Penjual" required>
            <?php while ($row = $penjual_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['ID_Penjual']; ?>">
                <?php echo $row['ID_Penjual']; ?>
                </option>
            <?php } ?>
        </select><br>

        <label>Nama Produk:</label>
        <input type="text" name="namaProduk" id="namaProduk" required><br>

        <label>Deskripsi:</label>
        <input type="text" name="deskripsi" id="deskripsi" required><br>
        
        <label>Stok:</label>
        <input type="number" name="stok" id="stok" required><br>

        <label>Harga:</label>
        <input type="number" name="harga" id="harga" required><br>
        
        <button type="submit">Tambah Data</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>