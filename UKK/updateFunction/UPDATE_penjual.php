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
    $id = $_GET['id'];
    $sql = "SELECT * FROM penjual WHERE ID_Penjual = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

// Update jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['ID_Penjual']);
    $idPelanggan = $conn->real_escape_string($_POST['ID_Pelanggan']);
    $namaToko = $conn->real_escape_string($_POST['namaToko']);
    $lokasiToko = $conn->real_escape_string($_POST['lokasiToko']);
    $pembuatanAkun = $conn->real_escape_string($_POST['pembuatanAkun']);


    $sql = "UPDATE penjual SET 
                ID_Pelanggan = '$idPelanggan',
                namaToko = '$namaToko',
                lokasiToko = '$lokasiToko',
                pembuatanAkun = '$pembuatanAkun'
            WHERE ID_Penjual = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate. <a href='../read_update_and_delete/penjual.php'>Kembali</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Penjual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
</head>
<body>
    <h2>Edit Data Penjual</h2>
    <form method="post">
        <input type="hidden" name="ID_Penjual" value="<?php echo $row['ID_Penjual']; ?>">
        <label>ID Pelanggan:</label><br>
        <input type="text" name="ID_Pelanggan" readonly value="<?php echo $row['ID_Pelanggan']; ?>"><br>

        <label>Nama Toko:</label><br>
        <input type="text" name="namaToko" value="<?php echo $row['namaToko']; ?>"><br>

        <label>Lokasi Toko:</label><br>
        <input type="text" name="lokasiToko" value="<?php echo $row['lokasiToko']; ?>"><br>

        <label>Tanggal Pembuatan Akun:</label><br>
        <input type="text" name="pembuatanAkun" readonly value="<?php echo $row['pembuatanAkun']; ?>"><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>