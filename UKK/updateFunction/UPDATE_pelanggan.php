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
    $sql = "SELECT * FROM pelanggan WHERE ID_Pelanggan = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

// Update jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['ID_Pelanggan']);
    $email = $conn->real_escape_string($_POST['email']);
    $katasandi = $_POST['kataSandi'];

    if (strlen($katasandi) > 10) {
    die("Password tidak boleh lebih dari 10 karakter.");
    }

    $hashedPassword = password_hash($katasandi, PASSWORD_DEFAULT);

    $alamat = $conn->real_escape_string($_POST['alamat']);
    $pembuatanAkun = $conn->real_escape_string($_POST['pembuatanAkun']);

    $sql = "UPDATE pelanggan SET email = '$email', kataSandi = '$hashedPassword', alamat = '$alamat', pembuatanAkun = '$pembuatanAkun' WHERE ID_Pelanggan = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate. <a href='../read_update_and_delete/pelanggan.php'>Kembali</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Pelanggan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style1.css"> <!-- Link ke file CSS -->
</head>
<script>
function togglePassword() {
    var input = document.getElementById("kataSandi");
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>

<body>
    <h2>Edit Data Pelanggan</h2>
    <form method="post">
        <input type="hidden" name="ID_Pelanggan" readonly value="<?php echo $row['ID_Pelanggan']; ?>">

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $row['email']; ?>"><br>
        
        <label>Kata Sandi:</label><br>
        <input type="password" name="kataSandi" id="kataSandi" maxlength="10" value="<?php echo $row['kataSandi']; ?>">
        <br>
        
        <label>Alamat:</label><br>
        <input type="text" name="alamat" value="<?php echo $row['alamat']; ?>"><br>

        <label>Pembuatan Akun:</label><br>
        <input type="text" name="pembuatanAkun" readonly value="<?php echo $row['pembuatanAkun']; ?>"><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>