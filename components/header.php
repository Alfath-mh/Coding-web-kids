<?php
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
$user = current_user();
$namaUser = $user ? $user['nama'] : 'Kapten';
$koinUser = $user ? (int) $user['star_coins'] : 0;
$levelUser = $user ? (int) $user['level'] : 1;
?>
<header class="sticky top-0 z-50 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
    <nav class="max-w-6xl mx-auto px-4 py-2.5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="index.php" class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-cosmic-accent shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" aria-hidden="true">
                        <path d="M9.5 17.5c-.5 1.6-.3 3.2.5 4.5 1-.6 1.6-1.2 2-2 .4.8 1 1.4 2 2 .8-1.3 1-2.9.5-4.5z" fill="#FB923C"/>
                        <path d="M8 11.5 4.5 15l.5 3 3.5-2z" fill="#A78BFA"/>
                        <path d="M16 11.5 19.5 15l-.5 3-3.5-2z" fill="#A78BFA"/>
                        <path d="M12 1.5c3 2 4.8 5.6 4.8 9.6 0 2.2-.4 4.2-1.1 6.4H8.3c-.7-2.2-1.1-4.2-1.1-6.4 0-4 1.8-7.6 4.8-9.6z" fill="#F8FAFC" stroke="#1E1B4B" stroke-width="1.2" stroke-linejoin="round"/>
                        <circle cx="12" cy="10" r="2.2" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="1.2"/>
                    </svg>
                </span>
                <span class="hidden sm:inline text-lg font-black">BRIGHT <span class="text-violet-600">SPACE</span></span>
            </a>
            <a href="index.php" class="hidden md:inline-flex items-center gap-1 text-sm font-extrabold px-3 py-1.5 rounded-full hover:bg-white/60">🗺️ Peta Planet</a>
        </div>

        <div class="flex items-center gap-2">
            <div id="star-coin-badge" class="flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-2.5 py-1 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
                <span id="star-coin-count" class="font-extrabold text-base leading-none"><?= $koinUser ?></span>
            </div>

            <button type="button" id="btn-toko-bintang" aria-haspopup="dialog"
                    class="flex items-center gap-1 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm px-3 py-2 rounded-full shadow-md hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 9l1.5-5h15L21 9"/><path d="M5 12v8h14v-8"/><path d="M10 20v-4h4v4"/>
                </svg>
                <span class="hidden sm:inline">Toko</span>
            </button>

            <div class="relative">
                <button type="button" id="btn-profil" aria-haspopup="true" aria-expanded="false"
                        class="flex items-center gap-2 bg-white hover:bg-violet-50 border-2 border-violet-200 rounded-full pl-1 pr-3 py-1 shadow-sm">
                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-black text-xs">
                        <?= strtoupper(substr($namaUser, 0, 1)) ?>
                    </span>
                    <span class="hidden sm:inline text-sm font-extrabold max-w-[80px] truncate"><?= htmlspecialchars($namaUser) ?></span>
                </button>
                <div id="profil-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border-2 border-violet-200 rounded-2xl shadow-xl p-3 z-50">
                    <div class="pb-2 border-b-2 border-violet-100">
                        <p class="text-xs font-extrabold uppercase text-violet-600">Kapten</p>
                        <p class="text-lg font-black truncate"><?= htmlspecialchars($namaUser) ?></p>
                        <p class="text-xs font-bold text-amber-600 mt-0.5">🏆 Level <?= $levelUser ?></p>
                    </div>
                    <a href="index.php" class="block mt-2 px-3 py-2 rounded-xl hover:bg-violet-50 text-sm font-extrabold">🗺️ Peta Planet</a>
                    <a href="logout.php" class="block mt-1 px-3 py-2 rounded-xl hover:bg-rose-50 text-sm font-extrabold text-rose-600">🚪 Keluar</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<script>
document.addEventListener('click', function (e) {
    var btn = document.getElementById('btn-profil');
    var dd  = document.getElementById('profil-dropdown');
    if (!btn || !dd) return;
    if (btn.contains(e.target)) {
        dd.classList.toggle('hidden');
    } else if (!dd.contains(e.target)) {
        dd.classList.add('hidden');
    }
});
</script>