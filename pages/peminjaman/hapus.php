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

// AMBIL DATA
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

// CEK STATUS
if ($data['status'] == 'dipinjam') {

    echo "
    <script>
        alert('Data tidak dapat dihapus karena buku belum dikembalikan!');
        window.location='index.php';
    </script>";
    exit;
}

// HAPUS DATA
mysqli_query(
    $conn,
    "DELETE FROM peminjaman
     WHERE id_peminjaman = $id_peminjaman"
);

header("Location: index.php");
exit;