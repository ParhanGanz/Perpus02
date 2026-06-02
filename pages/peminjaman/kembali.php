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

$id_peminjaman = (int) $_GET['id'];

// AMBIL DATA PEMINJAMAN
$query = mysqli_query(
    $conn,
    "SELECT *
     FROM peminjaman
     WHERE id_peminjaman = $id_peminjaman"
);

if (mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// CEK JIKA MASIH DIPINJAM
if ($data['status'] == 'dipinjam') {

    // UPDATE STATUS
    mysqli_query(
        $conn,
        "UPDATE peminjaman
         SET status = 'dikembalikan'
         WHERE id_peminjaman = $id_peminjaman"
    );

    // TAMBAH STOK BUKU
    mysqli_query(
        $conn,
        "UPDATE buku
         SET stok = stok + 1
         WHERE id_buku = {$data['id_buku']}"
    );
}

header("Location: index.php");
exit;