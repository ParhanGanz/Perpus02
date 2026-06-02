<header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6 flex-shrink-0">

    <div class="flex items-center gap-4">
        <button class="text-gray-500 hover:text-gray-700 md:hidden block focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <div class="hidden sm:flex items-center gap-2.5 text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100 text-xs font-medium">
            <i class="far fa-clock text-teal-600 text-sm"></i>
            <span id="live-clock">Memuat Waktu...</span>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="h-6 w-px bg-gray-200"></div>
        <a href="/perpustakaan/auth/logout.php" class="flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span class="hidden sm:inline">Keluar</span>
        </a>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        const dateString = now.toLocaleDateString('id-ID', options);
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        document.getElementById('live-clock').innerHTML = `<span class="text-gray-700 font-semibold">${dateString}</span> • ${timeString} WIB`;
    }
    // Jalankan fungsi setiap detik
    setInterval(updateClock, 1000);
    updateClock(); // Jalankan pertama kali saat load
</script>

<main class="flex-1 overflow-y-auto p-6 bg-gray-50">