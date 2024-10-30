<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM pemesanan WHERE id = $id";
    mysqli_query($koneksi, $deleteQuery);
    header("Location: index.php");
}
?>
