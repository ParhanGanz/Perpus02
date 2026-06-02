<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

// SIMPAN DATA
if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string(
        $conn,
        trim($_POST['nama'])
    );

    $jenis_kelamin = mysqli_real_escape_string(
        $conn,
        $_POST['jenis_kelamin']
    );

    $alamat = mysqli_real_escape_string(
        $conn,
        trim($_POST['alamat'])
    );

    $telepon = mysqli_real_escape_string(
        $conn,
        trim($_POST['telepon'])
    );

    $tanggal_daftar = $_POST['tanggal_daftar'];

    mysqli_query($conn, "
        INSERT INTO anggota (
            nama,
            jenis_kelamin,
            alamat,
            telepon,
            tanggal_daftar
        )
        VALUES (
            '$nama',
            '$jenis_kelamin',
            '$alamat',
            '$telepon',
            '$tanggal_daftar'
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
                Tambah Anggota
            </h1>

            <p class="text-slate-500 mt-1">
                Tambahkan anggota baru perpustakaan
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

        <form method="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- NAMA -->
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <!-- JENIS KELAMIN -->
                <div>

                    <label class="block mb-2 font-medium">
                        Jenis Kelamin
                    </label>

                    <select
                        name="jenis_kelamin"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">
                            Pilih Jenis Kelamin
                        </option>

                        <option value="L">
                            Laki-laki
                        </option>

                        <option value="P">
                            Perempuan
                        </option>

                    </select>

                </div>

                <!-- TELEPON -->
                <div>

                    <label class="block mb-2 font-medium">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        name="telepon"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <!-- TANGGAL DAFTAR -->
                <div>

                    <label class="block mb-2 font-medium">
                        Tanggal Daftar
                    </label>

                    <input
                        type="date"
                        name="tanggal_daftar"
                        value="<?= date('Y-m-d') ?>"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <!-- ALAMAT -->
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>

                </div>

            </div>

            <div class="mt-6">

                <button
                    type="submit"
                    name="simpan"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition">

                    Simpan Anggota

                </button>

            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>