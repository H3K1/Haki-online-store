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

// Ambil data penjual dan pelanggan
$penjual_result = $conn->query("SELECT ID_Penjual, namaToko, lokasiToko FROM penjual");
$pelanggan_result = $conn->query("
    SELECT ID_Pelanggan, email 
    FROM pelanggan 
    WHERE ID_Pelanggan NOT IN (SELECT ID_Pelanggan FROM penjual)
");

// Tambahkan data baru jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelanggan = $_POST['ID_Pelanggan'];
    $namaToko = $_POST['namaToko'];
    $lokasiToko = $_POST['lokasiToko'];

   // Cek apakah pelanggan sudah menjadi penjual
    $cekStmt = $conn->prepare("SELECT * FROM penjual WHERE ID_Pelanggan = ?");
    $cekStmt->bind_param("i", $id_pelanggan);
    $cekStmt->execute();
    $cekResult = $cekStmt->get_result();

if ($cekResult->num_rows > 0) {
    echo "<p style='color: red;'>Pelanggan ini sudah memiliki toko dan tidak bisa membuat lebih dari satu.</p>";
} else {
    // Jika belum punya, insert data penjual baru
    $stmt = $conn->prepare("INSERT INTO penjual (namaToko, lokasiToko, ID_Pelanggan) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $namaToko, $lokasiToko, $id_pelanggan);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Data berhasil ditambahkan!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
$cekStmt->close();

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data penjual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
</head>
<body>
    <h2>Tambah Data penjual</h2>
    <form method="POST">
        <label>Pilih Pelanggan:</label>
        <select name="ID_Pelanggan" required>
            <?php while ($row = $pelanggan_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['ID_Pelanggan']; ?>">
                    <?php echo $row['email']; ?>
                </option>
            <?php } ?>
        </select><br>
        <label>nama toko</label>
        <input type="text" name="namaToko" id="namaToko" required>
        <br>
        <label>lokasi toko</label>
        <input type="text" name="lokasiToko" id="lokasiToko" required>
        <br>
        <button type="submit">Tambah Data</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>