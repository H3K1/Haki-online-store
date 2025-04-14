<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data produk berdasarkan ID
    $sql = "SELECT * FROM produk WHERE ID_Produk = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID_Produk'];
    $namaProduk = $_POST['namaProduk'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $pembuatanProduk = $_POST['pembuatanProduk'];

    // Update data ke database
    $sql = "UPDATE produk SET 
                namaProduk = '$namaProduk', 
                deskripsi = '$deskripsi',
                stok = '$stok',
                harga = '$harga',
                pembuatanProduk = '$pembuatanProduk'
            WHERE ID_Produk = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate. <a href='../read_update_and_delete/produk.php'>Kembali</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
</head>
<body>
    <h2>Edit Produk</h2>
    <form method="post">
        <input type="hidden" name="ID_Produk" value="<?php echo $row['ID_Produk']; ?>">
        <label>Nama Produk:</label><br>
        <input type="text" name="namaProduk" value="<?php echo $row['namaProduk']; ?>"><br>
        <label>Deskripsi:</label><br>
        <textarea name="deskripsi"><?php echo $row['deskripsi']; ?></textarea><br>
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?php echo $row['stok']; ?>"><br>
        <label>Harga:</label><br>
        <input type="number" name="harga" value="<?php echo $row['harga']; ?>"><br>
        <label>Tanggal Pembuatan Produk:</label><br>
        <input type="text" name="pembuatanProduk" readonly value="<?php echo $row['pembuatanProduk']; ?>"><br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>