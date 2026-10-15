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

// Ambil top users dari DB
$topUsers = [];
try {
    $topUsers = db_all('SELECT id, nama, star_coins FROM users ORDER BY star_coins DESC LIMIT 10');
} catch (Exception $e) { $topUsers = []; }

// Fallback dummy kalau DB kosong
if (count($topUsers) < 3) {
    $topUsers = [
        ['id' => 1,   'nama' => 'Budi Astronot', 'star_coins' => 520, 'sekolah' => 'SDN 1 Jakarta'],
        ['id' => $user_id, 'nama' => $user_name, 'star_coins' => max(320, $star_coins), 'sekolah' => 'SDN 2 Bandung'],
        ['id' => 3,   'nama' => 'Siti Star',     'star_coins' => 290, 'sekolah' => 'SDN 3 Surabaya'],
        ['id' => 4,   'nama' => 'Dedi Rocket',   'star_coins' => 245, 'sekolah' => 'SDN 4 Medan'],
        ['id' => 5,   'nama' => 'Rina Comet',    'star_coins' => 210, 'sekolah' => 'SDN 5 Semarang'],
        ['id' => 6,   'nama' => 'Andi Nebula',   'star_coins' => 185, 'sekolah' => 'SDN 6 Makassar'],
        ['id' => 7,   'nama' => 'Lina Galaxy',   'star_coins' => 165, 'sekolah' => 'SDN 7 Palembang'],
        ['id' => 8,   'nama' => 'Fajar Meteor',  'star_coins' => 140, 'sekolah' => 'SDN 8 Denpasar'],
        ['id' => 9,   'nama' => 'Maya Asteroid', 'star_coins' => 125, 'sekolah' => 'SDN 9 Balikpapan'],
        ['id' => 10,  'nama' => 'Rizki Cosmos',  'star_coins' => 110, 'sekolah' => 'SDN 10 Pontianak'],
    ];
}

// Pastikan user sendiri ada di list (minimal rank 2)
$adaUser = false;
foreach ($topUsers as $u) if ((int)$u['id'] === $user_id) { $adaUser = true; break; }
if (!$adaUser) {
    $topUsers[1] = ['id' => $user_id, 'nama' => $user_name, 'star_coins' => $star_coins, 'sekolah' => 'Sekolahmu'];
}

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }
function inisial($nama) { return strtoupper(substr(trim($nama), 0, 1)); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Skor - <?= e($appName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' } },
            fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
        } } };
    </script>
    <style>
        @keyframes crown-bounce { 0%,100%{transform:translateY(0) rotate(-3deg);}50%{transform:translateY(-6px) rotate(3deg);} }
        @keyframes frame-glow { 0%,100%{box-shadow:0 0 0 0 rgba(251,191,36,0.5);}50%{box-shadow:0 0 0 12px rgba(251,191,36,0);} }
        .crown-anim { animation: crown-bounce 2s ease-in-out infinite; }
        .podium-1 { animation: frame-glow 2s ease-in-out infinite; }
    </style>
