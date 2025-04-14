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
    $deleteSql = "DELETE FROM produk WHERE ID_Produk = '$idToDelete'";
    $conn->query($deleteSql);
}

// Cek apakah ada pencarian
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$sql = "SELECT * FROM produk";
if (!empty($search)) {
    $sql .= " WHERE namaProduk LIKE '%$search%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Produk</title>
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
    <h2>Data Produk</h2>

    <!-- Search form -->
    <div class="search-container">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Cari nama produk..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
            <button type="submit" class="btn">Cari</button>
            <a href="produk.php" class="btn" style="background-color: #95a5a6;">Reset</a>
        </form>
    </div>

    <!-- Optional result info -->
    <?php if (!empty($search)) { ?>
        <p style="text-align: center; margin-bottom: 10px;">Menampilkan hasil pencarian untuk <strong>"<?php echo htmlspecialchars($search); ?>"</strong></p>
    <?php } ?>

    <div class="table-container">
        <table>
            <tr>
                <th>ID Produk</th>
                <th>ID Penjual</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Pembuatan Produk</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['ID_Produk']; ?></td>
                <td><?php echo $row['ID_Penjual']; ?></td>
                <td><?php echo $row['namaProduk']; ?></td>
                <td><?php echo $row['deskripsi']; ?></td>
                <td><?php echo $row['stok']; ?></td>
                <td><?php echo $row['harga']; ?></td>
                <td><?php echo $row['pembuatanProduk']; ?></td>
                <td>
                    <a href="../updateFunction/UPDATE_produk.php?id=<?php echo $row['ID_Produk']; ?>">Edit</a> | 
                    <a href="#" onclick="confirmDelete('<?php echo $row['ID_Produk']; ?>')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
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