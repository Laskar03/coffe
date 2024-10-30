<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $pemesananQuery = "SELECT * FROM pemesanan WHERE id = $id";
    $pemesananResult = mysqli_query($koneksi, $pemesananQuery);
    $pemesanan = mysqli_fetch_assoc($pemesananResult);

    // Ambil data produk
    $produkQuery = "SELECT * FROM produk";
    $produkResult = mysqli_query($koneksi, $produkQuery);
    $produks = mysqli_fetch_all($produkResult, MYSQLI_ASSOC);
}

if (isset($_POST['update'])) {
    $produk_id = $_POST['produk_id'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $jumlah = $_POST['jumlah'];

    $updateQuery = "UPDATE pemesanan SET produk_id = $produk_id, pelanggan_nama = '$nama_pemesan', jumlah = $jumlah WHERE id = $id";
    mysqli_query($koneksi, $updateQuery);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pemesanan - Aplikasi Kasir Coffe Aekhula</title>
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
            color: black;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5em;
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
            margin-top: 20px;
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
        .back-button {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }
        .back-button a {
            text-decoration: none;  
            color: white;
            background-color: #4CAF50;
            padding: 10px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        .back-button a i {
            margin-right: 8px; /* Jarak antara ikon dan teks */
        }
        .back-button a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> 
            </a>
        </div>
        <h1>Edit Pemesanan Coffe Aekhula</h1>
        <form method="post" action="">
            <label for="produk_id">Pilih Produk:</label>
            <select name="produk_id" required>
                <?php foreach ($produks as $produk): ?>
                    <option value="<?php echo $produk['id']; ?>" <?php echo $produk['id'] == $pemesanan['produk_id'] ? 'selected' : ''; ?>>
                        <?php echo $produk['nama']; ?> (Rp <?php echo number_format($produk['harga'], 2, ',', '.'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nama_pemesan">Nama Pemesan:</label>
            <input type="text" name="nama_pemesan" value="<?php echo $pemesanan['pelanggan_nama']; ?>" required>

            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" value="<?php echo $pemesanan['jumlah']; ?>" min="1" required>

            <button type="submit" name="update">Update Pemesanan</button>
        </form>
    </div>
</body>
</html>
