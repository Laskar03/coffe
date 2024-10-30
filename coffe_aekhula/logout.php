<?php
session_start(); // Memulai sesi

// Menghapus semua data sesi
$_SESSION = array();

// Menghancurkan sesi
session_destroy();

// Mengalihkan ke halaman login
header("Location: login.php");
exit;
?>
