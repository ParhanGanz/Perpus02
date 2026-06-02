<?php
// Mengambil path URL saat ini (misal: /perpustakaan/pages/buku/index.php)
$current_page = $_SERVER['REQUEST_URI'];

/**
 * Fungsi pembantu untuk menentukan class menu berdasarkan halaman aktif
 * @param string $page_path Kata kunci path menu
 * @return array Array berisi class link, class icon, dan status keaktifan
 */
function getMenuState($page_path, $current_page) {
    if (strpos($current_page, $page_path) !== false) {
        return [
            'link' => 'bg-teal-900/60 text-white font-semibold shadow-inner border-l-4 border-teal-400 pl-2',
            'icon' => 'text-teal-300'
        ];
    }
    return [
        'link' => 'text-teal-100 hover:bg-teal-700/50 hover:text-white hover:translate-x-1 border-l-4 border-transparent pl-3',
        'icon' => 'text-teal-400 group-hover:text-teal-200'
    ];
}
?>

<aside class="w-64 bg-gradient-to-b from-teal-850 to-teal-900 bg-teal-800 text-white flex flex-col h-full hidden md:flex flex-shrink-0 shadow-xl border-r border-teal-900">
    
    <div class="p-6 flex items-center gap-3 border-b border-teal-700/50 bg-teal-900/20">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-400 to-emerald-400 flex items-center justify-center shadow-md shadow-teal-900/50 animate-pulse">
            <i class="fas fa-book-reader text-teal-950 text-lg"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-black text-base tracking-wider bg-gradient-to-r from-white via-teal-100 to-teal-200 bg-clip-text text-transparent">E-PUSTAKA</span>
            <span class="text-[10px] text-teal-300 font-medium tracking-tight">Management System</span>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar">
        
        <div class="pt-2 mb-1">
            <p class="text-[10px] font-bold text-teal-400/80 uppercase tracking-widest px-3 mb-2">Utama</p>
            <?php $state = getMenuState('dashboard.php', $current_page); ?>
            <a href="/perpustakaan/dashboard.php" class="group flex items-center gap-3 py-2.5 rounded-r-xl transition-all duration-200 <?= $state['link'] ?>">
                <i class="fas fa-tachometer-alt w-5 text-center transition-transform group-hover:scale-110 <?= $state['icon'] ?>"></i> 
                <span class="text-sm">Dashboard</span>
            </a>
        </div>

        <div class="pt-4 mb-1">
            <p class="text-[10px] font-bold text-teal-400/80 uppercase tracking-widest px-3 mb-2">Manajemen</p>
            
            <?php $state = getMenuState('pages/buku', $current_page); ?>
            <a href="/perpustakaan/pages/buku/index.php" class="group flex items-center gap-3 py-2.5 rounded-r-xl transition-all duration-200 <?= $state['link'] ?>">
                <i class="fas fa-book w-5 text-center transition-transform group-hover:scale-110 <?= $state['icon'] ?>"></i> 
                <span class="text-sm">Katalog Buku</span>
            </a>

            <?php $state = getMenuState('pages/anggota', $current_page); ?>
            <a href="/perpustakaan/pages/anggota/index.php" class="group flex items-center gap-3 py-2.5 rounded-r-xl transition-all duration-200 <?= $state['link'] ?>">
                <i class="fas fa-users w-5 text-center transition-transform group-hover:scale-110 <?= $state['icon'] ?>"></i> 
                <span class="text-sm">Data Anggota</span>
            </a>

            <?php $state = getMenuState('pages/kategori', $current_page); ?>
            <a href="/perpustakaan/pages/kategori/index.php" class="group flex items-center gap-3 py-2.5 rounded-r-xl transition-all duration-200 <?= $state['link'] ?>">
                <i class="fas fa-tags w-5 text-center transition-transform group-hover:scale-110 <?= $state['icon'] ?>"></i> 
                <span class="text-sm">Kategori Buku</span>
            </a>
        </div>

        <div class="pt-4 mb-1">
            <p class="text-[10px] font-bold text-teal-400/80 uppercase tracking-widest px-3 mb-2">Sirkulasi</p>
            
            <?php $state = getMenuState('pages/peminjaman', $current_page); ?>
            <a href="/perpustakaan/pages/peminjaman/index.php" class="group flex items-center gap-3 py-2.5 rounded-r-xl transition-all duration-200 <?= $state['link'] ?>">
                <i class="fas fa-exchange-alt w-5 text-center transition-transform group-hover:scale-110 <?= $state['icon'] ?>"></i> 
                <span class="text-sm">Peminjaman</span>
            </a>
        </div>

        <div class="pt-4 mb-1">
            <p class="text-[10px] font-bold text-teal-400/80 uppercase tracking-widest px-3 mb-2">Laporan</p>
            
            <?php $state = getMenuState('pages/laporan', $current_page); ?>
            <a href="/perpustakaan/pages/laporan/index.php" class="group flex items-center gap-3 py-2.5 rounded-r-xl transition-all duration-200 <?= $state['link'] ?>">
                <i class="fas fa-chart-bar w-5 text-center transition-transform group-hover:scale-110 <?= $state['icon'] ?>"></i> 
                <span class="text-sm">Laporan Tahunan</span>
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-teal-700/50 bg-teal-950/40 backdrop-blur-md flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-500 flex items-center justify-center font-bold text-sm uppercase shadow-md text-teal-950 border border-teal-400/30">
            <?= isset($_SESSION['username']) ? substr(htmlspecialchars($_SESSION['username']), 0, 2) : 'AD' ?>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold truncate text-white capitalize tracking-wide">
                <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest Account' ?>
            </p>
            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-teal-300 bg-teal-900/60 px-2 py-0.5 rounded-md border border-teal-700/40 mt-0.5">
                <i class="fas fa-shield-alt text-[8px]"></i> Pustakawan
            </span>
        </div>
    </div>
</aside>

<div class="flex-1 flex flex-col overflow-hidden">