<?php
// Mengambil path URL saat ini (misal: /perpustakaan/pages/buku/index.php)
$current_page = $_SERVER['REQUEST_URI'];

/**
 * Fungsi pembantu untuk menentukan class menu berdasarkan halaman aktif
 * @param string $page_path Kata kunci path menu
 * @return string Class Tailwind CSS yang simpel
 */
function setActiveMenu($page_path, $current_page) {
    if (strpos($current_page, $page_path) !== false) {
        // Halaman Aktif: Hijau gelap solid, teks putih tebal
        return 'bg-teal-900 text-white font-semibold';
    }
    // Halaman Biasa: Teks agak pudar, kalau di-hover berubah warna tanpa animasi geser
    return 'text-teal-100 hover:bg-teal-700 hover:text-white';
}
?>

<aside class="w-64 bg-teal-800 text-white flex flex-col h-full hidden md:flex flex-shrink-0 border-r border-teal-900">
    
    <div class="p-5 flex items-center gap-3 border-b border-teal-700 bg-teal-900">
        <i class="fas fa-book-reader text-2xl text-teal-300"></i>
        <div class="flex flex-col">
            <span class="font-bold text-lg tracking-wider">E-PERPUSTAKAAN</span>
            <span class="text-[10px] text-teal-300">Sistem Informasi Pustaka</span>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

        <div class="mb-4">   
            <a href="/perpustakaan/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= setActiveMenu('dashboard', $current_page) ?>">
                <i class="fas fa-book w-5 text-center"></i> 
                <span class="text-sm">Dashboard</span>
            </a>
            <a href="/perpustakaan/pages/buku/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= setActiveMenu('pages/buku', $current_page) ?>">
                <i class="fas fa-book w-5 text-center"></i> 
                <span class="text-sm">Katalog Buku</span>
            </a>

            <a href="/perpustakaan/pages/anggota/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= setActiveMenu('pages/anggota', $current_page) ?>">
                <i class="fas fa-users w-5 text-center"></i> 
                <span class="text-sm">Data Anggota</span>
            </a>

            <a href="/perpustakaan/pages/kategori/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= setActiveMenu('pages/kategori', $current_page) ?>">
                <i class="fas fa-tags w-5 text-center"></i> 
                <span class="text-sm">Kategori Buku</span>
            </a>

            <a href="/perpustakaan/pages/peminjaman/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= setActiveMenu('pages/peminjaman', $current_page) ?>">
                <i class="fas fa-exchange-alt w-5 text-center"></i> 
                <span class="text-sm">Peminjaman</span>
            </a>

            <a href="/perpustakaan/pages/laporan/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= setActiveMenu('pages/laporan', $current_page) ?>">
                <i class="fas fa-chart-bar w-5 text-center"></i> 
                <span class="text-sm">Laporan</span>
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-teal-700 bg-teal-900 flex items-center gap-3">
        <div class="w-8 h-8 rounded bg-teal-700 flex items-center justify-center font-bold text-xs uppercase text-white">
            <?= isset($_SESSION['username']) ? substr(htmlspecialchars($_SESSION['username']), 0, 2) : 'AD' ?>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate text-white capitalize">
                <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?>
            </p>
            <p class="text-[11px] text-teal-300">Pustakawan</p>
        </div>
    </div>
</aside>

<div class="flex-1 flex flex-col overflow-hidden">