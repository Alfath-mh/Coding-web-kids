<?php
// components/header.php
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
?>
<header class="sticky top-0 z-50 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
    <nav class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3" aria-label="Navigasi utama">
        <a href="index.php" class="logo-link flex items-center gap-2 rounded-xl focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent" aria-label="<?= e($appName) ?> - Beranda">
            <span class="rocket-icon inline-flex items-center justify-center w-11 h-11 rounded-full bg-cosmic-accent shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7" aria-hidden="true">
                    <path d="M9.5 17.5c-.5 1.6-.3 3.2.5 4.5 1-.6 1.6-1.2 2-2 .4.8 1 1.4 2 2 .8-1.3 1-2.9.5-4.5z" fill="#FB923C"/>
                    <path d="M10.8 17.5c-.2 1-.1 1.8.4 2.6.5-.5.7-1 .8-1.6.1.6.3 1.1.8 1.6.5-.8.6-1.6.4-2.6z" fill="#FDE047"/>
                    <path d="M8 11.5 4.5 15l.5 3 3.5-2z" fill="#A78BFA"/>
                    <path d="M16 11.5 19.5 15l-.5 3-3.5-2z" fill="#A78BFA"/>
                    <path d="M12 1.5c3 2 4.8 5.6 4.8 9.6 0 2.2-.4 4.2-1.1 6.4H8.3c-.7-2.2-1.1-4.2-1.1-6.4 0-4 1.8-7.6 4.8-9.6z" fill="#F8FAFC" stroke="#1E1B4B" stroke-width="1.2" stroke-linejoin="round"/>
                    <path d="M12 1.5c1.2.8 2.2 1.9 3 3.2H9c.8-1.3 1.8-2.4 3-3.2z" fill="#F472B6"/>
                    <circle cx="12" cy="10" r="2.2" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="1.2"/>
                </svg>
            </span>
            <span class="text-xl sm:text-2xl font-black tracking-wide leading-none">
                BRIGHT <span class="text-violet-600">SPACE</span>
            </span>
        </a>

        <div class="flex items-center gap-2 sm:gap-3">
            <div id="star-coin-badge" class="flex items-center gap-1.5 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-2 pr-3 py-1 shadow-sm" role="status" aria-live="polite" aria-label="Jumlah Star Coins">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 shrink-0" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                             fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
                <span id="star-coin-count" class="font-extrabold text-base sm:text-lg leading-none"><?= (int) $starCoins ?></span>
                <span class="hidden sm:inline font-bold text-sm leading-none">Star Coins</span>
            </div>

            <button type="button" id="btn-toko-bintang" aria-haspopup="dialog"
                    class="flex items-center gap-1.5 bg-violet-500 text-white font-extrabold text-sm sm:text-base px-3 sm:px-4 py-2 rounded-full shadow-md transition-transform duration-150 hover:scale-105 hover:bg-violet-600 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 9l1.5-5h15L21 9"/>
                    <path d="M3 9c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3"/>
                    <path d="M5 12v8h14v-8"/>
                    <path d="M10 20v-4h4v4"/>
                </svg>
                <span>Toko Bintang</span>
            </button>
        </div>
    </nav>
</header>