<?php
define('BRIGHT_SPACE', true);
session_start();
require __DIR__ . '/config/database.php';
require_login();

$appName = 'BRIGHT SPACE';
$user = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

require __DIR__ . '/config/planets.php';

$planetId = isset($_GET['id']) ? trim($_GET['id']) : '';
$planet   = cariPlanet($planets, $planetId);

if (!$planet) { header('Location: index.php'); exit; }
if ($planet['status'] !== 'Terbuka') { header('Location: index.php'); exit; }

$pageTitle = $planet['nama'] . ' - ' . $appName;
$arazkaSvg = file_get_contents(__DIR__ . '/components/arazka-svg.php');

$topiUser     = $user['avatar_topi'] ?? null;
$kacamataUser = $user['avatar_kacamata'] ?? null;

// Ambil soal dari DB per mode
$soalPerMode = [];
foreach ($gameModes as $mid => $m) {
    $soalPerMode[$mid] = get_soal($planet['id'], $m['type'], $m['jumlah_soal']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' } },
            fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
        } } };
    </script>
    <style>
        @keyframes modal-in { from{opacity:0;transform:translateY(14px) scale(0.96);}to{opacity:1;transform:translateY(0) scale(1);} }
        @keyframes planet-float { 0%,100%{transform:translateY(0) rotate(0);}50%{transform:translateY(-6px) rotate(3deg);} }
        @keyframes arazka-lompat { 0%,100%{transform:translateY(0) rotate(0);}30%{transform:translateY(-10px) rotate(-6deg);}60%{transform:translateY(0) rotate(5deg);} }
        @keyframes arazka-miring { 0%,100%{transform:rotate(0);}50%{transform:rotate(-7deg);} }
        @keyframes percik { from{opacity:1;transform:translate(0,0) scale(0.4);}to{opacity:0;transform:translate(var(--dx),var(--dy)) scale(1.1);} }
        @keyframes coin-pop { 0%{transform:scale(1);}50%{transform:scale(1.18);}100%{transform:scale(1);} }
        @keyframes timer-pulse { 0%,100%{transform:scale(1);}50%{transform:scale(1.08);} }
        .modal-panel{animation:modal-in 0.25s ease-out;}
        .planet-hero-icon{animation:planet-float 3s ease-in-out infinite;}
        .timer-warning{animation:timer-pulse 1s ease-in-out infinite;}
        .arazka-wrap .m-senang,.arazka-wrap .m-brow,.arazka-wrap .m-terbuka{display:none;}
        .arazka-wrap[data-mood="selebrasi"] .m-normal,.arazka-wrap[data-mood="selebrasi"] .m-senyum{display:none;}
        .arazka-wrap[data-mood="selebrasi"] .m-senang,.arazka-wrap[data-mood="selebrasi"] .m-terbuka{display:inline;}
        .arazka-wrap[data-mood="semangat"] .m-brow{display:inline;}
        .arazka-wrap[data-mood="selebrasi"] .arazka-svg{animation:arazka-lompat 0.7s ease-in-out 3;}
        .arazka-wrap[data-mood="semangat"] .arazka-svg{animation:arazka-miring 0.8s ease-in-out 2;}
        .percik{position:absolute;left:50%;top:50%;width:14px;height:14px;margin:-7px 0 0 -7px;animation:percik 0.9s ease-out forwards;}
        .coin-pop{animation:coin-pop 0.4s ease-out;}
        @media (prefers-reduced-motion: reduce){*,*::before,*::after{animation:none!important;transition:none!important;}}
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <?php include __DIR__ . '/components/header.php'; ?>

    <main class="max-w-4xl mx-auto px-4 py-6 w-full flex-grow">
        <a href="index.php" class="inline-flex items-center gap-2 bg-white border-2 border-violet-200 hover:border-violet-400 font-extrabold text-sm px-4 py-2 rounded-full shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M15 18l-6-6 6-6"/></svg>
            Kembali
        </a>

        <section class="mt-4 relative overflow-hidden bg-gradient-to-br <?= e($planet['gradien']) ?> border-4 <?= e($planet['border']) ?> rounded-3xl p-6 sm:p-8 shadow-lg">
            <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-7">
                <div class="planet-hero-icon w-28 h-28 sm:w-36 sm:h-36 shrink-0"><?= $planet['ikon'] ?></div>
                <div class="text-center sm:text-left flex-1">
                    <span class="inline-block bg-white/70 text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full">🌍 Pilih Misi</span>
                    <h1 class="mt-2 text-3xl sm:text-4xl font-black"><?= e($planet['nama']) ?></h1>
                    <p class="mt-1 text-base font-bold opacity-80"><?= e($planet['deskripsi']) ?></p>
                </div>
            </div>
        </section>

        <!-- 2 KARTU PERCABANGAN -->
        <section class="mt-8" id="section-kategori">
            <h2 class="text-2xl font-black text-center">Pilih Jenis Misi 🎯</h2>
            <p class="mt-1 text-center text-sm font-bold opacity-80">Ada 2 pilihan misi untukmu!</p>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <button type="button" class="kategori-card group flex flex-col text-left bg-gradient-to-br from-amber-100 to-yellow-200 border-4 border-amber-300 rounded-3xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all"
                        data-kategori="wajib">
                    <div class="text-6xl mb-3 group-hover:scale-110 transition-transform">⭐</div>
                    <h3 class="text-2xl font-black">Misi Wajib</h3>
                    <p class="mt-2 text-sm font-bold opacity-80 flex-1">Selesaikan misi utama untuk mengumpulkan Koin Bintang & naik level!</p>
                    <span class="mt-4 inline-block bg-amber-500 text-white font-extrabold text-center py-2.5 rounded-full">Pilih Misi Wajib →</span>
                </button>

                <button type="button" class="kategori-card group flex flex-col text-left bg-gradient-to-br from-sky-100 to-cyan-200 border-4 border-sky-300 rounded-3xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all"
                        data-kategori="side">
                    <div class="text-6xl mb-3 group-hover:scale-110 transition-transform">💎</div>
                    <h3 class="text-2xl font-black">Side Quest</h3>
                    <p class="mt-2 text-sm font-bold opacity-80 flex-1">Tantangan bonus untuk variasi minigame & koin ekstra!</p>
                    <span class="mt-4 inline-block bg-sky-500 text-white font-extrabold text-center py-2.5 rounded-full">Pilih Side Quest →</span>
                </button>
            </div>
        </section>

        <!-- PILIHAN GAME (muncul setelah pilih kategori) -->
        <section class="mt-8 hidden" id="section-game">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black" id="judul-game">Pilih Game</h2>
                <button type="button" id="btn-ganti-kategori" class="text-sm font-extrabold text-violet-600 hover:underline">← Ganti Kategori</button>
            </div>

            <div id="mode-container" class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($gameModes as $mid => $mode):
                    $badge = $kategoriBadge[$mode['kategori']] ?? $kategoriBadge['side'];
                    $labelReward = ($mode['reward'] > 0) ? '+' . $mode['reward'] . ' Koin' : 'Tanpa Koin';
                ?>
                    <article class="mode-card group relative flex flex-col bg-gradient-to-br <?= e($mode['warna']['bg']) ?> border-2 <?= e($mode['warna']['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                             data-mode-id="<?= e($mid) ?>"
                             data-kategori="<?= e($mode['kategori']) ?>"
                             style="display:none;">
                        <div class="absolute top-3 left-3 <?= e($badge['kelas']) ?> text-xs font-extrabold px-3 py-1 rounded-full"><?= e($badge['teks']) ?></div>
                        <div class="absolute top-3 right-3 bg-white/90 border-2 border-amber-300 text-xs font-extrabold px-2 py-1 rounded-full"><?= e($labelReward) ?></div>
                        <div class="text-5xl mb-2 mt-6 group-hover:scale-110 transition-transform"><?= $mode['ikon'] ?></div>
                        <h3 class="text-xl font-black"><?= e($mode['judul']) ?></h3>
                        <p class="mt-2 text-sm font-bold opacity-80 flex-1"><?= e($mode['deskripsi']) ?></p>
                        <button type="button"
                                class="btn-mode w-full mt-4 text-white font-extrabold text-base py-3 rounded-full shadow <?= e($mode['warna']['tombol']) ?> hover:scale-105">
                            Mulai Game →
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- Modal Quiz -->
    <div id="kuis-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-cosmic-text/70" style="display:none;" role="dialog" aria-modal="true">
        <div class="modal-panel relative w-full max-w-lg max-h-[92vh] overflow-y-auto bg-cosmic-card border-4 border-cosmic-accent rounded-3xl shadow-2xl p-5 sm:p-6">
            <button type="button" id="kuis-tutup" class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-white/80 hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>

            <!-- TAHAP SOAL -->
            <div id="kuis-tahap-soal">
                <div class="flex items-center gap-3 pr-10">
                    <div id="kuis-ikon" class="w-14 h-14 shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-violet-600">Misi Berlangsung</p>
                        <h2 id="kuis-judul" class="text-lg font-black leading-tight"></h2>
                    </div>
                    <div id="kuis-timer-box" class="hidden shrink-0 bg-rose-100 border-2 border-rose-300 rounded-2xl px-3 py-1 text-center">
                        <p class="text-[10px] font-extrabold uppercase text-rose-700">Waktu</p>
                        <p id="kuis-timer" class="text-lg font-black text-rose-700 leading-none">05:00</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs font-extrabold mb-1">
                        <span id="kuis-progress-teks">Soal 1 dari 10</span>
                        <span id="kuis-progress-benar">✅ 0</span>
                    </div>
                    <div class="w-full bg-white/60 rounded-full h-3 overflow-hidden border-2 border-violet-200">
                        <div id="kuis-progress-bar" class="bg-violet-500 h-full rounded-full transition-all duration-300" style="width:0%;"></div>
                    </div>
                </div>

                <p id="kuis-pertanyaan" class="mt-4 text-lg font-extrabold leading-snug"></p>
                <div id="kuis-pilihan" class="mt-4 grid gap-3"></div>

                <div id="kuis-feedback" class="mt-4 flex items-start gap-3">
                    <div id="arazka-modal" class="arazka-wrap relative w-14 h-14 shrink-0">
                        <?= $arazkaSvg ?>
                        <div id="kuis-percik" class="absolute inset-0 pointer-events-none"></div>
                    </div>
                    <div id="kuis-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                        <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                        <p id="kuis-fb-judul" class="text-base font-black"></p>
                        <p id="kuis-fb-teks" class="mt-1 text-sm font-bold"></p>
                    </div>
                </div>

                <button type="button" id="kuis-lanjut" class="hidden w-full mt-4 bg-violet-500 text-white font-extrabold text-base py-3 rounded-full shadow hover:bg-violet-600">
                    Soal Berikutnya →
                </button>
            </div>

            <!-- TAHAP HASIL -->
            <div id="kuis-tahap-hasil" class="hidden text-center">
                <div class="text-5xl mb-2">🎉</div>
                <h2 class="text-2xl font-black">Misi Selesai!</h2>

                <div class="mt-4 mx-auto max-w-xs bg-white border-4 border-cosmic-accent rounded-3xl p-5 shadow-lg">
                    <p class="text-xs font-extrabold uppercase text-violet-600 mb-1">Skor Kamu</p>
                    <p class="text-5xl font-black leading-none">
                        <span id="hasil-benar">0</span><span class="text-2xl opacity-60">/</span><span id="hasil-total">10</span>
                    </p>
                    <p id="hasil-akurasi" class="mt-2 text-lg font-extrabold text-violet-700">Akurasi: 0%</p>
                    <div class="mt-3 w-full bg-violet-100 rounded-full h-3 overflow-hidden">
                        <div id="hasil-bar" class="bg-emerald-500 h-full rounded-full transition-all duration-700" style="width:0%;"></div>
                    </div>
                </div>

                <div id="hasil-reward-box" class="mt-4 inline-flex items-center gap-2 bg-cosmic-accent border-4 border-amber-300 rounded-full pl-2 pr-4 py-2 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7 shrink-0" aria-hidden="true">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-lg font-black">+<span id="hasil-reward">0</span> Koin Bintang</span>
                </div>

                <p id="hasil-bonus-teks" class="mt-2 text-sm font-extrabold text-emerald-700 hidden">🌟 Bonus Sempurna! +20%</p>

                <div class="mt-5 flex items-start gap-3 text-left">
                    <div id="arazka-hasil" class="arazka-wrap relative w-14 h-14 shrink-0">
                        <?= $arazkaSvg ?>
                        <div id="hasil-percik" class="absolute inset-0 pointer-events-none"></div>
                    </div>
                    <div id="hasil-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-emerald-50 border-emerald-300">
                        <p class="text-xs font-extrabold text-emerald-700">Arazka</p>
                        <p id="hasil-komentar" class="text-sm font-bold text-emerald-900"></p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <button type="button" id="hasil-ulang" class="bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-3 rounded-full shadow">🔁 Coba Lagi</button>
                    <button type="button" id="hasil-selesai" class="bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm py-3 rounded-full shadow">🚀 Ambil & Kembali</button>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/components/modal-shop.php'; ?>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]"></div>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 pt-4 pb-24 md:pb-4 text-center text-xs font-bold">
            BRIGHT SPACE EdTech
        </div>
    </footer>

    <script>
        window.BRIGHT_SPACE = {
            user: {
                id: <?= (int) $user['id'] ?>,
                nama: <?= json_encode($user['nama'], JSON_UNESCAPED_UNICODE) ?>,
                star_coins: <?= (int) $user['star_coins'] ?>,
                topi: <?= json_encode($topiUser, JSON_UNESCAPED_UNICODE) ?>,
                kacamata: <?= json_encode($kacamataUser, JSON_UNESCAPED_UNICODE) ?>
            },
            planet: {
                id: <?= json_encode($planet['id']) ?>,
                nama: <?= json_encode($planet['nama'], JSON_UNESCAPED_UNICODE) ?>,
                ikon: <?= json_encode($planet['ikon'], JSON_UNESCAPED_UNICODE) ?>
            },
            gameModes: <?= json_encode($gameModes, JSON_UNESCAPED_UNICODE) ?>,
            soalPerMode: <?= json_encode($soalPerMode, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        };
    </script>
    <script src="assets/js/app.js" defer></script>
</body>
</html>