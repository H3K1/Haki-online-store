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

// Hapus data jika parameter delete ada
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $deleteSql = "DELETE FROM penjual WHERE ID_Penjual = '$idToDelete'";
    $conn->query($deleteSql);
}

// Query untuk mengambil semua data dari tabel detail_penjualan
$sql = "SELECT * FROM penjual";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css"> <!-- Link ke file CSS -->
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = "?delete=" + id;
            }
        }
    </script>
</head>
<body>
    <h2>Data Penjualan</h2>
    <table>
        <tr>
            <th>ID Penjual</th>
            <th>ID Pelanggan</th>
            <th>namaToko</th>
            <th>lokasiToko</th>
            <th>pembuatanAkun</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['ID_Penjual']; ?></td>
            <td><?php echo $row['ID_Pelanggan']; ?></td>
            <td><?php echo $row['namaToko']; ?></td>
            <td><?php echo $row['lokasiToko']; ?></td>
            <td><?php echo $row['pembuatanAkun']; ?></td>
            <td><a href="../updateFunction/UPDATE_penjual.php?id=<?php echo $row['ID_Penjual']; ?>">Edit</a> | 
            <a href="#" onclick="confirmDelete('<?php echo $row['ID_Penjual']; ?>')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <footer style="margin-top: 30px; text-align: center;">
            <a href="pelanggan.php" class="btn">Ke Data Pelanggan</a>
            <a href="penjual.php" class="btn">Ke Data Penjual</a>
            <a href="produk.php" class="btn">Ke Data Produk</a>
            <a href="../index.html" class="btn">Halaman Utama</a>
        </footer>
</body>
</html>

<?php
$conn->close();
?>