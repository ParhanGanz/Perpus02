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
    SELECT *
    FROM anggota
    WHERE
        nama LIKE '%$search%'
        OR telepon LIKE '%$search%'
        OR alamat LIKE '%$search%'
    ORDER BY id_anggota ASC
");

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-users text-teal-700"></i> Data Anggota
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data keanggotaan perpustakaan, validasi kontak, dan pantau tanggal registrasi.
            </p>
        </div>
        <div>
            <a href="tambah.php" class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-sm transition-colors">
                <i class="fas fa-user-plus text-xs"></i> Tambah Anggota
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
                        placeholder="Cari nama anggota, nomor telepon, atau alamat..."
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
                Daftar Member Aktif
            </h2>
            <span class="text-xs bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full font-medium">
                Total: <?= mysqli_num_rows($query) ?> Anggota
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] table-auto">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-center w-[8%]">No</th>
                        <th class="px-6 py-4 text-left w-[35%]">Profil Anggota</th>
                        <th class="px-6 py-4 text-left w-[17%]">No. Telepon</th>
                        <th class="px-6 py-4 text-left w-[25%]">Alamat</th>
                        <th class="px-6 py-4 text-center w-[15%]">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-center w-[13%]">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (mysqli_num_rows($query) > 0) : ?>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($query)) :
                            // Ambil inisial huruf pertama nama untuk avatar alternatif
                            $initial = strtoupper(substr($row['nama'], 0, 1));
                            // Tentukan warna avatar berdasarkan jenis kelamin
                            $avatarBg = ($row['jenis_kelamin'] == 'L') ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700';
                        ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <td class="px-6 py-4 text-center font-medium text-gray-400">
                                    <?= $no++ ?>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full <?= $avatarBg ?> flex items-center justify-center font-bold text-sm flex-shrink-0 border border-white shadow-sm">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 text-base">
                                                <?= htmlspecialchars($row['nama']) ?>
                                            </div>
                                            <div class="mt-0.5">
                                                <?php if ($row['jenis_kelamin'] == 'L') : ?>
                                                    <span class="inline-flex items-center text-[11px] font-medium bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">
                                                        <i class="fas fa-mars mr-1 text-[10px]"></i> Laki-laki
                                                    </span>
                                                <?php else : ?>
                                                    <span class="inline-flex items-center text-[11px] font-medium bg-pink-50 text-pink-700 px-2 py-0.5 rounded border border-pink-100">
                                                        <i class="fas fa-venus mr-1 text-[10px]"></i> Perempuan
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-mono text-xs text-gray-600">
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                        <i class="fas fa-phone text-gray-400 text-[10px]"></i>
                                        <?= htmlspecialchars($row['telepon'] ?: '-') ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600 max-w-[200px]">
                                    <p class="truncate text-sm" title="<?= htmlspecialchars($row['alamat']) ?>">
                                        <?= htmlspecialchars($row['alamat'] ?: '-') ?>
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-center font-medium text-gray-600">
                                    <span class="text-xs bg-gray-50 border border-gray-200/60 px-2 py-1 rounded-md">
                                        <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                        <?= date('d M Y', strtotime($row['tanggal_daftar'])) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="edit.php?id=<?= $row['id_anggota'] ?>" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow-sm transition-colors" title="Edit Anggota">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $row['id_anggota'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')" class="inline-flex items-center justify-center w-8 h-8 bg-rose-500 hover:bg-rose-600 text-white rounded-lg shadow-sm transition-colors" title="Hapus Anggota">
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
                                    <i class="fas fa-users-slash text-4xl text-gray-200"></i>
                                    <p class="text-sm font-medium">Data anggota tidak ditemukan.</p>
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