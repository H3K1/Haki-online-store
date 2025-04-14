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

// Tambahkan data baru jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $katasandi = $_POST["kataSandi"];
    if (strlen($katasandi) > 10) {
        die("Password tidak boleh lebih dari 10 karakter.");
        }
        $hashedPassword = password_hash($katasandi, PASSWORD_DEFAULT);
    $alamat = $_POST["alamat"];
    
    $sql_insert = "INSERT INTO pelanggan (ID_Pelanggan, email, kataSandi, alamat, pembuatanAkun) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("isss", $id_pelanggan, $email, $hashedPassword, $alamat);
    
    if ($stmt->execute()) {
        echo "Data berhasil ditambahkan! <a href='../read_update_and_delete/pelanggan.php'>Kembali</a>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Detail Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
</head>
<body>
    <h2>Tambah Data Pelanggan</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label>Kata Sandi:</label>
        <input type="password" name="kataSandi" id="kataSandi" required>
        <br>
        <label>Alamat:</label>
        <input type="text" name="alamat" id="alamat" required>
        <br>
        <button type="submit">Tambah Data</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>