</head>
<body class="font-nunito text-slate-800 min-h-screen antialiased" style="background-color:#F0F7FF;">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <?php include __DIR__ . '/components/header.php'; ?>

        <main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1">

            <!-- Header -->
            <section class="mb-6 text-center">
                <h1 class="text-3xl sm:text-4xl font-black">🏆 Top Kapten Minggu Ini</h1>
                <p class="mt-2 text-base sm:text-lg font-bold opacity-80">
                    Lihat siapa saja penjelajah terbaik di galaksi!
                </p>
            </section>

            <!-- PODIUM TOP 3 -->
            <section class="mb-8">
                <div class="grid grid-cols-3 gap-2 sm:gap-4 items-end max-w-3xl mx-auto">

                    <!-- JUARA 2 (kiri) -->
                    <?php if (isset($topUsers[1])): $u = $topUsers[1]; ?>
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-slate-200 to-slate-400 border-4 border-slate-300 flex items-center justify-center text-2xl sm:text-3xl font-black text-white shadow-lg">
                                    <?= inisial($u['nama']) ?>
                                </div>
                                <span class="absolute -top-3 -right-2 text-2xl sm:text-3xl">🥈</span>
                            </div>
                            <p class="mt-2 text-xs sm:text-sm font-black truncate w-full text-center"><?= e($u['nama']) ?></p>
                            <p class="text-[10px] sm:text-xs font-bold text-slate-500 truncate w-full text-center"><?= e($u['sekolah'] ?? '-') ?></p>
                            <p class="mt-1 text-xs sm:text-sm font-black text-amber-600"><?= (int) $u['star_coins'] ?> ⭐</p>
                            <div class="mt-2 w-full bg-slate-300 rounded-t-2xl h-20 sm:h-24 flex items-start justify-center pt-2">
                                <span class="text-2xl font-black text-slate-700">2</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- JUARA 1 (tengah) -->
                    <?php if (isset($topUsers[0])): $u = $topUsers[0]; ?>
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-4xl sm:text-5xl crown-anim z-10">👑</span>
                                <div class="podium-1 w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-amber-200 to-amber-400 border-4 border-amber-400 flex items-center justify-center text-3xl sm:text-4xl font-black text-white shadow-2xl">
                                    <?= inisial($u['nama']) ?>
                                </div>
                                <span class="absolute -bottom-1 -right-2 text-2xl sm:text-3xl">🥇</span>
                            </div>
                            <p class="mt-2 text-xs sm:text-sm font-black truncate w-full text-center"><?= e($u['nama']) ?></p>
                            <p class="text-[10px] sm:text-xs font-bold text-slate-500 truncate w-full text-center"><?= e($u['sekolah'] ?? '-') ?></p>
                            <p class="mt-1 text-sm sm:text-base font-black text-amber-600"><?= (int) $u['star_coins'] ?> ⭐</p>
                            <div class="mt-2 w-full bg-amber-400 rounded-t-2xl h-28 sm:h-32 flex items-start justify-center pt-2">
                                <span class="text-3xl font-black text-white">1</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- JUARA 3 (kanan) -->
                    <?php if (isset($topUsers[2])): $u = $topUsers[2]; ?>
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-orange-200 to-amber-600 border-4 border-amber-500 flex items-center justify-center text-2xl sm:text-3xl font-black text-white shadow-lg">
                                    <?= inisial($u['nama']) ?>
                                </div>
                                <span class="absolute -top-3 -right-2 text-2xl sm:text-3xl">🥉</span>
                            </div>
                            <p class="mt-2 text-xs sm:text-sm font-black truncate w-full text-center"><?= e($u['nama']) ?></p>
                            <p class="text-[10px] sm:text-xs font-bold text-slate-500 truncate w-full text-center"><?= e($u['sekolah'] ?? '-') ?></p>
                            <p class="mt-1 text-xs sm:text-sm font-black text-amber-600"><?= (int) $u['star_coins'] ?> ⭐</p>
                            <div class="mt-2 w-full bg-amber-600 rounded-t-2xl h-16 sm:h-20 flex items-start justify-center pt-2">
                                <span class="text-2xl font-black text-white">3</span>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </section>

            <!-- TABEL PERINGKAT 4-10 -->
            <section>
                <h2 class="text-lg sm:text-xl font-black mb-3">📋 Peringkat Lainnya</h2>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <ul class="divide-y divide-slate-100">
                        <?php for ($i = 3; $i < count($topUsers); $i++): $u = $topUsers[$i]; $rank = $i + 1; $isMe = ((int)$u['id'] === $user_id); ?>
                            <li class="flex items-center justify-between gap-3 px-4 py-3 <?= $isMe ? 'bg-amber-50' : 'hover:bg-slate-50' ?>">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-8 text-center text-sm font-black text-slate-500">#<?= $rank ?></span>
                                    <div class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-violet-300 to-indigo-400 flex items-center justify-center text-white font-black text-sm shadow">
                                        <?= inisial($u['nama']) ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold truncate">
                                            <?= e($u['nama']) ?>
                                            <?php if ($isMe): ?>
                                                <span class="ml-1 text-xs bg-amber-300 text-amber-900 font-black px-2 py-0.5 rounded-full">Kamu</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="text-xs font-bold text-slate-500 truncate"><?= e($u['sekolah'] ?? '-') ?></p>
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-base font-black text-amber-600"><?= (int) $u['star_coins'] ?> ⭐</p>
                                </div>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </section>

        </main>

        <footer class="bg-white border-t-2 border-slate-100 mt-8">
            <div class="max-w-6xl mx-auto px-4 py-4 text-center text-xs font-bold text-slate-400">
                BRIGHT SPACE EdTech
            </div>
        </footer>
    </div>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" style="display:none;"></div>

    <script>
        window.BRIGHT_SPACE = {
            user: {
                id: <?= (int) $user_id ?>,
                nama: <?= json_encode($user_name, JSON_UNESCAPED_UNICODE) ?>,
                star_coins: <?= (int) $star_coins ?>
            }
        };
    </script>
    <script src="assets/js/app.js" defer></script>
</body>
</html>