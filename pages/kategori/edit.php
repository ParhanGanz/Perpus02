<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ .'/../../config/koneksi.php';

// VALIDASI ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_kategori = (int) $_GET['id'];

// AMBIL DATA KATEGORI
$query = mysqli_query(
    $conn,
    "SELECT *
     FROM kategori
     WHERE id_kategori = $id_kategori"
);

if (mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// UPDATE DATA
if (isset($_POST['update'])) {

    $nama_kategori = mysqli_real_escape_string(
        $conn,
        trim($_POST['nama_kategori'])
    );

    mysqli_query(
        $conn,
        "UPDATE kategori
         SET nama_kategori = '$nama_kategori'
         WHERE id_kategori = $id_kategori"
    );

    header("Location: index.php");
    exit;
}

include __DIR__ .'/../../layouts/header.php';
include __DIR__ .'/../../layouts/sidebar.php';
include __DIR__ .'/../../layouts/topbar.php';
?>

<div class="p-6">

    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-2xl shadow-lg p-6">

            <div class="flex items-center justify-between mb-6">

                <h1 class="text-2xl font-bold text-slate-800">
                    Edit Kategori
                </h1>

                <a
                    href="index.php"
                    class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg">

                    Kembali

                </a>

            </div>

            <form method="POST">

                <div class="mb-5">

                    <label class="block mb-2 font-medium text-slate-700">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        name="nama_kategori"
                        value="<?= htmlspecialchars($data['nama_kategori']) ?>"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <button
                    type="submit"
                    name="update"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-xl font-medium transition">

                    Update Kategori

                </button>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ .'/../../layouts/footer.php'; ?>