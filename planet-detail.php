<?php
// ============================================================
// planet-detail.php
// Halaman detail planet + pilihan mode game.
// Parameter URL: ?id=[planet_id]   (contoh: ?id=matematika)
//
// Kompatibel dengan assets/js/app.js yang membaca:
//   - window.BRIGHT_SPACE.kuis     (bank soal misi wajib)
//   - window.BRIGHT_SPACE.latihan  (bank soal latihan tambahan)
//   - window.BRIGHT_SPACE.planet   (info planet aktif)
//   - .mode-card[data-mode-id] + .btn-mode (pemicu kuis)
// ============================================================

define('BRIGHT_SPACE', true);

$appName   = 'BRIGHT SPACE';
$starCoins = 120;

function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// --- Cek file wajib ---
$fileWajib = [
    __DIR__ . '/config/planets.php',
    __DIR__ . '/components/arazka-svg.php',
    __DIR__ . '/components/header.php',
    __DIR__ . '/components/modal-quiz.php',
    __DIR__ . '/assets/js/app.js',
];
foreach ($fileWajib as $fileCek) {
    if (!is_file($fileCek)) {
        http_response_code(500);
        exit('BRIGHT SPACE: file tidak ditemukan -> ' . e($fileCek));
    }
}
unset($fileWajib, $fileCek);

// --- Muat data terpusat ---
require __DIR__ . '/config/planets.php';

// --- Ambil ID planet dari query string ---
$planetId = isset($_GET['id']) ? trim($_GET['id']) : '';

// --- Validasi planet ---
$planet = cariPlanet($planets, $planetId);

if (!$planet) {
    header('Location: index.php');
    exit;
}

if ($planet['status'] !== 'Terbuka') {
    header('Location: index.php');
    exit;
}

