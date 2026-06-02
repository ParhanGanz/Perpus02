<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_buku = (int) $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT cover
     FROM buku
     WHERE id_buku = $id_buku"
);

$data = mysqli_fetch_assoc($query);

// HAPUS FILE COVER
if (
    !empty($data['cover']) &&
    file_exists(
        __DIR__ . '/../../assets/img/' . $data['cover']
    )
) {
    unlink(
        __DIR__ . '/../../assets/img/' . $data['cover']
    );
}

// HAPUS DATABASE
mysqli_query(
    $conn,
    "DELETE FROM buku
     WHERE id_buku = $id_buku"
);

header("Location: index.php");
exit;