<?php
// ============================================================
// leaderboard.php — Papan Skor (dummy data)
// ============================================================
define('BRIGHT_SPACE', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/config/database.php';
require_login();

$user = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

$leaderboard = [
    ['nama' => 'Budi Santoso',   'kelas' => '5B', 'bintang' => 520, 'avatar' => '🧑‍🚀'],
    ['nama' => $user['nama'],    'kelas' => '4A', 'bintang' => 320, 'avatar' => '🚀', 'is_me' => true],
    ['nama' => 'Siti Nurhaliza', 'kelas' => '5A', 'bintang' => 480, 'avatar' => '👩‍🚀'],
    ['nama' => 'Dedi Kurniawan', 'kelas' => '4B', 'bintang' => 445, 'avatar' => '🧑‍🔬'],
    ['nama' => 'Rina Amelia',    'kelas' => '3A', 'bintang' => 410, 'avatar' => '👧'],
    ['nama' => 'Andi Pratama',   'kelas' => '5C', 'bintang' => 385, 'avatar' => '👦'],
    ['nama' => 'Lina Marlina',   'kelas' => '4C', 'bintang' => 350, 'avatar' => '👩'],
    ['nama' => 'Fajar Ramadhan', 'kelas' => '6A', 'bintang' => 320, 'avatar' => '🧑'],
    ['nama' => 'Maya Sari',      'kelas' => '3B', 'bintang' => 295, 'avatar' => '👧'],
    ['nama' => 'Rizki Hidayat',  'kelas' => '6B', 'bintang' => 275, 'avatar' => '👦'],
];

usort($leaderboard, function ($a, $b) { return $b['bintang'] - $a['bintang']; });

$top3 = array_slice($leaderboard, 0, 3);
$rest = array_slice($leaderboard, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Skor - BRIGHT SPACE</title>
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
        @keyframes crown-bounce { 0%,100%{transform:translateY(0) rotate(-3deg);}50%{transform:translateY(-6px) rotate(3deg);} }
        .crown-anim { animation: crown-bounce 2s ease-in-out infinite; }
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-40 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
        <nav class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
            <a href="index.php" class="text-lg font-black">BRIGHT <span class="text-violet-600">SPACE</span></a>
            <a href="logout.php" class="bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-sm px-3 py-2 rounded-full shadow">Keluar</a>
        </nav>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6 w-full flex-1">

        <section class="mb-6 text-center">
            <span class="inline-block bg-violet-100 border-2 border-violet-200 text-violet-700 text-xs font-extrabold uppercase tracking-wider px-3 py-1.5 rounded-full">🏫 SDN BRIGHT SPACE</span>
            <h1 class="mt-3 text-3xl sm:text-4xl font-black">Papan Skor Sekolah 🏆</h1>
            <p class="mt-2 text-base font-bold opacity-80">Top Kapten dengan Bintang Terbanyak Minggu Ini!</p>
        </section>

        <section class="mb-8">
            <div class="grid grid-cols-3 gap-2 sm:gap-4 items-end max-w-3xl mx-auto">
                <?php if (isset($top3[1])): $u = $top3[1]; ?>
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-slate-200 to-slate-400 border-4 border-slate-300 flex items-center justify-center text-3xl shadow-lg"><?= $u['avatar'] ?></div>
                            <span class="absolute -top-3 -right-2 text-2xl">🥈</span>
                        </div>
                        <p class="mt-2 text-xs font-black truncate w-full text-center"><?= e($u['nama']) ?></p>
                        <span class="mt-0.5 inline-block bg-violet-100 text-violet-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">Kelas <?= e($u['kelas']) ?></span>
                        <p class="mt-1 text-xs font-black text-amber-600"><?= (int) $u['bintang'] ?> ⭐</p>
                        <div class="mt-2 w-full bg-slate-300 rounded-t-2xl h-20 flex items-start justify-center pt-2"><span class="text-2xl font-black text-slate-700">2</span></div>
                    </div>
                <?php endif; ?>

                <?php if (isset($top3[0])): $u = $top3[0]; ?>
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-4xl crown-anim z-10">👑</span>
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-amber-200 to-amber-400 border-4 border-amber-400 flex items-center justify-center text-4xl shadow-2xl"><?= $u['avatar'] ?></div>
                            <span class="absolute -bottom-1 -right-2 text-2xl">🥇</span>
                        </div>
                        <p class="mt-2 text-xs font-black truncate w-full text-center"><?= e($u['nama']) ?></p>
                        <span class="mt-0.5 inline-block bg-amber-100 text-amber-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">Kelas <?= e($u['kelas']) ?></span>
                        <p class="mt-1 text-sm font-black text-amber-600"><?= (int) $u['bintang'] ?> ⭐</p>
                        <div class="mt-2 w-full bg-amber-400 rounded-t-2xl h-28 flex items-start justify-center pt-2"><span class="text-3xl font-black text-white">1</span></div>
                    </div>
                <?php endif; ?>

                <?php if (isset($top3[2])): $u = $top3[2]; ?>
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-orange-200 to-amber-600 border-4 border-amber-500 flex items-center justify-center text-3xl shadow-lg"><?= $u['avatar'] ?></div>
                            <span class="absolute -top-3 -right-2 text-2xl">🥉</span>
                        </div>
                        <p class="mt-2 text-xs font-black truncate w-full text-center"><?= e($u['nama']) ?></p>
                        <span class="mt-0.5 inline-block bg-orange-100 text-orange-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">Kelas <?= e($u['kelas']) ?></span>
                        <p class="mt-1 text-xs font-black text-amber-600"><?= (int) $u['bintang'] ?> ⭐</p>
                        <div class="mt-2 w-full bg-amber-600 rounded-t-2xl h-16 flex items-start justify-center pt-2"><span class="text-2xl font-black text-white">3</span></div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <h2 class="text-lg font-black mb-3">📋 Peringkat Lainnya</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <ul class="divide-y divide-slate-100">
                    <?php foreach ($rest as $i => $u): $rank = $i + 4; $isMe = !empty($u['is_me']); ?>
                        <li class="flex items-center justify-between gap-3 px-4 py-3 <?= $isMe ? 'bg-amber-50' : 'hover:bg-slate-50' ?>">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-8 text-center text-sm font-black text-slate-500">#<?= $rank ?></span>
                                <div class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-violet-300 to-indigo-400 flex items-center justify-center text-xl shadow"><?= $u['avatar'] ?></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-extrabold truncate"><?= e($u['nama']) ?><?php if ($isMe): ?> <span class="ml-1 text-[10px] bg-amber-300 text-amber-900 font-black px-2 py-0.5 rounded-full">Kamu</span><?php endif; ?></p>
                                    <p class="text-xs font-bold text-slate-500">Kelas <?= e($u['kelas']) ?></p>
                                </div>
                            </div>
                            <div class="shrink-0 text-right"><p class="text-base font-black text-amber-600"><?= (int) $u['bintang'] ?> ⭐</p></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-5xl mx-auto px-4 py-4 text-center text-xs font-bold opacity-70">BRIGHT SPACE EdTech</div>
    </footer>
</body>
</html>