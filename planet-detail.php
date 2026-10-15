<?php
// ============================================================
// planet-detail.php - Halaman detail planet + 5 mode game
// Parameter URL: ?id=[planet_id]
// ============================================================
define('BRIGHT_SPACE', true);

$appName   = 'BRIGHT SPACE';
$starCoins = 120;

function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

$fileWajib = [
    __DIR__ . '/config/planets.php',
    __DIR__ . '/components/arazka-svg.php',
    __DIR__ . '/components/header.php',
    __DIR__ . '/components/modal-quiz.php',
    __DIR__ . '/components/modal-shop.php',
    __DIR__ . '/assets/js/app.js',
];
foreach ($fileWajib as $fileCek) {
    if (!is_file($fileCek)) {
        http_response_code(500);
        exit('BRIGHT SPACE: file tidak ditemukan -> ' . e($fileCek));
    }
}
unset($fileWajib, $fileCek);

require __DIR__ . '/config/planets.php';

$planetId = isset($_GET['id']) ? trim($_GET['id']) : '';
$planet   = cariPlanet($planets, $planetId);

if (!$planet) { header('Location: index.php'); exit; }
if ($planet['status'] !== 'Terbuka') { header('Location: index.php'); exit; }

$pageTitle = $planet['nama'] . ' - ' . $appName;
$arazkaSvg = file_get_contents(__DIR__ . '/components/arazka-svg.php');

$poinKuisOverride = 50;

