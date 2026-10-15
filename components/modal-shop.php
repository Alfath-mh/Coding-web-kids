<?php
// ============================================================
// components/modal-shop.php
// Struktur modal Toko Bintang (penukaran Star Coins).
// Variabel yang dipakai dari index.php: $items, $arazkaSvg, $starCoins, e()
// ============================================================
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
?>
<!-- ===================== MODAL TOKO BINTANG ===================== -->
<div id="toko-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-cosmic-text/60"
     role="dialog" aria-modal="true" aria-labelledby="toko-judul">
    <div class="modal-panel relative flex flex-col w-full max-w-2xl max-h-[90vh] overflow-hidden bg-cosmic-card border-4 border-cosmic-accent rounded-3xl shadow-2xl p-5 sm:p-6">

        <!-- Tombol Tutup (X) -->
        <button type="button" id="toko-tutup" aria-label="Tutup toko"
                class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-white/80 text-cosmic-text transition-transform duration-150 hover:scale-110 hover:bg-white focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>

        <!-- Bagian atas (tetap terlihat): judul, saldo, dan Arazka -->
        <div class="shrink-0">
            <div class="flex items-center gap-3 pr-10">
                <div class="w-12 h-12 shrink-0 flex items-center justify-center rounded-full bg-cosmic-accent shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 9l1.5-5h15L21 9"/>
                        <path d="M3 9c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3"/>
                        <path d="M5 12v8h14v-8"/>
                        <path d="M10 20v-4h4v4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-wide text-violet-600">Tukar Koin Bintang</p>
                    <h2 id="toko-judul" class="text-xl sm:text-2xl font-black leading-tight">Toko Bintang</h2>
                </div>
            </div>

            <!-- Saldo koin -->
            <div class="mt-3 inline-flex items-center gap-1.5 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-3 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                             fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm font-extrabold">Koinmu: <span id="toko-saldo"><?= (int) $starCoins ?></span></span>
            </div>

            <!-- Arazka di toko -->
            <div class="mt-3 flex items-start gap-3" role="status" aria-live="polite">
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

        <!-- Grid item toko (bagian ini yang bisa digulir) -->
        <div class="mt-4 flex-1 min-h-0 overflow-y-auto pr-1">
            <div id="toko-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <?php foreach ($items as $it): ?>
                    <article class="item-toko flex flex-col text-center bg-white/80 border-2 border-violet-200 rounded-2xl p-3 transition-colors duration-200"
                             data-item-id="<?= e($it['id']) ?>"
                             data-harga="<?= (int) $it['harga'] ?>"
                             data-nama="<?= e($it['nama']) ?>">
                        <div class="w-16 h-16 mx-auto"><?= $it['ikon'] ?></div>
                        <h3 class="mt-2 text-sm sm:text-base font-black leading-tight"><?= e($it['nama']) ?></h3>
                        <p class="mt-1 text-xs font-bold opacity-75 leading-snug"><?= e($it['deskripsi']) ?></p>

                        <!-- Harga -->
                        <div class="mt-2 mx-auto inline-flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1 pr-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 shrink-0" aria-hidden="true">
                                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                         fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-sm font-extrabold leading-none"><?= (int) $it['harga'] ?> Koin</span>
                        </div>

                        <!-- Status -->
                        <span class="item-status mt-1 text-xs font-extrabold text-violet-600">Tersedia</span>

                        <!-- Tombol Beli -->
                        <div class="mt-auto pt-2">
                            <button type="button"
                                    class="btn-beli w-full bg-violet-500 text-white font-extrabold text-sm py-2 rounded-full shadow transition-all duration-150 hover:scale-105 hover:bg-violet-600 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
                                Beli
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>