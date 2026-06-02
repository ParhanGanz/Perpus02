<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

$jenis = $_GET['jenis'] ?? '';

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Laporan Perpustakaan
            </h1>

            <p class="text-slate-500 mt-1">
                Cetak dan lihat data perpustakaan
            </p>

        </div>

        <?php if (!empty($jenis)) : ?>

            <button
                onclick="window.print()"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl">

                🖨️ Cetak

            </button>

        <?php endif; ?>

    </div>

    <!-- PILIH LAPORAN -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">

        <form method="GET">

            <div class="flex flex-col md:flex-row gap-4">

                <select
                    name="jenis"
                    class="flex-1 border border-slate-300 rounded-xl px-4 py-3"
                    required>

                    <option value="">
                        Pilih Jenis Laporan
                    </option>

                    <option value="buku"
                        <?= $jenis == 'buku' ? 'selected' : '' ?>>
                        Laporan Buku
                    </option>

                    <option value="anggota"
                        <?= $jenis == 'anggota' ? 'selected' : '' ?>>
                        Laporan Anggota
                    </option>

                    <option value="peminjaman"
                        <?= $jenis == 'peminjaman' ? 'selected' : '' ?>>
                        Laporan Peminjaman
                    </option>

                </select>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                    Tampilkan

                </button>

            </div>

        </form>

    </div>

    <!-- LAPORAN BUKU -->
    <?php if ($jenis == 'buku') : ?>

        <?php
        $query = mysqli_query($conn, "
            SELECT
                buku.*,
                kategori.nama_kategori
            FROM buku
            LEFT JOIN kategori
                ON buku.id_kategori = kategori.id_kategori
            ORDER BY buku.judul ASC
        ");
        ?>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="p-5 border-b">

                <h2 class="font-semibold text-lg">
                    Laporan Data Buku
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-100">

                        <tr>
                            <th class="px-4 py-3 text-left">ISBN</th>
                            <th class="px-4 py-3 text-left">Judul</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-center">Stok</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                            <tr class="border-b">

                                <td class="px-4 py-3">
                                    <?= $row['isbn'] ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($row['judul']) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($row['nama_kategori']) ?>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <?= $row['stok'] ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

    <!-- LAPORAN ANGGOTA -->
    <?php if ($jenis == 'anggota') : ?>

        <?php
        $query = mysqli_query($conn, "
            SELECT *
            FROM anggota
            ORDER BY nama ASC
        ");
        ?>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="p-5 border-b">

                <h2 class="font-semibold text-lg">
                    Laporan Data Anggota
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-100">

                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">JK</th>
                            <th class="px-4 py-3">Telepon</th>
                            <th class="px-4 py-3">Tanggal Daftar</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                            <tr class="border-b">

                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($row['nama']) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= $row['jenis_kelamin'] ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($row['telepon']) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= date('d-m-Y', strtotime($row['tanggal_daftar'])) ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

    <!-- LAPORAN PEMINJAMAN -->
    <?php if ($jenis == 'peminjaman') : ?>

        <?php
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
            ORDER BY peminjaman.id_peminjaman DESC
        ");
        ?>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="p-5 border-b">

                <h2 class="font-semibold text-lg">
                    Laporan Peminjaman
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-100">

                        <tr>
                            <th class="px-4 py-3">Anggota</th>
                            <th class="px-4 py-3">Buku</th>
                            <th class="px-4 py-3">Tgl Pinjam</th>
                            <th class="px-4 py-3">Tgl Kembali</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                            <tr class="border-b">

                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($row['nama']) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($row['judul']) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= date('d-m-Y', strtotime($row['tanggal_kembali'])) ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?= ucfirst($row['status']) ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>