<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, trim($_GET['search']))
    : '';

$query = mysqli_query($conn, "
    SELECT
        peminjaman.*,
        anggota.nama,
        buku.judul
    FROM peminjaman
    JOIN anggota
        ON peminjaman.id_anggota = anggota.id_anggota
    JOIN buku
        ON peminjaman.id_buku = buku.id_buku
    WHERE
        anggota.nama LIKE '%$search%'
        OR buku.judul LIKE '%$search%'
    ORDER BY peminjaman.id_peminjaman DESC
");

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-exchange-alt text-teal-700"></i> Transaksi Peminjaman
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Pantau sirkulasi peminjaman buku, proses pengembalian, dan cek status keterlambatan.
            </p>
        </div>
        <div>
            <a href="tambah.php" class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-sm transition-colors">
                <i class="fas fa-plus text-xs"></i> Tambah Peminjaman
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
                        placeholder="Cari nama anggota peminjam atau judul buku..."
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
                Log Transaksi Sirkulasi
            </h2>
            <span class="text-xs bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full font-medium">
                Total: <?= mysqli_num_rows($query) ?> Transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] table-auto">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-center w-[6%]">No</th>
                        <th class="px-6 py-4 text-left w-[24%]">Nama Anggota</th>
                        <th class="px-6 py-4 text-left w-[28%]">Judul Buku</th>
                        <th class="px-6 py-4 text-center w-[14%]">Tgl Pinjam</th>
                        <th class="px-6 py-4 text-center w-[14%]">Tgl Kembali</th>
                        <th class="px-6 py-4 text-center w-[12%]">Status</th>
                        <th class="px-6 py-4 text-center w-[12%]">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (mysqli_num_rows($query) > 0) : ?>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($query)) :
                            // Variabel style status dinamis
                            $isDipinjam = ($row['status'] == 'dipinjam');
                            $statusBadge = $isDipinjam
                                ? 'bg-amber-50 text-amber-700 border-amber-200'
                                : 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">

                                <td class="px-6 py-4 text-center font-medium text-gray-400">
                                    <?= $no++ ?>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center text-xs font-bold">
                                            <i class="fas fa-user text-[10px]"></i>
                                        </div>
                                        <span class="font-semibold text-gray-900"><?= htmlspecialchars($row['nama']) ?></span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800 line-clamp-1" title="<?= htmlspecialchars($row['judul']) ?>">
                                        <?= htmlspecialchars($row['judul']) ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center text-xs text-gray-600 bg-gray-50 border border-gray-200/60 px-2 py-1 rounded-md font-medium">
                                        <i class="far fa-calendar text-gray-400 mr-1.5 text-[11px]"></i>
                                        <?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center text-xs text-gray-600 bg-gray-50 border border-gray-200/60 px-2 py-1 rounded-md font-medium">
                                        <i class="far fa-calendar-check text-gray-400 mr-1.5 text-[11px]"></i>
                                        <?= date('d M Y', strtotime($row['tanggal_kembali'])) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block border text-xs px-2.5 py-1 rounded-full font-semibold capitalize tracking-wide <?= $statusBadge ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <?php if ($row['status'] == 'dipinjam') : ?>
                                            <a
                                                href="kembali.php?id=<?= $row['id_peminjaman'] ?>"
                                                onclick="return confirm('Konfirmasi pengembalian buku?')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg">
                                                ✔️
                                            </a>
                                        <?php else : ?>
                                            <a
                                                href="hapus.php?id=<?= $row['id_peminjaman'] ?>"
                                                onclick="return confirm('Hapus riwayat peminjaman ini?')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                                🗑️
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-folder-open text-4xl text-gray-200"></i>
                                    <p class="text-sm font-medium">Belum ada riwayat log transaksi peminjaman.</p>
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