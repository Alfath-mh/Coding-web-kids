<?php
// components/modal-quiz.php
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
?>
<aside id="arazka-widget" class="fixed bottom-4 right-4 z-40 flex items-end gap-2" aria-label="Arazka, mentor AI BRIGHT SPACE">
    <div id="arazka-bubble" class="hidden relative max-w-[14rem] sm:max-w-xs bg-white border-2 border-violet-300 rounded-2xl px-3 py-2 shadow-lg" style="display: none;" role="status" aria-live="polite">
        <p class="text-xs font-extrabold text-violet-600">Arazka</p>
        <p id="arazka-teks" class="text-sm font-bold leading-snug"></p>
        <span class="absolute -right-1.5 bottom-5 w-3 h-3 rotate-45 bg-white border-t-2 border-r-2 border-violet-300"></span>
    </div>
    <button type="button" id="arazka-btn" aria-expanded="false" aria-label="Ngobrol dengan Arazka"
            class="arazka-wrap w-16 h-16 sm:w-20 sm:h-20 shrink-0 p-1 rounded-full bg-cosmic-card border-4 border-cosmic-accent shadow-lg transition-transform duration-150 hover:scale-110 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
        <?= $arazkaSvg ?>
    </button>
</aside>

<div id="kuis-modal"
     class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-cosmic-text/60"
     style="display: none;"
     data-modal="kuis"
     role="dialog" aria-modal="true" aria-labelledby="kuis-judul">
    <div id="kuis-panel" class="modal-panel relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-cosmic-card border-4 border-cosmic-accent rounded-3xl shadow-2xl p-5 sm:p-6">

        <button type="button" id="kuis-tutup" aria-label="Tutup kuis"
                class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-white/80 text-cosmic-text transition-transform duration-150 hover:scale-110 hover:bg-white focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>

        <div class="flex items-center gap-3 pr-10">
            <div id="kuis-ikon" class="w-16 h-16 shrink-0"></div>
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-violet-600">Misi Kuis</p>
                <h2 id="kuis-judul" class="text-xl sm:text-2xl font-black leading-tight"></h2>
            </div>
        </div>

        <div class="mt-3 inline-flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-3 py-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                         fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm font-extrabold">Hadiah: <span id="kuis-hadiah"></span></span>
        </div>

        <p id="kuis-pertanyaan" class="mt-4 text-lg sm:text-xl font-extrabold leading-snug"></p>

        <div id="kuis-pilihan" class="mt-4 grid gap-3"></div>

        <div id="kuis-feedback" class="mt-4 flex items-start gap-3" role="status" aria-live="polite">
            <div id="arazka-modal" class="arazka-wrap relative w-16 h-16 shrink-0">
                <?= $arazkaSvg ?>
                <div id="kuis-percik" class="absolute inset-0 pointer-events-none"></div>
            </div>
            <div id="kuis-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                <span class="absolute -left-1.5 top-5 w-3 h-3 rotate-45 bg-inherit border-l-2 border-b-2 border-inherit"></span>
                <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                <p id="kuis-fb-judul" class="text-base font-black leading-snug"></p>
                <p id="kuis-fb-teks" class="mt-1 text-sm font-bold leading-snug"></p>
            </div>
        </div>

        <button type="button" id="kuis-lanjut"
                class="hidden w-full mt-4 bg-violet-500 text-white font-extrabold text-base py-3 rounded-full shadow transition-all duration-200 hover:scale-105 hover:bg-violet-600 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
            Tutup / Lanjut Misi 🚀
        </button>
    </div>
</div>