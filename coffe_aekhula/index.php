<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Menambahkan pesanan baru
$message = '';
if (isset($_POST['submit'])) {
    $produk_id = $_POST['produk_id'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $jumlah = $_POST['jumlah'];

    // Menghitung total harga berdasarkan produk
    $query = "SELECT harga FROM produk WHERE id = $produk_id";
    $result = mysqli_query($koneksi, $query);
    $harga = mysqli_fetch_assoc($result)['harga'];

    // Menghitung total bayar
    $total_bayar = $harga * $jumlah;

    // Menyimpan pemesanan
    $insertQuery = "INSERT INTO pemesanan (produk_id, pelanggan_nama, jumlah, total_bayar, tanggal_pemesanan) VALUES ($produk_id, '$nama_pemesan', $jumlah, $total_bayar, NOW())";
    mysqli_query($koneksi, $insertQuery);

    // Menampilkan pesan sukses
    $message = "Pemesanan berhasil disimpan!";
}

// Ambil data produk
$produkQuery = "SELECT * FROM produk";
$produkResult = mysqli_query($koneksi, $produkQuery);
$produks = mysqli_fetch_all($produkResult, MYSQLI_ASSOC);

// Ambil data pemesanan
$pemesananQuery = "SELECT p.id, p.jumlah, p.total_bayar, p.tanggal_pemesanan, pr.nama AS produk_nama, p.pelanggan_nama 
                   FROM pemesanan p 
                   LEFT JOIN produk pr ON p.produk_id = pr.id";
$pemesananResult = mysqli_query($koneksi, $pemesananQuery);
$pemesananList = mysqli_fetch_all($pemesananResult, MYSQLI_ASSOC);

// Menghitung subtotal total bayar
$totalBayarSemua = 0;
foreach ($pemesananList as $pemesanan) {
    $totalBayarSemua += $pemesanan['total_bayar'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir Coffe Aekhula</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url(images/gambar3.jpeg);
            background-size: cover;
            background-position: center;
            color: black;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: rgba(76, 175, 80, 0.8);
            color: white;
            border-radius: 10px;
            width: 100%;
            max-width: 1200px;
        }
        .header h1 {
            margin: 0;
            font-size: 2em;
        }
        .header .logout {
            font-size: 1.5em;
            color: white;
            text-decoration: none;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .form-container, .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            transition: transform 0.3s;
        }
        .form-container:hover, .table-container:hover {
            transform: translateY(-5px);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        select, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            max-height: 300px; /* Batas tinggi */
            overflow-y: auto; /* Scroll vertikal */
            display: block; /* Agar scroll bisa muncul */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }
        .edit {
            background-color: #007bff;
        }
        .delete {
            background-color: #e63946;
        }
        .message {
            color: #4CAF50;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
        .subtotal {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kasir Coffe Aekhula</h1>
        <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
    </div>
    <div class="container">
        <div class="form-container">
            <h2>Tambah Pemesanan</h2>
            <form method="post" action="">
                <label for="produk_id">Pilih Produk:</label>
                <select name="produk_id" required>
                    <?php foreach ($produks as $produk): ?>
                        <option value="<?php echo $produk['id']; ?>"><?php echo $produk['nama']; ?> (Rp <?php echo number_format($produk['harga'], 2, ',', '.'); ?>)</option>
                    <?php endforeach; ?>
                </select>

                <label for="nama_pemesan">Nama Pemesan:</label>
                <input type="text" name="nama_pemesan" required>

                <label for="jumlah">Jumlah:</label>
                <input type="number" name="jumlah" min="1" required>

                <button type="submit" name="submit">Simpan Pemesanan</button>
            </form>
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>

        <div class="table-container">
            <h2>Daftar Pemesanan</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Nama Pemesan</th>
                        <th>Jumlah</th>
                        <th>Total Bayar</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($pemesananList as $pemesanan): ?>
                    <tr>
                        <td><?php echo $index++; ?></td> <!-- ID dimulai dari 1 -->
                        <td><?php echo $pemesanan['produk_nama']; ?></td>
                        <td><?php echo $pemesanan['pelanggan_nama']; ?></td>
                        <td><?php echo $pemesanan['jumlah']; ?></td>
                        <td>Rp <?php echo number_format($pemesanan['total_bayar'], 2, ',', '.'); ?></td>
                        <td><?php echo date('d-m-Y H:i', strtotime($pemesanan['tanggal_pemesanan'])); ?></td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?php echo $pemesanan['id']; ?>" class="edit">Edit</a>
                            <a href="delete.php?id=<?php echo $pemesanan['id']; ?>" class="delete">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="subtotal">Subtotal : Rp <?php echo number_format($totalBayarSemua, 2, ',', '.'); ?></div>
            <form method="post" action="rincian.php">
                <button type="submit">Lihat Rincian</button>
            </form>
        </div>
    </div>
</body>
</html>
