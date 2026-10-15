<?php
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
$user = current_user();
$namaUser = $user ? $user['nama'] : 'Kapten';
$koinUser = $user ? (int) $user['star_coins'] : 0;
$levelUser = $user ? (int) $user['level'] : 1;
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Tombol Hamburger (fixed kanan atas) -->
<button type="button" id="btn-sidebar-open" aria-label="Buka menu"
        class="fixed top-3 left-3 z-[70] w-11 h-11 flex items-center justify-center bg-white border-2 border-violet-300 rounded-2xl shadow-lg hover:scale-105 transition-transform focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 text-cosmic-text" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <path d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<!-- Backdrop -->
<div id="sidebar-backdrop" class="hidden fixed inset-0 bg-cosmic-text/50 z-[75] transition-opacity" style="display:none;"></div>

<!-- Sidebar -->
<aside id="sidebar" aria-label="Menu navigasi utama"
       class="fixed top-0 left-0 h-full w-72 max-w-[85vw] bg-cosmic-card border-r-4 border-cosmic-accent shadow-2xl z-[80] -translate-x-full transition-transform duration-300 ease-out flex flex-col"
       style="transform: translateX(-100%);">

    <!-- Header sidebar -->
    <div class="flex items-center justify-between p-4 border-b-2 border-violet-200">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-cosmic-accent shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" aria-hidden="true">
                    <path d="M9.5 17.5c-.5 1.6-.3 3.2.5 4.5 1-.6 1.6-1.2 2-2 .4.8 1 1.4 2 2 .8-1.3 1-2.9.5-4.5z" fill="#FB923C"/>
                    <path d="M8 11.5 4.5 15l.5 3 3.5-2z" fill="#A78BFA"/>
                    <path d="M16 11.5 19.5 15l-.5 3-3.5-2z" fill="#A78BFA"/>
                    <path d="M12 1.5c3 2 4.8 5.6 4.8 9.6 0 2.2-.4 4.2-1.1 6.4H8.3c-.7-2.2-1.1-4.2-1.1-6.4 0-4 1.8-7.6 4.8-9.6z" fill="#F8FAFC" stroke="#1E1B4B" stroke-width="1.2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.2" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="1.2"/>
                </svg>
            </span>
            <span class="text-base font-black">BRIGHT <span class="text-violet-600">SPACE</span></span>
        </div>
        <button type="button" id="btn-sidebar-close" aria-label="Tutup menu"
                class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/60">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>

    <!-- Profil singkat -->
    <div class="p-4 bg-gradient-to-br from-violet-100 to-indigo-100 border-b-2 border-violet-200">
        <div class="flex items-center gap-3">
            <span class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-black text-lg shadow">
                <?= strtoupper(substr($namaUser, 0, 1)) ?>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-extrabold uppercase text-violet-600">Kapten</p>
                <p class="text-base font-black truncate"><?= htmlspecialchars($namaUser) ?></p>
                <p class="text-xs font-bold text-amber-600">🏆 Level <?= $levelUser ?></p>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-2 bg-cosmic-accent border-2 border-amber-300 rounded-full px-3 py-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/>
            </svg>
            <span class="font-extrabold text-base" id="sidebar-koin"><?= $koinUser ?></span>
            <span class="text-xs font-bold opacity-70">Koin</span>
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto p-3">
        <p class="text-xs font-extrabold uppercase tracking-wider text-violet-500 px-3 py-2">Menu Utama</p>

        <a href="index.php" class="menu-item <?= $currentPage === 'index.php' ? 'bg-violet-500 text-white' : 'hover:bg-violet-100 text-cosmic-text' ?> flex items-center gap-3 px-3 py-2.5 rounded-2xl font-extrabold text-sm transition-colors">
            <span class="text-lg">🗺️</span>
            <span>Peta Planet</span>
        </a>

        <button type="button" id="sidebar-btn-toko" class="menu-item w-full flex items-center gap-3 px-3 py-2.5 rounded-2xl font-extrabold text-sm hover:bg-violet-100 transition-colors text-left">
            <span class="text-lg">🛍️</span>
            <span>Toko Bintang</span>
        </button>

        <p class="text-xs font-extrabold uppercase tracking-wider text-violet-500 px-3 py-2 mt-3">Petualangan</p>

        <a href="index.php#grid-planet" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-2xl font-extrabold text-sm hover:bg-violet-100 transition-colors">
            <span class="text-lg">🚀</span>
            <span>Lanjut Misi</span>
        </a>

        <a href="index.php" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-2xl font-extrabold text-sm hover:bg-violet-100 transition-colors">
            <span class="text-lg">🏆</span>
            <span>Statistik</span>
        </a>

        <p class="text-xs font-extrabold uppercase tracking-wider text-violet-500 px-3 py-2 mt-3">Lainnya</p>

        <a href="logout.php" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-2xl font-extrabold text-sm text-rose-600 hover:bg-rose-50 transition-colors">
            <span class="text-lg">🚪</span>
            <span>Keluar</span>
        </a>
    </nav>

    <!-- Footer sidebar -->
    <div class="p-3 border-t-2 border-violet-200">
        <a href="logout.php" class="flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-sm py-3 rounded-2xl shadow transition-transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Log Out</span>
        </a>
    </div>
</aside>

<script>
(function () {
    var sidebar = document.getElementById('sidebar');
    var backdrop = document.getElementById('sidebar-backdrop');
    var btnOpen = document.getElementById('btn-sidebar-open');
    var btnClose = document.getElementById('btn-sidebar-close');
    var btnToko = document.getElementById('sidebar-btn-toko');
    if (!sidebar) return;

    function bukaSidebar() {
        sidebar.style.transform = 'translateX(0)';
        backdrop.classList.remove('hidden');
        backdrop.style.display = '';
        document.body.classList.add('overflow-hidden');
    }
    function tutupSidebar() {
        sidebar.style.transform = 'translateX(-100%)';
        backdrop.classList.add('hidden');
        backdrop.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
    }

    btnOpen && btnOpen.addEventListener('click', bukaSidebar);
    btnClose && btnClose.addEventListener('click', tutupSidebar);
    backdrop && backdrop.addEventListener('click', tutupSidebar);

    // Tombol Toko di sidebar — trigger tombol toko di header
    btnToko && btnToko.addEventListener('click', function () {
        tutupSidebar();
        setTimeout(function () {
            var headerToko = document.getElementById('btn-toko-bintang');
            if (headerToko) headerToko.click();
        }, 250);
    });

    // Escape nutup sidebar
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.style.transform === 'translateX(0px)') tutupSidebar();
    });
})();
</script>