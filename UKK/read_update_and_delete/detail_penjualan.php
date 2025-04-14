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
    $deleteSql = "DELETE FROM detail_penjualan WHERE ID_Detail_penjualan = '$idToDelete'";
    $conn->query($deleteSql);
}

// Query untuk mengambil semua data dari tabel detail_penjualan
$sql = "SELECT * FROM detail_penjualan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 🔍 SEO Meta Tags -->
    <title>Data Detail Penjualan | Manajemen Penjualan Produk</title>
    <meta name="description" content="Halaman untuk melihat, mengedit, dan menghapus data detail penjualan pelanggan, termasuk jumlah barang, harga total, dan metode pembayaran.">
    <meta name="keywords" content="penjualan, data transaksi, detail penjualan, produk, pelanggan, metode pembayaran">
    <meta name="author" content="YourCompanyName">

    <!-- 🟢 Open Graph untuk Sosial Media -->
    <meta property="og:title" content="Data Detail Penjualan">
    <meta property="og:description" content="Lihat dan kelola data transaksi penjualan secara lengkap dan terstruktur.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://yourwebsite.com/detail_penjualan.php">
    <meta property="og:image" content="http://yourwebsite.com/assets/images/sales.png">

    <!-- 🔗 CSS -->
    <link rel="stylesheet" href="../style/style.css">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <!-- 🧠 Struktur data (Schema.org) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Data Detail Penjualan",
        "description": "Halaman untuk mengelola data penjualan pelanggan termasuk produk, jumlah, dan pembayaran.",
        "author": {
            "@type": "Organization",
            "name": "YourCompanyName"
        }
    }
    </script>

    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = "?delete=" + id;
            }
        }
    </script>
</head>
<body>
    <main>
        <header>
            <h1>Data Detail Penjualan</h1>
            <p>Kelola data transaksi pelanggan dengan mudah dan cepat.</p>
        </header>

        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Detail Penjualan</th>
                        <th>ID Pelanggan</th>
                        <th>ID Produk</th>
                        <th>Jumlah Barang</th>
                        <th>Total Harga</th>
                        <th>Tanggal Pembelian</th>
                        <th>Metode Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ID_Detail_penjualan']; ?></td>
                        <td><?php echo $row['ID_Pelanggan']; ?></td>
                        <td><?php echo $row['ID_Produk']; ?></td>
                        <td><?php echo $row['jumlahBarang']; ?></td>
                        <td><?php echo $row['totalHarga']; ?></td>
                        <td><?php echo $row['tanggalPembelian']; ?></td>
                        <td><?php echo $row['metode_pembayaran']; ?></td>
                        <td>
                            <a href="../updateFunction/UPDATE_detail_penjualan.php?id=<?php echo $row['ID_Detail_penjualan']; ?>">Edit</a> | 
                            <a href="#" onclick="confirmDelete('<?php echo $row['ID_Detail_penjualan']; ?>')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>

        <footer style="margin-top: 30px; text-align: center;">
            <a href="pelanggan.php" class="btn">Ke Data Pelanggan</a>
            <a href="penjual.php" class="btn">Ke Data Penjual</a>
            <a href="produk.php" class="btn">Ke Data Produk</a>
            <a href="../index.html" class="btn">Halaman Utama</a>
        </footer>
    </main>
</body>
</html>

<?php
$conn->close();
?>