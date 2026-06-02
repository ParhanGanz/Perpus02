<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

// VALIDASI ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_kategori = (int) $_GET['id'];

// HAPUS DATA
mysqli_query(
    $conn,
    "DELETE FROM kategori
     WHERE id_kategori = $id_kategori"
);

header("Location: index.php");
exit;