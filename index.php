<?php
// ============================================================
// index.php — Dashboard BRIGHT SPACE (tanpa database)
// ============================================================
define('BRIGHT_SPACE', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/config/database.php';
require_login();

$appName = 'BRIGHT SPACE';
$user    = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

require __DIR__ . '/config/planets.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($appName) ?> - Petualangan Belajar</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' }
                    },
                    fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
                }
            }
        };
    </script>
    <style>
        @keyframes planet-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50%      { transform: translateY(-4px) rotate(4deg); }
        }
        .planet-card:hover .planet-icon { animation: planet-float 1.4s ease-in-out infinite; }
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-40 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
        <nav class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
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
                <span class="text-lg font-black">BRIGHT <span class="text-violet-600">SPACE</span></span>
            </a>

            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-2.5 py-1 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                    </svg>
                    <span class="font-extrabold text-base leading-none"><?= (int) $user['star_coins'] ?></span>
                </div>

                <a href="logout.php" class="bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-sm px-3 py-2 rounded-full shadow">
                    Keluar
                </a>
            </div>
        </nav>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-grow">

        <section class="mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-black">
                Selamat Datang, <?= e($user['nama']) ?>! 👋
            </h1>
            <p class="mt-2 text-base sm:text-lg font-bold opacity-80">
                Pilih planet petualanganmu hari ini! 🚀
            </p>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($planets as $p): $terbuka = ($p['status'] === 'Terbuka'); ?>
                <?php if ($terbuka): ?>
                    <a href="kuis.php?planet=<?= e($p['id']) ?>"
                       class="planet-card group relative flex flex-col bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <div class="planet-icon w-24 h-24 mx-auto mt-6 mb-3"><?= $p['ikon'] ?></div>
                        <h2 class="text-xl font-black text-center leading-tight"><?= e($p['nama']) ?></h2>
                        <p class="mt-1 text-sm font-bold text-center opacity-80"><?= e($p['deskripsi']) ?></p>
                        <div class="mt-3 flex justify-center">
                            <span class="inline-flex items-center gap-1 bg-white/70 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full">
                                ✅ <?= e($p['status']) ?>
                            </span>
                        </div>
                        <div class="mt-auto pt-5">
                            <span class="block w-full text-center text-white font-extrabold text-sm py-2.5 rounded-full shadow transition-transform duration-200 group-hover:scale-105 <?= e($p['tombol']) ?>">
                                Jelajahi Planet 🚀
                            </span>
                        </div>
                    </a>
                <?php else: ?>
                    <article class="planet-card opacity-90 relative flex flex-col bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md">
                        <div class="planet-icon w-24 h-24 mx-auto mt-6 mb-3"><?= $p['ikon'] ?></div>
                        <h2 class="text-xl font-black text-center leading-tight"><?= e($p['nama']) ?></h2>
                        <p class="mt-1 text-sm font-bold text-center opacity-80"><?= e($p['deskripsi']) ?></p>
                        <div class="mt-3 flex justify-center">
                            <span class="inline-flex items-center gap-1 bg-white/70 text-slate-600 text-xs font-extrabold px-3 py-1 rounded-full">
                                🔒 <?= e($p['status']) ?>
                            </span>
                        </div>
                        <div class="mt-auto pt-5">
                            <button type="button" class="w-full bg-gray-200 text-gray-500 font-extrabold text-sm py-2.5 rounded-full cursor-not-allowed">
                                Buka Level <?= (int) $p['level'] ?> 🔒
                            </button>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
    </main>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 py-4 text-center text-xs font-bold opacity-70">
            BRIGHT SPACE EdTech · Optimized for Low-Spec School Hardware
        </div>
    </footer>
</body>
</html>