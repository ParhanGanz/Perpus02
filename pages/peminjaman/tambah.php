<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

// SIMPAN PEMINJAMAN
if (isset($_POST['simpan'])) {

    $id_anggota = (int) $_POST['id_anggota'];
    $id_buku = (int) $_POST['id_buku'];

    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    // CEK STOK TERBARU
    $cekBuku = mysqli_query(
        $conn,
        "SELECT stok
         FROM buku
         WHERE id_buku = $id_buku"
    );

    $buku = mysqli_fetch_assoc($cekBuku);

    if ($buku && $buku['stok'] > 0) {

        // SIMPAN PEMINJAMAN
        mysqli_query(
            $conn,
            "INSERT INTO peminjaman (
                id_anggota,
                id_buku,
                tanggal_pinjam,
                tanggal_kembali,
                status
            ) VALUES (
                '$id_anggota',
                '$id_buku',
                '$tanggal_pinjam',
                '$tanggal_kembali',
                'dipinjam'
            )"
        );

        // KURANGI STOK
        mysqli_query(
            $conn,
            "UPDATE buku
             SET stok = stok - 1
             WHERE id_buku = $id_buku"
        );

        header("Location: index.php");
        exit;
    }

    $error = "Stok buku habis.";
}

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="p-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Tambah Peminjaman
            </h1>

            <p class="text-slate-500 mt-1">
                Buat transaksi peminjaman baru
            </p>

        </div>

        <a
            href="index.php"
            class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-xl">

            Kembali

        </a>

    </div>

    <?php if (isset($error)) : ?>

        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
            <?= $error ?>
        </div>

    <?php endif; ?>

    <!-- FORM -->
    <div class="bg-white rounded-2xl shadow-lg p-6">

        <form method="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- ANGGOTA -->
                <div>

                    <label class="block mb-2 font-medium">
                        Anggota
                    </label>

                    <select
                        name="id_anggota"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">

                        <option value="">
                            Pilih Anggota
                        </option>

                        <?php

                        $anggota = mysqli_query(
                            $conn,
                            "SELECT *
                             FROM anggota
                             ORDER BY nama ASC"
                        );

                        while ($a = mysqli_fetch_assoc($anggota)) :
                        ?>

                            <option value="<?= $a['id_anggota'] ?>">
                                <?= htmlspecialchars($a['nama']) ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- BUKU -->
                <div>

                    <label class="block mb-2 font-medium">
                        Buku
                    </label>

                    <select
                        name="id_buku"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">

                        <option value="">
                            Pilih Buku
                        </option>

                        <?php

                        $buku = mysqli_query(
                            $conn,
                            "SELECT *
                             FROM buku
                             WHERE stok > 0
                             ORDER BY judul ASC"
                        );

                        while ($b = mysqli_fetch_assoc($buku)) :
                        ?>

                            <option value="<?= $b['id_buku'] ?>">
                                <?= htmlspecialchars($b['judul']) ?>
                                (Stok: <?= $b['stok'] ?>)
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- TANGGAL PINJAM -->
                <div>

                    <label class="block mb-2 font-medium">
                        Tanggal Pinjam
                    </label>

                    <input
                        type="date"
                        name="tanggal_pinjam"
                        value="<?= date('Y-m-d') ?>"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">

                </div>

                <!-- TANGGAL KEMBALI -->
                <div>

                    <label class="block mb-2 font-medium">
                        Tanggal Kembali
                    </label>

                    <input
                        type="date"
                        name="tanggal_kembali"
                        value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">

                </div>

            </div>

            <div class="mt-6">

                <button
                    type="submit"
                    name="simpan"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                    Simpan Peminjaman

                </button>

            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>