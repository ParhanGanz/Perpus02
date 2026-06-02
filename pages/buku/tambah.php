<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

// SIMPAN BUKU
if (isset($_POST['simpan'])) {

    $isbn = mysqli_real_escape_string(
        $conn,
        trim($_POST['isbn'])
    );

    $judul = mysqli_real_escape_string(
        $conn,
        trim($_POST['judul'])
    );

    $penulis = mysqli_real_escape_string(
        $conn,
        trim($_POST['penulis'])
    );

    $penerbit = mysqli_real_escape_string(
        $conn,
        trim($_POST['penerbit'])
    );

    $tahun_terbit = (int) $_POST['tahun_terbit'];

    $stok = (int) $_POST['stok'];

    $id_kategori = (int) $_POST['id_kategori'];

    $cover = '';

    if (!empty($_FILES['cover']['name'])) {

        $ext = strtolower(
            pathinfo(
                $_FILES['cover']['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {

            $cover = time() . '_' . uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES['cover']['tmp_name'],
                __DIR__ . '/../../assets/img/' . $cover
            );
        }
    }

    mysqli_query($conn, "
        INSERT INTO buku (
            isbn,
            judul,
            penulis,
            penerbit,
            tahun_terbit,
            stok,
            cover,
            id_kategori
        )
        VALUES (
            '$isbn',
            '$judul',
            '$penulis',
            '$penerbit',
            '$tahun_terbit',
            '$stok',
            '$cover',
            '$id_kategori'
        )
    ");

    header("Location: index.php");
    exit;
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
                Tambah Buku
            </h1>

            <p class="text-slate-500 mt-1">
                Tambahkan koleksi buku baru
            </p>

        </div>

        <a
            href="index.php"
            class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-xl">

            Kembali

        </a>

    </div>

    <!-- FORM -->
    <div class="bg-white rounded-2xl shadow-lg p-6">

        <form
            method="POST"
            enctype="multipart/form-data">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- ISBN -->
                <div>

                    <label class="block mb-2 font-medium">
                        ISBN
                    </label>

                    <input
                        type="text"
                        name="isbn"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- KATEGORI -->
                <div>

                    <label class="block mb-2 font-medium">
                        Kategori
                    </label>

                    <select
                        name="id_kategori"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                        <option value="">
                            Pilih Kategori
                        </option>

                        <?php

                        $kategori = mysqli_query(
                            $conn,
                            "SELECT *
                             FROM kategori
                             ORDER BY nama_kategori ASC"
                        );

                        while ($k = mysqli_fetch_assoc($kategori)) :
                        ?>

                            <option value="<?= $k['id_kategori'] ?>">
                                <?= $k['nama_kategori'] ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- JUDUL -->
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Judul Buku
                    </label>

                    <input
                        type="text"
                        name="judul"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- PENULIS -->
                <div>

                    <label class="block mb-2 font-medium">
                        Penulis
                    </label>

                    <input
                        type="text"
                        name="penulis"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- PENERBIT -->
                <div>

                    <label class="block mb-2 font-medium">
                        Penerbit
                    </label>

                    <input
                        type="text"
                        name="penerbit"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- TAHUN -->
                <div>

                    <label class="block mb-2 font-medium">
                        Tahun Terbit
                    </label>

                    <input
                        type="number"
                        name="tahun_terbit"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- STOK -->
                <div>

                    <label class="block mb-2 font-medium">
                        Stok
                    </label>

                    <input
                        type="number"
                        name="stok"
                        min="0"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- COVER -->
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Cover Buku
                    </label>

                    <input
                        type="file"
                        name="cover"
                        accept=".jpg,.jpeg,.png,.webp"
                        onchange="previewCover(event)"
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <!-- PREVIEW -->
                <div class="md:col-span-2">

                    <img
                        id="preview"
                        src=""
                        class="hidden w-40 rounded-xl border shadow">

                </div>

            </div>

            <div class="mt-6">

                <button
                    type="submit"
                    name="simpan"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                    Simpan Buku

                </button>

            </div>

        </form>

    </div>

</div>

<script>
function previewCover(event) {

    const preview = document.getElementById('preview');

    preview.src = URL.createObjectURL(
        event.target.files[0]
    );

    preview.classList.remove('hidden');
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>