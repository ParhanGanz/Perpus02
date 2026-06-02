<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$query = mysqli_query($conn, "
    SELECT
        buku.*,
        kategori.nama_kategori
    FROM buku
    LEFT JOIN kategori
        ON buku.id_kategori = kategori.id_kategori
    WHERE
        buku.judul LIKE '%$search%'
        OR buku.penulis LIKE '%$search%'
        OR buku.isbn LIKE '%$search%'
        OR buku.tahun_terbit LIKE '%$search%'
        OR kategori.nama_kategori LIKE '%$search%'
    ORDER BY buku.id_buku ASC
");

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-book text-teal-700"></i> Katalog Buku
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelola seluruh koleksi buku, cek ketersediaan stok, dan sirkulasi kategori.
            </p>
        </div>
        <div>
            <a href="tambah.php" class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-sm transition-colors">
                <i class="fas fa-plus text-xs"></i> Tambah Buku
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari judul buku, penulis, tahun, ISBN, atau kategori..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-gray-50 focus:bg-white transition-all">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        Filter Data
                    </button>
                    <?php if (!empty($search)) : ?>
                        <a href="index.php" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center justify-center" title="Reset Pencarian">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
            <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider">
                Daftar Inventaris Buku
            </h2>
            <span class="text-xs bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full font-medium">
                Total: <?= mysqli_num_rows($query) ?> Buku
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] table-auto">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left w-[40%]">Informasi Buku</th>
                        <th class="px-6 py-4 text-left w-[15%]">Kategori</th>
                        <th class="px-6 py-4 text-center w-[12%]">Tahun Terbit</th>
                        <th class="px-6 py-4 text-center w-[10%]">Stok Buku</th>
                        <th class="px-6 py-4 text-center w-[10%]">Status</th>
                        <th class="px-6 py-4 text-center w-[13%]">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (mysqli_num_rows($query) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($row['cover'])) : ?>
                                                <img src="../../assets/img/<?= $row['cover'] ?>" class="w-12 h-16 object-cover rounded-md shadow-sm border border-gray-100">
                                            <?php else : ?>
                                                <div class="w-12 h-16 bg-gray-200 rounded-md border border-gray-300 flex flex-col items-center justify-center text-[10px] text-gray-400 font-medium">
                                                    <i class="fas fa-book-open text-base mb-1 text-gray-300"></i> NO COV
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="space-y-0.5">
                                            <div class="font-semibold text-gray-900 text-base line-clamp-1">
                                                <?= htmlspecialchars($row['judul']) ?>
                                            </div>
                                            <div class="text-xs text-gray-500 font-medium">
                                                Penulis: <span class="text-gray-700"><?= htmlspecialchars($row['penulis']) ?></span>
                                            </div>
                                            <div class="inline-block mt-1 text-[11px] font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded border border-gray-200">
                                                ISBN: <?= htmlspecialchars($row['isbn'] ?: '-') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-800 bg-teal-50 border border-teal-200/60 px-2.5 py-1 rounded-md">
                                        <i class="fas fa-tag text-[10px] text-teal-600"></i>
                                        <?= htmlspecialchars($row['nama_kategori'] ?: 'Tanpa Kategori') ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center font-medium text-gray-600">
                                    <span class="bg-gray-100 px-2.5 py-1 rounded-lg text-xs border border-gray-200/80">
                                        <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                        <?= htmlspecialchars($row['tahun_terbit'] ?: '-') ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center font-bold text-gray-800">
                                    <?= $row['stok'] ?> <span class="text-xs font-normal text-gray-400">eks</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <?php if ($row['stok'] > 0) : ?>
                                        <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            Tersedia
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-block bg-rose-50 text-rose-600 border border-rose-200 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            Kosong
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="edit.php?id=<?= $row['id_buku'] ?>" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow-sm transition-colors" title="Edit Data">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $row['id_buku'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini dari katalog?')" class="inline-flex items-center justify-center w-8 h-8 bg-rose-500 hover:bg-rose-600 text-white rounded-lg shadow-sm transition-colors" title="Hapus Data">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center py-16 text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-book-open text-4xl text-gray-200"></i>
                                    <p class="text-sm font-medium">Buku yang Anda cari tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>