// Item toko untuk modal-shop.php
$items = [
    ['id'=>'kacamata-bintang','nama'=>'Kacamata Bintang','deskripsi'=>'Lihat bintang jadi lebih berkilau!','harga'=>40,'ikon'=>'<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true"><circle cx="32" cy="32" r="30" fill="#E0F2FE"/><path d="M6 30h4M54 30h4" stroke="#1E1B4B" stroke-width="3" stroke-linecap="round"/><rect x="10" y="22" width="19" height="16" rx="6" fill="#FDE68A" stroke="#1E1B4B" stroke-width="2.5"/><rect x="35" y="22" width="19" height="16" rx="6" fill="#FDE68A" stroke="#1E1B4B" stroke-width="2.5"/><path d="M29 30h6" stroke="#1E1B4B" stroke-width="2.5" stroke-linecap="round"/><polygon points="19.5,26 20.8,29 24,29.2 21.5,31.2 22.4,34.4 19.5,32.6 16.6,34.4 17.5,31.2 15,29.2 18.2,29" fill="#F59E0B"/><polygon points="44.5,26 45.8,29 49,29.2 46.5,31.2 47.4,34.4 44.5,32.6 41.6,34.4 42.5,31.2 40,29.2 43.2,29" fill="#F59E0B"/></svg>'],
    ['id'=>'topi-astronaut','nama'=>'Topi Astronaut Pastel','deskripsi'=>'Topi lucu untuk Kapten cilik!','harga'=>50,'ikon'=>'<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true"><circle cx="32" cy="32" r="30" fill="#EDE9FE"/><line x1="32" y1="20" x2="32" y2="14" stroke="#1E1B4B" stroke-width="2.5" stroke-linecap="round"/><circle cx="32" cy="12" r="3.5" fill="#FCD34D" stroke="#B45309" stroke-width="1.5"/><path d="M12 40a20 20 0 0 1 40 0z" fill="#C4B5FD" stroke="#1E1B4B" stroke-width="2.5" stroke-linejoin="round"/><path d="M18 38a14 14 0 0 1 28 0z" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="2" stroke-linejoin="round"/><path d="M23 32q3-5 8-5" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/><rect x="9" y="38" width="46" height="8" rx="4" fill="#F9A8D4" stroke="#1E1B4B" stroke-width="2.5"/></svg>'],
    ['id'=>'planet-pelangi','nama'=>'Stiker Planet Pelangi','deskripsi'=>'Planet warna-warni yang ceria!','harga'=>60,'ikon'=>'<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true"><defs><clipPath id="klip-pelangi2"><circle cx="32" cy="32" r="18"/></clipPath></defs><circle cx="32" cy="32" r="30" fill="#EDE9FE"/><g clip-path="url(#klip-pelangi2)"><rect x="12" y="14" width="40" height="6" fill="#F87171"/><rect x="12" y="20" width="40" height="6" fill="#FB923C"/><rect x="12" y="26" width="40" height="6" fill="#FDE047"/><rect x="12" y="32" width="40" height="6" fill="#4ADE80"/><rect x="12" y="38" width="40" height="6" fill="#60A5FA"/><rect x="12" y="44" width="40" height="6" fill="#A78BFA"/></g><circle cx="32" cy="32" r="18" fill="none" stroke="#1E1B4B" stroke-width="2.5"/><circle cx="25" cy="25" r="3" fill="#FFFFFF" fill-opacity="0.7"/></svg>'],
    ['id'=>'roket-neon','nama'=>'Roket Neon','deskripsi'=>'Roket bercahaya yang melesat cepat!','harga'=>80,'ikon'=>'<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true"><circle cx="32" cy="32" r="30" fill="#312E81"/><path d="M22 36l-7 8 1 6 8-5z" fill="#F472B6"/><path d="M42 36l7 8-1 6-8-5z" fill="#F472B6"/><path d="M32 10c7 5 10 13 10 21 0 5-1 9-2.5 13h-15C23 40 22 36 22 31c0-8 3-16 10-21z" fill="#22D3EE" stroke="#A5F3FC" stroke-width="2" stroke-linejoin="round"/><circle cx="32" cy="27" r="5" fill="#1E1B4B" stroke="#F0ABFC" stroke-width="2"/><path d="M27 46c-1 4 0 8 5 11 5-3 6-7 5-11z" fill="#FDE047" stroke="#FB923C" stroke-width="1.5" stroke-linejoin="round"/></svg>'],
    ['id'=>'badge-emas','nama'=>'Warna Badge Emas','deskripsi'=>'Badge Koinmu jadi berkilau emas!','harga'=>100,'ikon'=>'<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true"><circle cx="32" cy="32" r="30" fill="#FEF3C7"/><polygon points="22,8 30,8 34,26 26,26" fill="#A78BFA"/><polygon points="42,8 34,8 30,26 38,26" fill="#818CF8"/><circle cx="32" cy="39" r="16" fill="#FBBF24" stroke="#B45309" stroke-width="2.5"/><circle cx="32" cy="39" r="11" fill="#FCD34D" stroke="#F59E0B" stroke-width="1.5"/><polygon points="32,31 34.4,36.4 40.2,37 35.8,40.8 37.1,46.5 32,43.5 26.9,46.5 28.2,40.8 23.8,37 29.6,36.4" fill="#FFFFFF" stroke="#B45309" stroke-width="1" stroke-linejoin="round"/></svg>'],
    ['id'=>'mahkota-galaksi','nama'=>'Mahkota Galaksi','deskripsi'=>'Mahkota spesial untuk Kapten hebat!','harga'=>150,'ikon'=>'<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true"><circle cx="32" cy="32" r="30" fill="#FEF3C7"/><path d="M12 44l-3-22 12 10 11-16 11 16 12-10-3 22z" fill="#FCD34D" stroke="#B45309" stroke-width="2.5" stroke-linejoin="round"/><rect x="12" y="44" width="40" height="7" rx="2" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2"/><circle cx="9" cy="21" r="3" fill="#F472B6"/><circle cx="55" cy="21" r="3" fill="#F472B6"/><circle cx="32" cy="15" r="3" fill="#22D3EE"/></svg>'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($planet['nama']) ?> - 5 mode game edukatif di BRIGHT SPACE.">
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
        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(167,139,250,0.4); }
            50%      { box-shadow: 0 0 0 8px rgba(167,139,250,0); }
        }
        .modal-panel { animation: modal-in 0.25s ease-out; }
        .planet-hero-icon { animation: planet-float 3s ease-in-out infinite; }
        .mode-card:hover { animation: glow-pulse 1.5s ease-in-out infinite; }

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

    <main id="main-content" class="max-w-6xl mx-auto px-4 py-6 w-full flex-grow">

        <a href="index.php"
           class="inline-flex items-center gap-2 bg-white border-2 border-violet-200 hover:border-violet-400 text-cosmic-text font-extrabold text-sm px-4 py-2 rounded-full shadow-sm transition-all duration-150 hover:-translate-x-1 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Kembali ke Dashboard
        </a>

        <section aria-labelledby="judul-planet"
                 class="mt-4 relative overflow-hidden bg-gradient-to-br <?= e($planet['gradien']) ?> border-4 <?= e($planet['border']) ?> rounded-3xl p-6 sm:p-8 shadow-lg">

            <svg class="absolute top-3 right-4 w-8 h-8 opacity-40" viewBox="0 0 24 24" aria-hidden="true">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                         fill="#FCD34D" stroke="#B45309" stroke-width="0.8"/>
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
                        <span class="inline-flex items-center gap-1 bg-white/80 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full">
                            ✅ Status: <?= e($planet['status']) ?>
                        </span>
                        <span class="inline-flex items-center gap-1 bg-white/80 text-violet-800 text-xs font-extrabold px-3 py-1 rounded-full">
                            🎮 5 Mode Tersedia
                        </span>
                    </div>
                </div>
            </div>

            <div id="arazka-hero-slot"
                 class="mt-6 relative bg-white/50 border-2 border-dashed border-violet-300 rounded-2xl p-4 flex items-center gap-4">
                <div id="arazka-hero-avatar" class="arazka-wrap relative w-20 h-20 sm:w-24 sm:h-24 shrink-0">
                    <?= $arazkaSvg ?>
                    <div id="arazka-hero-percik" class="absolute inset-0 pointer-events-none"></div>
                </div>
                <div id="arazka-hero-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                    <span class="absolute -left-1.5 top-5 w-3 h-3 rotate-45 bg-inherit border-l-2 border-b-2 border-inherit"></span>
                    <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                    <p id="arazka-hero-judul" class="text-base font-black leading-snug">Halo, Kapten! 👋</p>
                    <p id="arazka-hero-teks" class="mt-1 text-sm font-bold leading-snug">
                        5 mode misi sudah siap! Pilih yang paling kamu suka. Misi Wajib & Koin Bonus memberi Koin Bintang, Mode Santai untuk latihan bebas.
                    </p>
                </div>
            </div>
        </section>

        <section aria-labelledby="judul-mode" class="mt-8">
            <h2 id="judul-mode" class="text-2xl sm:text-3xl font-black text-center">
                Pilih Mode Misi 🚀
            </h2>
            <p class="mt-1 text-center text-sm sm:text-base font-bold opacity-80">
                Ada 5 pilihan misi seru untukmu!
            </p>

            <div id="mode-container" class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($gameModes as $mode):
                    $badge = $kategoriBadge[$mode['kategori']] ?? $kategoriBadge['santai'];
                    $labelReward = ($mode['reward'] > 0) ? '+' . $mode['reward'] . ' Koin' : 'Tanpa Koin';
                ?>
                    <article class="mode-card group relative flex flex-col bg-gradient-to-br <?= e($mode['warna']['bg']) ?> border-2 <?= e($mode['warna']['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:scale-[1.02]"
                             data-mode-id="<?= e($mode['id']) ?>"
                             data-mode-type="<?= e($mode['type']) ?>"
                             data-mode-reward="<?= (int) $mode['reward'] ?>"
                             data-mode-kategori="<?= e($mode['kategori']) ?>">

                        <div class="absolute top-3 left-3 <?= e($badge['kelas']) ?> text-xs font-extrabold px-3 py-1 rounded-full shadow-sm">
                            <?= e($badge['teks']) ?>
                        </div>

                        <div class="absolute top-3 right-3 bg-white/90 border-2 border-amber-300 text-cosmic-text text-xs font-extrabold px-3 py-1 rounded-full shadow-sm inline-flex items-center gap-1">
                            <?php if ($mode['reward'] > 0): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                             fill="#FCD34D" stroke="#B45309" stroke-width="1"/>
                                </svg>
                            <?php endif; ?>
                            <?= e($labelReward) ?>
                        </div>

                        <div class="text-5xl sm:text-6xl mb-2 mt-4 group-hover:scale-110 transition-transform duration-300">
                            <?= $mode['ikon'] ?>
                        </div>

                        <h3 class="text-xl font-black leading-tight">
                            <?= e($mode['judul']) ?>
                        </h3>

                        <p class="mt-2 text-sm font-bold leading-snug opacity-80 flex-1">
                            <?= e($mode['deskripsi']) ?>
                        </p>

                        <button type="button"
                                class="btn-mode w-full mt-4 text-white font-extrabold text-base py-3 rounded-full shadow transition-all duration-200 hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-4 <?= e($mode['warna']['tombol']) ?>">
                            Mulai Game →
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/components/modal-quiz.php'; ?>
    <?php include __DIR__ . '/components/modal-shop.php'; ?>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" role="status" aria-live="polite"></div>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 pt-4 pb-24 md:pb-4 text-center text-xs sm:text-sm font-bold">
            Optimized for Low-Spec School Hardware (RAM 2GB-4GB) | BRIGHT SPACE EdTech
        </div>
    </footer>

    <script>
        window.BRIGHT_SPACE = {
            kuis:      <?= json_encode($kuis, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            latihan:   <?= json_encode($latihan, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            gameModes: <?= json_encode($gameModes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            planet:    <?= json_encode([
                'id'   => $planet['id'],
                'nama' => $planet['nama'],
                'poin' => $poinKuisOverride,
                'ikon' => $planet['ikon'],
            ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        };
    </script>

    <script src="assets/js/app.js" defer></script>
</body>
</html>