<?php
session_start();

include __DIR__ . '/config/koneksi.php';

$success = "";
$error = "";

if (isset($_POST['simpan'])) {

    $nama_lengkap = mysqli_real_escape_string(
        $conn,
        $_POST['nama_lengkap']
    );

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $role = mysqli_real_escape_string(
        $conn,
        $_POST['role']
    );

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    // cek username
    $cek = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE username='$username'"
    );

    if (mysqli_num_rows($cek) > 0) {

        $error = "Username sudah digunakan!";

    } else {

        $insert = mysqli_query(
            $conn,
            "INSERT INTO users
            (
                nama_lengkap,
                username,
                password,
                role
            )
            VALUES
            (
                '$nama_lengkap',
                '$username',
                '$password',
                '$role'
            )"
        );

        if ($insert) {
            $success = "User berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah User</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10 bg-white rounded-2xl shadow-lg p-8">

    <h2 class="text-2xl font-bold text-teal-800 mb-6">
        <i class="fas fa-user-plus mr-2"></i>
        Tambah User
    </h2>

    <?php if ($success) : ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if ($error) : ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">

        <div>
            <label class="block mb-2 text-sm font-medium">
                Nama Lengkap
            </label>
            <input
                type="text"
                name="nama_lengkap"
                required
                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">
                Username
            </label>
            <input
                type="text"
                name="username"
                required
                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">
                Password
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">
                Role
            </label>
            <select
                name="role"
                required
                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-teal-500"
            >
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="anggota">Anggota</option>
            </select>
        </div>

        <button
            type="submit"
            name="simpan"
            class="w-full bg-teal-800 hover:bg-teal-700 text-white py-3 rounded-xl font-semibold"
        >
            <i class="fas fa-save mr-2"></i>
            Simpan User
        </button>

    </form>

</div>

</body>
</html>