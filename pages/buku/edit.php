<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../auth/login.php");
    exit;
}

include __DIR__ . '/../../config/koneksi.php';

// VALIDASI ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_buku = (int) $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM buku
     WHERE id_buku = $id_buku"
);

if (mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// UPDATE
if (isset($_POST['update'])) {

    $isbn = mysqli_real_escape_string($conn, trim($_POST['isbn']));
    $judul = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $penulis = mysqli_real_escape_string($conn, trim($_POST['penulis']));
    $penerbit = mysqli_real_escape_string($conn, trim($_POST['penerbit']));
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $stok = (int) $_POST['stok'];
    $id_kategori = (int) $_POST['id_kategori'];

    $cover = $data['cover'];

    // CEK COVER BARU
    if (!empty($_FILES['cover']['name'])) {

        $ext = strtolower(
            pathinfo(
                $_FILES['cover']['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {

            // HAPUS COVER LAMA
            if (
                !empty($data['cover']) &&
                file_exists(
                    __DIR__ . '/../../assets/img/' . $data['cover']
                )
            ) {
                unlink(
                    __DIR__ . '/../../assets/img/' . $data['cover']
                );
            }

            $cover = time() . '_' . uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES['cover']['tmp_name'],
                __DIR__ . '/../../assets/img/' . $cover
            );
        }
    }

    mysqli_query($conn, "
        UPDATE buku SET
            isbn = '$isbn',
            judul = '$judul',
            penulis = '$penulis',
            penerbit = '$penerbit',
            tahun_terbit = '$tahun_terbit',
            stok = '$stok',
            cover = '$cover',
            id_kategori = '$id_kategori'
        WHERE id_buku = $id_buku
    ");

    header("Location: index.php");
    exit;
}

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="p-6">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Edit Buku
            </h1>

            <p class="text-slate-500">
                Perbarui data buku
            </p>

        </div>

        <a
            href="index.php"
            class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-xl">

            Kembali

        </a>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <form method="POST" enctype="multipart/form-data">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block mb-2 font-medium">ISBN</label>
                    <input
                        type="text"
                        name="isbn"
                        value="<?= htmlspecialchars($data['isbn']) ?>"
                        class="w-full border rounded-xl px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Kategori</label>

                    <select
                        name="id_kategori"
                        class="w-full border rounded-xl px-4 py-3"
                        required>

                        <?php
                        $kategori = mysqli_query(
                            $conn,
                            "SELECT * FROM kategori
                             ORDER BY nama_kategori ASC"
                        );

                        while ($k = mysqli_fetch_assoc($kategori)) :
                        ?>

                            <option
                                value="<?= $k['id_kategori'] ?>"
                                <?= $k['id_kategori'] == $data['id_kategori'] ? 'selected' : '' ?>>

                                <?= $k['nama_kategori'] ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">Judul Buku</label>
                    <input
                        type="text"
                        name="judul"
                        value="<?= htmlspecialchars($data['judul']) ?>"
                        class="w-full border rounded-xl px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Penulis</label>
                    <input
                        type="text"
                        name="penulis"
                        value="<?= htmlspecialchars($data['penulis']) ?>"
                        class="w-full border rounded-xl px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Penerbit</label>
                    <input
                        type="text"
                        name="penerbit"
                        value="<?= htmlspecialchars($data['penerbit']) ?>"
                        class="w-full border rounded-xl px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Tahun Terbit</label>
                    <input
                        type="number"
                        name="tahun_terbit"
                        value="<?= $data['tahun_terbit'] ?>"
                        class="w-full border rounded-xl px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Stok</label>
                    <input
                        type="number"
                        name="stok"
                        value="<?= $data['stok'] ?>"
                        class="w-full border rounded-xl px-4 py-3"
                        required>
                </div>

                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Cover Saat Ini
                    </label>

                    <?php if (!empty($data['cover'])) : ?>

                        <img
                            src="../../assets/img/<?= $data['cover'] ?>"
                            class="w-32 rounded-xl shadow mb-4">

                    <?php endif; ?>

                    <input
                        type="file"
                        name="cover"
                        accept=".jpg,.jpeg,.png,.webp"
                        onchange="previewCover(event)"
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <div class="md:col-span-2">

                    <img
                        id="preview"
                        class="hidden w-32 rounded-xl shadow">

                </div>

            </div>

            <div class="mt-6">

                <button
                    type="submit"
                    name="update"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl">

                    Update Buku

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