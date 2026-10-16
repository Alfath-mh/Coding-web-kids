<?php
// ============================================================
// misi.php — Peta Misi (tanpa database)
// ============================================================
define('BRIGHT_SPACE', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/config/database.php';
require_login();

$user = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

require __DIR__ . '/config/planets.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Misi - BRIGHT SPACE</title>
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
                <span class="text-lg font-black">BRIGHT <span class="text-violet-600">SPACE</span></span>
            </a>
            <a href="logout.php" class="bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-sm px-3 py-2 rounded-full shadow">
                Keluar
            </a>
        </nav>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-grow">
        <section class="mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-black">Peta Misi 🗺️</h1>
            <p class="mt-2 text-base sm:text-lg font-bold opacity-80">
                Pilih planet untuk memulai petualanganmu, Kapten <?= e($user['nama']) ?>!
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
            BRIGHT SPACE EdTech
        </div>
    </footer>
</body>
</html>