$pageTitle = $planet['nama'] . ' - ' . $appName;
$arazkaSvg = file_get_contents(__DIR__ . '/components/arazka-svg.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($planet['nama']) ?> - Pilih mode misi di BRIGHT SPACE.">
    <meta name="theme-color" content="#E0F2FE">
    <title><?= e($pageTitle) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cosmic: {
                            bg:     '#E0F2FE',
                            card:   '#EDE9FE',
                            accent: '#FEF3C7',
                            text:   '#1E1B4B'
                        }
                    },
                    fontFamily: {
                        nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif']
                    }
                }
            }
        };
    </script>

    <style>
        @keyframes modal-in {
            from { opacity: 0; transform: translateY(14px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes planet-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50%      { transform: translateY(-6px) rotate(3deg); }
        }
        @keyframes arazka-lompat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            30%      { transform: translateY(-10px) rotate(-6deg); }
            60%      { transform: translateY(0) rotate(5deg); }
        }
        @keyframes arazka-miring {
            0%, 100% { transform: rotate(0deg); }
            50%      { transform: rotate(-7deg); }
        }
        @keyframes percik {
            from { opacity: 1; transform: translate(0, 0) scale(0.4); }
            to   { opacity: 0; transform: translate(var(--dx), var(--dy)) scale(1.1); }
        }
        @keyframes coin-pop {
            0%   { transform: scale(1); }
            50%  { transform: scale(1.18); }
            100% { transform: scale(1); }
        }
        .modal-panel { animation: modal-in 0.25s ease-out; }
        .planet-hero-icon { animation: planet-float 3s ease-in-out infinite; }

        .arazka-wrap .m-senang,
        .arazka-wrap .m-brow,
        .arazka-wrap .m-terbuka { display: none; }

        .arazka-wrap[data-mood="selebrasi"] .m-normal,
        .arazka-wrap[data-mood="selebrasi"] .m-senyum { display: none; }
        .arazka-wrap[data-mood="selebrasi"] .m-senang,
        .arazka-wrap[data-mood="selebrasi"] .m-terbuka { display: inline; }
        .arazka-wrap[data-mood="semangat"] .m-brow { display: inline; }

        .arazka-wrap[data-mood="selebrasi"] .arazka-svg { animation: arazka-lompat 0.7s ease-in-out 3; }
        .arazka-wrap[data-mood="semangat"] .arazka-svg  { animation: arazka-miring 0.8s ease-in-out 2; }

        .percik {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 14px;
            height: 14px;
            margin: -7px 0 0 -7px;
            animation: percik 0.9s ease-out forwards;
        }
        .coin-pop { animation: coin-pop 0.4s ease-out; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <?php include __DIR__ . '/components/header.php'; ?>

    <main id="main-content" class="max-w-4xl mx-auto px-4 py-6 w-full flex-grow">

        <!-- Tombol Kembali -->
        <a href="index.php"
           class="inline-flex items-center gap-2 bg-white border-2 border-violet-200 hover:border-violet-400 text-cosmic-text font-extrabold text-sm px-4 py-2 rounded-full shadow-sm transition-all duration-150 hover:-translate-x-1 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Kembali ke Dashboard
        </a>

        <!-- ===================== HERO PLANET ===================== -->
        <section aria-labelledby="judul-planet"
                 class="mt-4 relative overflow-hidden bg-gradient-to-br <?= e($planet['gradien']) ?> border-4 <?= e($planet['border']) ?> rounded-3xl p-6 sm:p-8 shadow-lg">

            <svg class="absolute top-3 right-4 w-8 h-8 opacity-40" viewBox="0 0 24 24" aria-hidden="true">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                         fill="#FCD34D" stroke="#B45309" stroke-width="0.8"/>
            </svg>
            <svg class="absolute bottom-4 left-5 w-5 h-5 opacity-30" viewBox="0 0 24 24" aria-hidden="true">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                         fill="#FFFFFF" stroke="#1E1B4B" stroke-width="0.6"/>
            </svg>

            <div class="relative flex flex-col sm:flex-row items-center gap-5 sm:gap-7">
                <div class="planet-hero-icon w-28 h-28 sm:w-36 sm:h-36 shrink-0 drop-shadow-lg">
                    <?= $planet['ikon'] ?>
                </div>

                <div class="text-center sm:text-left flex-1">
                    <p class="inline-block bg-white/70 text-cosmic-text text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full">
                        🌍 Pilih Misi
                    </p>
                    <h1 id="judul-planet" class="mt-2 text-3xl sm:text-4xl font-black leading-tight">
                        <?= e($planet['nama']) ?>
                    </h1>
                    <p class="mt-1 text-base sm:text-lg font-bold opacity-80">
                        <?= e($planet['deskripsi']) ?>
                    </p>

                    <div class="mt-3 flex flex-wrap justify-center sm:justify-start gap-2">
                        <span class="inline-flex items-center gap-1 bg-white/80 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                         fill="#FCD34D" stroke="#B45309" stroke-width="1"/>
                            </svg>
                            Reward: +<?= (int) $planet['poin'] ?> Koin
                        </span>
                        <span class="inline-flex items-center gap-1 bg-white/80 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full">
                            ✅ Status: <?= e($planet['status']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- SLOT ARAZKA -->
            <div id="arazka-hero-slot"
                 class="mt-6 relative bg-white/50 border-2 border-dashed border-violet-300 rounded-2xl p-4 flex items-center gap-4"
                 aria-label="Slot karakter Arazka">
                <div id="arazka-hero-avatar" class="arazka-wrap relative w-20 h-20 sm:w-24 sm:h-24 shrink-0">
                    <?= $arazkaSvg ?>
                    <div id="arazka-hero-percik" class="absolute inset-0 pointer-events-none"></div>
                </div>
                <div id="arazka-hero-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                    <span class="absolute -left-1.5 top-5 w-3 h-3 rotate-45 bg-inherit border-l-2 border-b-2 border-inherit"></span>
                    <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                    <p id="arazka-hero-judul" class="text-base font-black leading-snug">Halo, Kapten! 👋</p>
                    <p id="arazka-hero-teks" class="mt-1 text-sm font-bold leading-snug">
                        Pilih misi yang ingin kamu jalankan hari ini. Misi Wajib memberi Koin Bintang, Latihan Tambahan untuk memperkuat pemahamanmu!
                    </p>
                </div>
            </div>

        </section>

        <!-- ===================== PILIHAN MODE ===================== -->
        <section aria-labelledby="judul-mode" class="mt-8">
            <h2 id="judul-mode" class="text-2xl sm:text-3xl font-black text-center">
                Pilih Mode Misi 🚀
            </h2>
            <p class="mt-1 text-center text-sm sm:text-base font-bold opacity-80">
                Ada 2 pilihan misi untukmu. Pilih yang paling kamu suka!
            </p>

            <div id="mode-container" class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php foreach ($modeGame as $mode): ?>
                    <article class="mode-card group relative flex flex-col bg-gradient-to-br <?= e($mode['warna']['bg']) ?> border-2 <?= e($mode['warna']['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:scale-[1.02]"
                             data-mode-id="<?= e($mode['id']) ?>">

                        <div class="absolute top-3 right-3 <?= e($mode['warna']['badge']) ?> text-xs font-extrabold px-3 py-1 rounded-full shadow-sm">
                            <?= e($mode['label_koin']) ?>
                        </div>

                        <div class="text-5xl sm:text-6xl mb-2 group-hover:scale-110 transition-transform duration-300">
                            <?= $mode['ikon'] ?>
                        </div>

                        <p class="text-xs font-extrabold uppercase tracking-wider text-cosmic-text/70">
                            <?= e($mode['subjudul']) ?>
                        </p>
                        <h3 class="text-xl sm:text-2xl font-black leading-tight">
                            <?= e($mode['judul']) ?>
                        </h3>

                        <p class="mt-2 text-sm font-bold leading-snug opacity-80 flex-1">
                            <?= e($mode['deskripsi']) ?>
                        </p>

                        <button type="button"
                                class="btn-mode w-full mt-4 text-white font-extrabold text-base py-3 rounded-full shadow transition-all duration-200 hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-4 <?= e($mode['warna']['tombol']) ?>">
                            Mulai <?= e($mode['judul']) ?> →
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/components/modal-quiz.php'; ?>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" role="status" aria-live="polite"></div>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 pt-4 pb-24 md:pb-4 text-center text-xs sm:text-sm font-bold">
            Optimized for Low-Spec School Hardware (RAM 2GB-4GB) | BRIGHT SPACE EdTech
        </div>
    </footer>

    <!-- ============================================================
         DATA UNTUK JAVASCRIPT
         Struktur WAJIB sama persis dengan yang dibaca app.js:
           window.BRIGHT_SPACE.kuis     → bank soal misi wajib
           window.BRIGHT_SPACE.latihan  → bank soal latihan
           window.BRIGHT_SPACE.planet   → planet aktif
         ============================================================ -->
    <script>
        window.BRIGHT_SPACE = {
            kuis:    <?= json_encode($kuis,    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            latihan: <?= json_encode($latihan, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            planet:  <?= json_encode([
                'id'   => $planet['id'],
                'nama' => $planet['nama'],
                'poin' => (int) $planet['poin'],
                'ikon' => $planet['ikon'],
            ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        };

        // DEBUG: cek di Console browser apakah data terisi
        console.log('[BRIGHT SPACE] window.BRIGHT_SPACE =', window.BRIGHT_SPACE);
        console.log('[BRIGHT SPACE] mode-card ditemukan:', document.querySelectorAll('.mode-card').length);
        console.log('[BRIGHT SPACE] btn-mode ditemukan:', document.querySelectorAll('.btn-mode').length);
    </script>

    <script src="assets/js/app.js" defer></script>
</body>
</html>