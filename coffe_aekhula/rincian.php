<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

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
    <title>Rincian Pemesanan - Aplikasi Kasir Coffe Aekhula</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url(images/gambar3.jpeg);
            background-size: cover;
            background-position: center;
            color: black
        }
        h1 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5em;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .subtotal {
            font-weight: bold;
            font-size: 1.5em;
            text-align: right;
            padding: 20px 0;
            margin-top: 20px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .back-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rincian Pemesanan Coffe Aekhula</h1>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Nama Pemesan</th>
                    <th>Jumlah</th>
                    <th>Total Bayar</th>
                    <th>Tanggal Pemesanan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pemesananList as $pemesanan): ?>
                <tr>
                    <td><?php echo $pemesanan['id']; ?></td>
                    <td><?php echo $pemesanan['produk_nama']; ?></td>
                    <td><?php echo $pemesanan['pelanggan_nama']; ?></td>
                    <td><?php echo $pemesanan['jumlah']; ?></td>
                    <td>Rp <?php echo number_format($pemesanan['total_bayar'], 2, ',', '.'); ?></td>
                    <td><?php echo date('d-m-Y H:i', strtotime($pemesanan['tanggal_pemesanan'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="subtotal">Subtotal : Rp <?php echo number_format($totalBayarSemua, 2, ',', '.'); ?></div>
        <a href="index.php" class="back-button">Kembali</a>
    </div>
</body>
</html>
