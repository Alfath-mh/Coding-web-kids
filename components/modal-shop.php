<?php
// ============================================================
// components/modal-shop.php
// Modal Toko Bintang: daftar item, saldo koin, Arazka pendamping.
// ============================================================
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
?>
<div id="toko-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-cosmic-text/60"
     role="dialog" aria-modal="true" aria-labelledby="toko-judul">
    <div class="modal-panel relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-cosmic-card border-4 border-cosmic-accent rounded-3xl shadow-2xl p-5 sm:p-6">

        <button type="button" id="toko-tutup" aria-label="Tutup toko"
                class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-white/80 text-cosmic-text transition-transform duration-150 hover:scale-110 hover:bg-white focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>

        <div class="flex items-center gap-3 pr-10">
            <div class="w-14 h-14 shrink-0 flex items-center justify-center bg-cosmic-accent rounded-full border-2 border-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 h-8" fill="none" stroke="#1E1B4B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 9l1.5-5h15L21 9"/>
                    <path d="M3 9c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3"/>
                    <path d="M5 12v8h14v-8"/>
                    <path d="M10 20v-4h4v4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-violet-600">Toko</p>
                <h2 id="toko-judul" class="text-xl sm:text-2xl font-black leading-tight">Toko Bintang</h2>
            </div>
        </div>

        <div class="mt-3 inline-flex items-center gap-1.5 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-2 pr-3 py-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                         fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm font-extrabold">Saldo: <span id="toko-saldo">0</span> Koin</span>
        </div>

        <div id="toko-grid" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3"></div>

        <div id="toko-feedback" class="mt-5 flex items-start gap-3" role="status" aria-live="polite">
            <div id="arazka-toko" class="arazka-wrap relative w-16 h-16 shrink-0">
                <?= $arazkaSvg ?>
                <div id="toko-percik" class="absolute inset-0 pointer-events-none"></div>
            </div>
            <div id="toko-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                <span class="absolute -left-1.5 top-5 w-3 h-3 rotate-45 bg-inherit border-l-2 border-b-2 border-inherit"></span>
                <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                <p id="toko-fb-judul" class="text-base font-black leading-snug"></p>
                <p id="toko-fb-teks" class="mt-1 text-sm font-bold leading-snug"></p>
            </div>
        </div>
    </div>
</div>

<!-- Data item toko untuk JS -->
<script>
    window.BRIGHT_SPACE_ITEMS = <?= json_encode($items, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>