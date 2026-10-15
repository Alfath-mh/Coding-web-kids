<?php
define('BRIGHT_SPACE', true);
session_start();
require __DIR__ . '/config/database.php';
require_login();

$appName   = 'BRIGHT SPACE';
$pageTitle = 'BRIGHT SPACE - Petualangan Belajar';
$user      = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

require __DIR__ . '/config/planets.php';

$arazkaSvg = file_get_contents(__DIR__ . '/components/arazka-svg.php');

// Terapkan outfit Arazka dari DB
$topiUser     = $user['avatar_topi'] ?? null;
$kacamataUser = $user['avatar_kacamata'] ?? null;
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
        @keyframes coin-pop { 0%{transform:scale(1);}50%{transform:scale(1.18);}100%{transform:scale(1);} }
        @keyframes planet-float { 0%,100%{transform:translateY(0) rotate(0);}50%{transform:translateY(-4px) rotate(4deg);} }
        @keyframes modal-in { from{opacity:0;transform:translateY(14px) scale(0.96);}to{opacity:1;transform:translateY(0) scale(1);} }
        @keyframes arazka-lompat { 0%,100%{transform:translateY(0) rotate(0);}30%{transform:translateY(-10px) rotate(-6deg);}60%{transform:translateY(0) rotate(5deg);} }
        @keyframes arazka-miring { 0%,100%{transform:rotate(0);}50%{transform:rotate(-7deg);} }
        @keyframes percik { from{opacity:1;transform:translate(0,0) scale(0.4);}to{opacity:0;transform:translate(var(--dx),var(--dy)) scale(1.1);} }
        .coin-pop{animation:coin-pop 0.4s ease-out;}
        .planet-card.is-open:hover .planet-icon{animation:planet-float 1.4s ease-in-out infinite;}
        .modal-panel{animation:modal-in 0.25s ease-out;}
        .arazka-wrap .m-senang,.arazka-wrap .m-brow,.arazka-wrap .m-terbuka{display:none;}
        .arazka-wrap[data-mood="selebrasi"] .m-normal,.arazka-wrap[data-mood="selebrasi"] .m-senyum{display:none;}
        .arazka-wrap[data-mood="selebrasi"] .m-senang,.arazka-wrap[data-mood="selebrasi"] .m-terbuka{display:inline;}
        .arazka-wrap[data-mood="semangat"] .m-brow{display:inline;}
        .arazka-wrap[data-mood="selebrasi"] .arazka-svg{animation:arazka-lompat 0.7s ease-in-out 3;}
        .arazka-wrap[data-mood="semangat"] .arazka-svg{animation:arazka-miring 0.8s ease-in-out 2;}
        .percik{position:absolute;left:50%;top:50%;width:14px;height:14px;margin:-7px 0 0 -7px;animation:percik 0.9s ease-out forwards;}
        @media (prefers-reduced-motion: reduce){*,*::before,*::after{animation:none!important;transition:none!important;}}
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <?php include __DIR__ . '/components/header.php'; ?>

    <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-grow">
        <section class="mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-black">Halo, Kapten <?= e($user['nama']) ?>! 👋</h1>
            <p class="mt-2 text-base sm:text-lg font-bold opacity-80">Pilih planet petualanganmu hari ini! 🚀</p>
        </section>

        <section id="grid-planet" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($planets as $p): $terbuka = ($p['status'] === 'Terbuka'); ?>
                <?php if ($terbuka): ?>
                    <a href="planet-detail.php?id=<?= e($p['id']) ?>"
                       class="planet-card is-open relative flex flex-col bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                       data-planet-id="<?= e($p['id']) ?>">
                        <div class="planet-icon w-24 h-24 mx-auto mt-6 mb-3"><?= $p['ikon'] ?></div>
                        <h2 class="text-xl font-black text-center leading-tight"><?= e($p['nama']) ?></h2>
                        <p class="mt-1 text-sm font-bold text-center opacity-80"><?= e($p['deskripsi']) ?></p>
                        <div class="mt-3 flex justify-center">
                            <span class="inline-flex items-center gap-1 bg-white/70 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full">✅ <?= e($p['status']) ?></span>
                        </div>
                        <div class="mt-auto pt-5">
                            <span class="block w-full text-center text-white font-extrabold text-sm sm:text-base py-2.5 rounded-full shadow <?= e($p['tombol']) ?>">Jelajahi Planet 🚀</span>
                        </div>
                    </a>
                <?php else: ?>
                    <article class="planet-card opacity-90 relative flex flex-col bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md"
                             data-level="<?= (int) $p['level'] ?>">
                        <div class="planet-icon w-24 h-24 mx-auto mt-6 mb-3"><?= $p['ikon'] ?></div>
                        <h2 class="text-xl font-black text-center leading-tight"><?= e($p['nama']) ?></h2>
                        <p class="mt-1 text-sm font-bold text-center opacity-80"><?= e($p['deskripsi']) ?></p>
                        <div class="mt-3 flex justify-center">
                            <span class="inline-flex items-center gap-1 bg-white/70 text-slate-600 text-xs font-extrabold px-3 py-1 rounded-full">🔒 <?= e($p['status']) ?></span>
                        </div>
                        <div class="mt-auto pt-5">
                            <button type="button" class="btn-terkunci w-full bg-gray-200 text-gray-500 font-extrabold text-sm py-2.5 rounded-full cursor-not-allowed">Buka Level <?= (int) $p['level'] ?> 🔒</button>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
    </main>

    <!-- Widget Arazka -->
    <aside id="arazka-widget" class="fixed bottom-4 right-4 z-40 flex items-end gap-2">
        <div id="arazka-bubble" class="hidden relative max-w-[14rem] sm:max-w-xs bg-white border-2 border-violet-300 rounded-2xl px-3 py-2 shadow-lg" style="display:none;">
            <p class="text-xs font-extrabold text-violet-600">Arazka</p>
            <p id="arazka-teks" class="text-sm font-bold leading-snug"></p>
            <span class="absolute -right-1.5 bottom-5 w-3 h-3 rotate-45 bg-white border-t-2 border-r-2 border-violet-300"></span>
        </div>
        <button type="button" id="arazka-btn" aria-expanded="false"
                class="arazka-wrap w-16 h-16 sm:w-20 sm:h-20 shrink-0 p-1 rounded-full bg-cosmic-card border-4 border-cosmic-accent shadow-lg hover:scale-110 active:scale-95 transition-transform"
                data-topi="<?= e($topiUser ?? '') ?>"
                data-kacamata="<?= e($kacamataUser ?? '') ?>">
            <?= $arazkaSvg ?>
        </button>
    </aside>

    <?php include __DIR__ . '/components/modal-shop.php'; ?>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" role="status" aria-live="polite"></div>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 pt-4 pb-24 md:pb-4 text-center text-xs sm:text-sm font-bold">
            Optimized for Low-Spec School Hardware | BRIGHT SPACE EdTech
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
            planetId: null,
            kuis: [],
            latihan: [],
            gameModes: <?= json_encode($gameModes, JSON_UNESCAPED_UNICODE) ?>
        };
    </script>
    <script src="assets/js/app.js" defer></script>
</body>
</html>