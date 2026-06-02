<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}


include __DIR__ .'/../../config/koneksi.php';

// TAMBAH KATEGORI
if (isset($_POST['simpan'])) {

    $nama_kategori = mysqli_real_escape_string(
        $conn,
        trim($_POST['nama_kategori'])
    );

    if (!empty($nama_kategori)) {

        mysqli_query(
            $conn,
            "INSERT INTO kategori (nama_kategori)
             VALUES ('$nama_kategori')"
        );

        header("Location: index.php");
        exit;
    }
}

include __DIR__ .'/../../layouts/header.php';
include __DIR__ .'/../../layouts/sidebar.php';
include __DIR__ .'/../../layouts/topbar.php';
?>

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800">
            Kategori Buku
        </h1>
        <p class="text-slate-500">
            Kelola data kategori buku perpustakaan
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM TAMBAH -->
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <h2 class="text-xl font-semibold mb-5">
                Tambah Kategori
            </h2>

            <form method="POST">

                <div class="mb-4">

                    <label class="block mb-2 text-sm font-medium text-slate-700">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        name="nama_kategori"
                        required
                        placeholder="Masukkan kategori..."
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <button
                    type="submit"
                    name="simpan"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium transition">

                    Simpan Kategori

                </button>

            </form>

        </div>

        <!-- TABEL DATA -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">

            <div class="flex justify-between items-center mb-5">

                <h2 class="text-xl font-semibold">
                    Data Kategori
                </h2>

                <span class="text-sm text-slate-500">
                    Total :
                    <?php
                    $total = mysqli_num_rows(
                        mysqli_query($conn, "SELECT * FROM kategori")
                    );
                    echo $total;
                    ?>
                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b">

                            <th class="text-left py-3">
                                No
                            </th>

                            <th class="text-left py-3">
                                Nama Kategori
                            </th>

                            <th class="text-center py-3">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        $query = mysqli_query(
                            $conn,
                            "SELECT *
                             FROM kategori
                             ORDER BY id_kategori DESC"
                        );

                        if (mysqli_num_rows($query) > 0) :

                            while ($row = mysqli_fetch_assoc($query)) :
                        ?>

                                <tr class="border-b hover:bg-slate-50">

                                    <td class="py-4">
                                        <?= $no++ ?>
                                    </td>

                                    <td class="py-4 font-medium">
                                        <?= htmlspecialchars($row['nama_kategori']) ?>
                                    </td>

                                    <td class="py-4">

                                        <div class="flex justify-center gap-2">

                                            <a
                                                href="edit.php?id=<?= $row['id_kategori'] ?>"
                                                class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm">

                                                Edit

                                            </a>

                                            <a
                                                href="hapus.php?id=<?= $row['id_kategori'] ?>"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm">

                                                Hapus

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php
                            endwhile;

                        else :
                            ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center py-10 text-slate-500">

                                    Belum ada data kategori

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ .'/../../layouts/footer.php'; ?>