<?php
// ============================================================
// components/planet-grid.php
// Bagian hero + grid kartu planet belajar.
// Variabel yang dipakai dari index.php: $planets, e()
// ============================================================
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');
?>
<!-- Hero: Judul Bagian Misi -->
<section aria-labelledby="judul-misi" class="mb-8 text-center">
    <h1 id="judul-misi" class="text-3xl sm:text-4xl font-black">Pilih Planet Petualanganmu!</h1>
    <p class="mt-2 text-base sm:text-lg font-bold opacity-80">
        Selesaikan misi di setiap planet untuk mengumpulkan Koin Bintang ⭐
    </p>
</section>

<!-- Grid Kartu Planet: 1 kolom (HP), 2 kolom (Tablet), 4 kolom (Desktop) -->
<section id="grid-planet" aria-label="Daftar planet belajar" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($planets as $p): ?>
        <?php $terbuka = ($p['status'] === 'Terbuka'); ?>
        <article class="planet-card <?= $terbuka ? 'is-open hover:-translate-y-2 hover:shadow-xl' : 'opacity-90' ?> relative flex flex-col cursor-pointer bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300"
                 aria-labelledby="planet-<?= e($p['id']) ?>"
                 data-planet-id="<?= e($p['id']) ?>"
                 data-planet-nama="<?= e($p['nama']) ?>"
                 data-poin="<?= (int) $p['poin'] ?>"
                 data-level="<?= (int) $p['level'] ?>">

            <!-- Badge Poin (pojok atas kartu) -->
            <div class="absolute top-3 right-3 flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-2.5 py-0.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                             fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm font-extrabold leading-none">+<?= (int) $p['poin'] ?> Koin</span>
            </div>

            <!-- Ikon Planet SVG -->
            <div class="planet-icon w-24 h-24 mx-auto mt-6 mb-3">
                <?= $p['ikon'] ?>
            </div>

            <!-- Nama & Deskripsi -->
            <h2 id="planet-<?= e($p['id']) ?>" class="text-xl font-black text-center leading-tight"><?= e($p['nama']) ?></h2>
            <p class="mt-1 text-sm sm:text-base font-bold text-center opacity-80"><?= e($p['deskripsi']) ?></p>

            <!-- Status Planet -->
            <div class="mt-3 flex justify-center">
                <?php if ($terbuka): ?>
                    <span class="inline-flex items-center gap-1 bg-white/70 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="status-teks"><?= e($p['status']) ?></span>
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1 bg-white/70 text-slate-600 text-xs font-extrabold px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="5" y="11" width="14" height="9" rx="2"/>
                            <path d="M8 11V8a4 4 0 0 1 8 0v3"/>
                        </svg>
                        <span class="status-teks"><?= e($p['status']) ?></span>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-auto pt-5">
                <?php if ($terbuka): ?>
                    <button type="button"
                            class="btn-jelajah w-full text-white font-extrabold text-sm sm:text-base py-2.5 rounded-full shadow transition-all duration-200 hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-4 <?= e($p['tombol']) ?>">
                        Jelajahi Planet 🚀
                    </button>
                <?php else: ?>
                    <!-- Tidak memakai atribut disabled agar klik tetap memunculkan pemberitahuan ramah -->
                    <button type="button" aria-disabled="true"
                            class="btn-terkunci w-full bg-gray-200 text-gray-500 font-extrabold text-sm sm:text-base py-2.5 rounded-full cursor-not-allowed focus:outline-none focus-visible:ring-4 focus-visible:ring-gray-300">
                        Buka Level <?= (int) $p['level'] ?> 🔒
                    </button>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</section>