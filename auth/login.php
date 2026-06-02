<?php
session_start();

include __DIR__ . '/../config/koneksi.php';

if (isset($_SESSION['username'])) {
    header("Location: ../dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE username='$username'"
    );

    if (mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard.php");
            exit;
        }

        $error = "Password salah!";
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Pustaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased h-screen flex items-center justify-center">

    <div class="w-full h-full md:h-auto md:max-w-4xl bg-white md:rounded-2xl md:shadow-xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="hidden md:flex md:w-1/2 bg-teal-800 p-12 flex-col justify-between text-white relative bg-cover bg-center" style="background-image: linear-gradient(rgba(17, 94, 89, 0.9), rgba(13, 148, 136, 0.85)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=1000');">
            
            <div class="flex items-center gap-3">
                <i class="fas fa-book-reader text-3xl text-teal-300"></i>
                <span class="font-bold text-xl tracking-wider">E-PUSTAKA</span>
            </div>

            <div class="space-y-4">
                <i class="fas fa-quote-left text-4xl text-teal-300 opacity-50"></i>
                <p class="text-xl italic font-light leading-relaxed">
                    "Membaca adalah alat paling mendasar untuk meraih hidup yang baik."
                </p>
                <p class="text-sm font-semibold text-teal-200">— Joseph Addison</p>
            </div>

            <div class="text-xs text-teal-200">
                <p><i class="fas fa-info-circle mr-2"></i> Butuh bantuan login? Hubungi petugas pustakawan di ruang utama.</p>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
            
            <div class="mb-8">
                <div class="md:hidden flex items-center gap-2 text-teal-800 mb-6 justify-center">
                    <i class="fas fa-book-reader text-3xl"></i>
                    <span class="font-bold text-xl tracking-wider">E-PUSTAKA</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali!</h2>
                <p class="text-sm text-gray-500 mt-1">Silakan masuk menggunakan akun pustakawan atau anggota Anda.</p>
            </div>

            <?php if (isset($error) && $error) : ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center gap-3 text-red-700">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <p class="text-sm font-medium"><?= htmlspecialchars($error) ?></p>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input 
                            type="text" 
                            name="username" 
                            placeholder="Masukkan username anda" 
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all bg-gray-50 focus:bg-white"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="••••••••" 
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all bg-gray-50 focus:bg-white"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" class="rounded text-teal-600 focus:ring-teal-500 mr-2 border-gray-300">
                        Ingat Saya
                    </label>
                    <a href="#" class="text-teal-700 hover:underline font-medium">Lupa Password?</a>
                </div>

                <button 
                    type="submit" 
                    name="login"
                    class="w-full py-3 bg-teal-800 hover:bg-teal-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-teal-800/20 hover:shadow-lg transition-all transform active:scale-[0.98] mt-2"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk Ke Sistem
                </button>

            </form>
            </div>
    </div>

</body>
</html>