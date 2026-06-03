<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth/login.php");
    exit;
}

include __DIR__ . '/config/koneksi.php';

$totalBuku = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM buku")
)['total'];

$totalAnggota = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM anggota")
)['total'];

$totalKategori = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM kategori")
)['total'];

$totalDipinjam = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM peminjaman
         WHERE status='dipinjam'"
    )
)['total'];

$bukuTersedia = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(stok) total
         FROM buku"
    )
)['total'];

$bukuHabis = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM buku
         WHERE stok = 0"
    )
)['total'];

$peminjamanTerbaru = mysqli_query(
    $conn,
    "SELECT
        p.*,
        a.nama,
        b.judul
     FROM peminjaman p
     JOIN anggota a
        ON p.id_anggota = a.id_anggota
     JOIN buku b
        ON p.id_buku = b.id_buku
     ORDER BY p.id_peminjaman DESC
     LIMIT 5"
);

include __DIR__ . '/layouts/header.php';
include __DIR__ . '/layouts/sidebar.php';
include __DIR__ . '/layouts/topbar.php';
?>

<div class="space-y-6">

    <div class="bg-teal-800 rounded-xl p-6 text-white border border-teal-900">
        <h1 class="text-2xl font-bold tracking-tight">
            Halo, Pustakawan <?= htmlspecialchars($_SESSION['username']) ?>! 👋
        </h1>
        <p class="text-teal-100 text-sm mt-1">
            Selamat datang di panel administrasi E-Pustaka. Kelola aktivitas perpustakaan melalui menu di bawah ini.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Total Buku</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $totalBuku ?></h3>
            </div>
            <div class="text-teal-700 text-lg">
                <i class="fas fa-book"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Anggota</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $totalAnggota ?></h3>
            </div>
            <div class="text-blue-600 text-lg">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Kategori</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $totalKategori ?></h3>
            </div>
            <div class="text-purple-600 text-lg">
                <i class="fas fa-tags"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Dipinjam</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1"><?= $totalDipinjam ?></h3>
            </div>
            <div class="text-amber-600 text-lg">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-4">
            Akses Cepat Modul Utama
        </h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            
            <a href="pages/buku/index.php" class="bg-teal-700 hover:bg-teal-800 text-white rounded-lg p-4 text-center transition-colors">
                <i class="fas fa-book text-xl block mb-2"></i>
                <span class="text-sm font-medium">Katalog Buku</span>
            </a>

            <a href="pages/anggota/index.php" class="bg-blue-700 hover:bg-blue-800 text-white rounded-lg p-4 text-center transition-colors">
                <i class="fas fa-users text-xl block mb-2"></i>
                <span class="text-sm font-medium">Data Anggota</span>
            </a>

            <a href="pages/peminjaman/index.php" class="bg-amber-600 hover:bg-amber-700 text-white rounded-lg p-4 text-center transition-colors">
                <i class="fas fa-exchange-alt text-xl block mb-2"></i>
                <span class="text-sm font-medium">Peminjaman</span>
            </a>

            <a href="pages/laporan/index.php" class="bg-purple-700 hover:bg-purple-800 text-white rounded-lg p-4 text-center transition-colors">
                <i class="fas fa-chart-bar text-xl block mb-2"></i>
                <span class="text-sm font-medium">Laporan Tahunan</span>
            </a>

        </div>
    </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>