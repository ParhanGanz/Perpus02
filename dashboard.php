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

    <div class="bg-gradient-to-r from-teal-800 to-teal-700 rounded-2xl p-6 md:p-8 text-white shadow-sm relative overflow-hidden">
        <div class="relative z-10 max-w-xl">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                Halo, Pustakawan <?= htmlspecialchars($_SESSION['username']) ?>! 👋
            </h1>
            <p class="text-teal-100 text-sm mt-2 leading-relaxed">
                Selamat datang kembali di panel administrasi E-Pustaka. Pantau ketersediaan buku, aktivitas peminjaman siswa, dan kelola member dengan mudah hari ini.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-teal-600/20 rounded-full blur-xl"></div>
        <div class="absolute right-20 -top-10 w-32 h-32 bg-teal-500/10 rounded-full blur-lg"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Buku</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1"><?= $totalBuku ?></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center text-lg">
                <i class="fas fa-book"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Anggota</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1"><?= $totalAnggota ?></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1"><?= $totalKategori ?></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fas fa-tags"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dipinjam</p>
                <h3 class="text-2xl font-extrabold text-amber-600 mt-1"><?= $totalDipinjam ?></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Aman</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1"><?= $bukuTersedia ?? 0 ?> <span class="text-xs font-medium text-gray-400">eks</span></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Habis</p>
                <h3 class="text-2xl font-extrabold text-rose-600 mt-1"><?= $bukuHabis ?> <span class="text-xs font-medium text-gray-400">judul</span></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-4">
            Akses Cepat Modul Utama
        </h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            
            <a href="pages/buku/index.php" class="group bg-gradient-to-br from-teal-50 to-emerald-50/40 hover:from-teal-700 hover:to-teal-800 rounded-xl p-4 text-center border border-teal-100 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <div class="w-12 h-12 rounded-full bg-white text-teal-700 shadow-sm mx-auto flex items-center justify-center text-lg group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="fas fa-book"></i>
                </div>
                <div class="mt-3 font-semibold text-gray-800 text-sm group-hover:text-white transition-colors">Katalog Buku</div>
            </a>

            <a href="pages/anggota/index.php" class="group bg-gradient-to-br from-blue-50 to-indigo-50/40 hover:from-blue-600 hover:to-blue-700 rounded-xl p-4 text-center border border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <div class="w-12 h-12 rounded-full bg-white text-blue-600 shadow-sm mx-auto flex items-center justify-center text-lg group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i class="fas fa-users"></i>
                </div>
                <div class="mt-3 font-semibold text-gray-800 text-sm group-hover:text-white transition-colors">Data Anggota</div>
            </a>

            <a href="pages/peminjaman/index.php" class="group bg-gradient-to-br from-amber-50 to-orange-50/40 hover:from-amber-500 hover:to-amber-600 rounded-xl p-4 text-center border border-amber-100 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <div class="w-12 h-12 rounded-full bg-white text-amber-600 shadow-sm mx-auto flex items-center justify-center text-lg group-hover:bg-amber-400 group-hover:text-white transition-colors">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="mt-3 font-semibold text-gray-800 text-sm group-hover:text-white transition-colors">Sirkulasi Pinjam</div>
            </a>

            <a href="pages/laporan/index.php" class="group bg-gradient-to-br from-purple-50 to-fuchsia-50/40 hover:from-purple-600 hover:to-purple-700 rounded-xl p-4 text-center border border-purple-100 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <div class="w-12 h-12 rounded-full bg-white text-purple-600 shadow-sm mx-auto flex items-center justify-center text-lg group-hover:bg-purple-500 group-hover:text-white transition-colors">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="mt-3 font-semibold text-gray-800 text-sm group-hover:text-white transition-colors">Laporan Mutasi</div>
            </a>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex items-center justify-between">
            <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider">
                Aktivitas Transaksi Terbaru
            </h2>
            <a href="pages/peminjaman/index.php" class="text-xs font-semibold text-teal-700 hover:text-teal-800 hover:underline">
                Lihat Semua Log <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] table-auto">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold border-b border-gray-200 uppercase">
                    <tr>
                        <th class="px-6 py-3.5 text-left">Nama Member</th>
                        <th class="px-6 py-3.5 text-left">Buku Terkait</th>
                        <th class="px-6 py-3.5 text-center">Status Aktivitas</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (mysqli_num_rows($peminjamanTerbaru) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($peminjamanTerbaru)) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-teal-600"></div>
                                        <?= htmlspecialchars($row['nama']) ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    <div class="line-clamp-1" title="<?= htmlspecialchars($row['judul']) ?>">
                                        <?= htmlspecialchars($row['judul']) ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <?php if ($row['status'] == 'dipinjam') : ?>
                                        <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            <i class="fas fa-clock mr-1 text-[10px]"></i> Dipinjam
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            <i class="fas fa-circle-check mr-1 text-[10px]"></i> Dikembalikan
                                        </span>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="text-center py-12 text-gray-400">
                                <i class="fas fa-history text-3xl mb-2 text-gray-200 block"></i>
                                Belum ada riwayat sirkulasi buku hari ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>