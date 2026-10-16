<?php
define('BRIGHT_SPACE', true);
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/config/database.php';
require_login();

$appName = 'BRIGHT SPACE';
$user = current_user();
if (!$user) { header('Location: logout.php'); exit; }

$user_name  = $user['nama']        ?? 'Kapten';
$user_id    = (int) ($user['id']         ?? 0);
$star_coins = (int) ($user['star_coins'] ?? 0);

require __DIR__ . '/config/planets.php';

// Validasi ?planet=
$planetId = isset($_GET['planet']) ? trim($_GET['planet']) : '';
$planet   = cariPlanet($planets, $planetId);

if (!$planet || $planet['status'] !== 'Terbuka') {
    header('Location: misi.php');
    exit;
}

$pageTitle = $planet['nama'] . ' - Pilih Misi';
$arazkaSvg = file_get_contents(__DIR__ . '/components/arazka-svg.php');

// Ambil progress user untuk planet ini dari DB
$progress = [];
try {
    $rows = db_all(
        'SELECT mode_type, best_score, is_completed, attempts
         FROM user_progress WHERE user_id = ? AND planet_id = ?',
        [$user_id, $planetId]
    );
    foreach ($rows as $r) $progress[$r['mode_type']] = $r;
} catch (Exception $e) {}

// Hitung total mode wajib & bonus yang ada
$modeWajib  = [];
$modeBonus  = [];
foreach ($gameModes as $mid => $m) {
    if (($m['kategori'] ?? '') === 'wajib') $modeWajib[] = $mid;
    else $modeBonus[] = $mid;
}

