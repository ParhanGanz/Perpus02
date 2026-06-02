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

$id_anggota = (int) $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM anggota
     WHERE id_anggota = $id_anggota"
);

header("Location: index.php");
exit;