// Hitung berapa yang sudah selesai di mode wajib
$wajibSelesai = 0;
foreach ($modeWajib as $mid) {
    if (!empty($progress[$mid]['is_completed'])) $wajibSelesai++;
}
$totalWajib = count($modeWajib);
$persenWajib = $totalWajib > 0 ? round(($wajibSelesai / $totalWajib) * 100) : 0;

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= e($appName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' } },
            fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
        } } };
    </script>
    <style>
        @keyframes planet-float { 0%,100%{transform:translateY(0) rotate(0);}50%{transform:translateY(-6px) rotate(3deg);} }
        @keyframes glow-pulse { 0%,100%{box-shadow:0 0 0 0 rgba(167,139,250,0.5);}50%{box-shadow:0 0 0 16px rgba(167,139,250,0);} }
        @keyframes shimmer { 0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;} }
        @keyframes bob { 0%,100%{transform:translateY(0);}50%{transform:translateY(-4px);} }

        .planet-hero-icon { animation: planet-float 3s ease-in-out infinite; }
        .arazka-bob { animation: bob 2.5s ease-in-out infinite; }
        .card-glow:hover { animation: glow-pulse 1.5s ease-in-out infinite; }

        .shimmer-bar {
            background: linear-gradient(90deg, #a78bfa 0%, #f472b6 50%, #a78bfa 100%);
            background-size: 200% 100%;
            animation: shimmer 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-nunito text-slate-800 min-h-screen antialiased" style="background-color:#F0F7FF;">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <?php include __DIR__ . '/components/header.php'; ?>

        <main class="max-w-4xl mx-auto px-4 py-6 w-full flex-1">

            <!-- Tombol kembali -->
            <a href="misi.php"
               class="inline-flex items-center gap-2 bg-white border-2 border-violet-200 hover:border-violet-400 font-extrabold text-sm px-4 py-2 rounded-full shadow-sm transition-all hover:-translate-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
                Kembali ke Peta Misi
            </a>

            <!-- HERO PLANET -->
            <section class="mt-4 relative overflow-hidden bg-gradient-to-br <?= e($planet['gradien']) ?> border-4 <?= e($planet['border']) ?> rounded-3xl p-6 sm:p-8 shadow-lg">

                <svg class="absolute top-3 right-4 w-8 h-8 opacity-40" viewBox="0 0 24 24" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                             fill="#FCD34D" stroke="#B45309" stroke-width="0.8"/>
                </svg>
                <svg class="absolute bottom-3 left-5 w-5 h-5 opacity-30" viewBox="0 0 24 24" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                             fill="#FFFFFF" stroke="#1E1B4B" stroke-width="0.6"/>
                </svg>

                <div class="relative flex flex-col sm:flex-row items-center gap-5 sm:gap-7">
                    <div class="planet-hero-icon w-28 h-28 sm:w-36 sm:h-36 shrink-0 drop-shadow-lg">
                        <?= $planet['ikon'] ?>
                    </div>

                    <div class="text-center sm:text-left flex-1">
                        <span class="inline-block bg-white/70 text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full">
                            🌍 Memulai Petualangan
                        </span>
                        <h1 class="mt-2 text-3xl sm:text-4xl font-black leading-tight">
                            <?= e($planet['nama']) ?>
                        </h1>
                        <p class="mt-1 text-base font-bold opacity-80">
                            <?= e($planet['deskripsi']) ?>
                        </p>

                        <!-- Progress Bar Misi Wajib -->
                        <div class="mt-4 max-w-sm mx-auto sm:mx-0">
                            <div class="flex items-center justify-between text-xs font-extrabold mb-1">
                                <span>⭐ Misi Wajib Selesai</span>
                                <span><?= $wajibSelesai ?> / <?= $totalWajib ?></span>
                            </div>
                            <div class="w-full bg-white/40 rounded-full h-3 overflow-hidden border-2 border-white/60">
                                <div class="h-full rounded-full transition-all duration-700 <?= $persenWajib === 100 ? 'bg-emerald-400' : 'shimmer-bar' ?>"
                                     style="width: <?= $persenWajib ?>%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SAPAAN ARAZKA -->
            <section class="mt-6 flex items-start gap-4 bg-white border-2 border-indigo-100 rounded-3xl shadow-md p-5">
                <div class="arazka-wrap arazka-bob relative w-16 h-16 sm:w-20 sm:h-20 shrink-0">
                    <?= $arazkaSvg ?>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-500">Arazka · Mentor AI</p>
                    <div class="mt-2 bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                        <p class="text-sm font-bold leading-snug text-slate-700">
                            <?php if ($wajibSelesai === 0): ?>
                                Halo, Kapten <?= e($user_name) ?>! 👋 Ini pertama kalinya kamu di
                                <span class="text-violet-600 font-black"><?= e($planet['nama']) ?></span> ya?
                                Yuk mulai dari Misi Wajib dulu! 🚀
                            <?php elseif ($persenWajib === 100): ?>
                                Luar biasa, Kapten <?= e($user_name) ?>! 🏆 Kamu sudah menyelesaikan semua misi wajib di
                                <span class="text-violet-600 font-black"><?= e($planet['nama']) ?></span>!
                                Sekarang coba Side Quest untuk koin ekstra! 💎
                            <?php else: ?>
                                Hebat, Kapten <?= e($user_name) ?>! 💪 Kamu sudah menyelesaikan
                                <span class="text-emerald-600 font-black"><?= $wajibSelesai ?> dari <?= $totalWajib ?></span>
                                misi wajib. Lanjutkan sisanya, ya! 🚀
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </section>

            <!-- PILIH MODE -->
            <section class="mt-6">
                <h2 class="text-2xl font-black text-center mb-1">Pilih Jenis Misi 🎯</h2>
                <p class="text-center text-sm font-bold opacity-70 mb-5">Pilih yang paling kamu suka, Kapten!</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- MISI WAJIB -->
                    <a href="planet-detail.php?id=<?= e($planet['id']) ?>#wajib"
                       class="card-glow group relative flex flex-col bg-gradient-to-br from-amber-100 to-yellow-200 border-4 border-amber-300 rounded-3xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer">

                        <!-- Badge status -->
                        <div class="absolute top-3 right-3 bg-amber-500 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow">
                            ⭐ Misi Wajib
                        </div>

                        <!-- Progress indicator -->
                        <?php if ($persenWajib === 100): ?>
                            <div class="absolute top-3 left-3 bg-emerald-500 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow">
                                ✓ Selesai
                            </div>
                        <?php endif; ?>

                        <div class="text-6xl mb-3 group-hover:scale-110 transition-transform duration-300 text-center">
                            ⭐
                        </div>

                        <h3 class="text-2xl font-black text-center">Misi Wajib</h3>
                        <p class="mt-2 text-sm font-bold opacity-80 text-center flex-1">
                            Kuis utama untuk kumpulkan Koin Bintang & naik level!
                        </p>

                        <!-- Mini info -->
                        <div class="mt-3 flex flex-wrap justify-center gap-2">
                            <span class="inline-flex items-center gap-1 bg-white/70 text-xs font-extrabold px-2.5 py-1 rounded-full">
                                🎮 <?= $totalWajib ?> mode
                            </span>
                            <span class="inline-flex items-center gap-1 bg-white/70 text-xs font-extrabold px-2.5 py-1 rounded-full">
                                🪙 Koin besar
                            </span>
                        </div>

                        <!-- Progress bar -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-xs font-extrabold mb-1">
                                <span>Progress</span>
                                <span><?= $wajibSelesai ?>/<?= $totalWajib ?></span>
                            </div>
                            <div class="w-full bg-white/60 rounded-full h-2 overflow-hidden">
                                <div class="h-full rounded-full <?= $persenWajib === 100 ? 'bg-emerald-500' : 'bg-amber-500' ?> transition-all duration-500"
                                     style="width: <?= $persenWajib ?>%;"></div>
                            </div>
                        </div>

                        <span class="mt-4 inline-block bg-amber-500 group-hover:bg-amber-600 text-white font-extrabold text-center py-3 rounded-full shadow transition-all">
                            Mulai Misi Wajib →
                        </span>
                    </a>

                    <!-- SIDE QUEST -->
                    <a href="planet-detail.php?id=<?= e($planet['id']) ?>#side"
                       class="card-glow group relative flex flex-col bg-gradient-to-br from-sky-100 to-cyan-200 border-4 border-sky-300 rounded-3xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer">

                        <div class="absolute top-3 right-3 bg-sky-500 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow">
                            💎 Side Quest
                        </div>

                        <div class="text-6xl mb-3 group-hover:scale-110 transition-transform duration-300 text-center">
                            💎
                        </div>

                        <h3 class="text-2xl font-black text-center">Side Quest</h3>
                        <p class="mt-2 text-sm font-bold opacity-80 text-center flex-1">
                            Tantangan bonus seru dengan minigame tambahan. Kumpulkan koin ekstra!
                        </p>

                        <!-- Mini info -->
                        <div class="mt-3 flex flex-wrap justify-center gap-2">
                            <span class="inline-flex items-center gap-1 bg-white/70 text-xs font-extrabold px-2.5 py-1 rounded-full">
                                🎮 <?= count($modeBonus) ?> mode
                            </span>
                            <span class="inline-flex items-center gap-1 bg-white/70 text-xs font-extrabold px-2.5 py-1 rounded-full">
                                🎁 Koin bonus
                            </span>
                        </div>

                        <!-- Info tambahan -->
                        <div class="mt-4 bg-white/50 rounded-2xl p-3 text-center">
                            <p class="text-xs font-bold text-slate-600">
                                ✨ Cocok untuk latihan santai & tanpa tekanan!
                            </p>
                        </div>

                        <span class="mt-4 inline-block bg-sky-500 group-hover:bg-sky-600 text-white font-extrabold text-center py-3 rounded-full shadow transition-all">
                            Mulai Side Quest →
                        </span>
                    </a>

                </div>
            </section>

            <!-- TIPS BAWAH -->
            <section class="mt-6 bg-gradient-to-r from-violet-50 to-indigo-50 border-2 border-violet-200 rounded-3xl p-5">
                <div class="flex items-start gap-3">
                    <span class="text-3xl">💡</span>
                    <div>
                        <p class="text-sm font-black text-violet-700">Tips dari Arazka</p>
                        <p class="mt-1 text-xs font-bold text-slate-600 leading-snug">
                            Jawab semua soal dengan benar = <span class="text-emerald-600 font-black">Bonus Sempurna +20%</span>!
                            Semakin tinggi akurasimu, semakin banyak koin yang kamu dapat. Semangat! 🚀
                        </p>
                    </div>
                </div>
            </section>

        </main>

        <footer class="bg-white border-t-2 border-slate-100 mt-8">
            <div class="max-w-6xl mx-auto px-4 py-4 text-center text-xs font-bold text-slate-400">
                BRIGHT SPACE EdTech · Optimized for Low-Spec School Hardware
            </div>
        </footer>
    </div>

    <?php include __DIR__ . '/components/modal-shop.php'; ?>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" style="display:none;"></div>

    <script>
        window.BRIGHT_SPACE = {
            user: {
                id: <?= (int) $user_id ?>,
                nama: <?= json_encode($user_name, JSON_UNESCAPED_UNICODE) ?>,
                star_coins: <?= (int) $star_coins ?>,
                topi: <?= json_encode($user['avatar_topi'] ?? null, JSON_UNESCAPED_UNICODE) ?>,
                kacamata: <?= json_encode($user['avatar_kacamata'] ?? null, JSON_UNESCAPED_UNICODE) ?>
            }
        };
    </script>
    <script src="assets/js/app.js" defer></script>
</body>
</html>