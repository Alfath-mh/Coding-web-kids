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
$user_level = (int) ($user['level']      ?? 1);

require __DIR__ . '/config/planets.php';

// Ambil item yang dimiliki user
$itemDimiliki = [];
try {
    $rows = db_all('SELECT item_id FROM user_items WHERE user_id = ?', [$user_id]);
    foreach ($rows as $r) $itemDimiliki[$r['item_id']] = true;
} catch (Exception $e) {}

$topiAktif     = $user['avatar_topi'] ?? null;
$kacamataAktif = $user['avatar_kacamata'] ?? null;

// Filter item yang dimiliki
$koleksiSaya = [];
foreach ($items as $item) {
    if (isset($itemDimiliki[$item['id']])) $koleksiSaya[] = $item;
}

// Data dummy statistik
$total_misi   = 12;
$misi_selesai = 8;
$streak       = 3;

$arazkaSvg = file_get_contents(__DIR__ . '/components/arazka-svg.php');

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kapten - <?= e($appName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' } },
            fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
        } } };
    </script>
    <style>
        @keyframes float-frame { 0%,100%{transform:translateY(0);}50%{transform:translateY(-6px);} }
        @keyframes glow-ring { 0%,100%{box-shadow:0 0 0 0 rgba(139,92,246,0.4);}50%{box-shadow:0 0 0 16px rgba(139,92,246,0);} }
        .profile-float { animation: float-frame 3s ease-in-out infinite; }
        .profile-glow  { animation: glow-ring 2.5s ease-in-out infinite; }
    </style>
</head>
<body class="font-nunito text-slate-800 min-h-screen antialiased" style="background-color:#F0F7FF;">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <?php include __DIR__ . '/components/header.php'; ?>

        <main class="max-w-5xl mx-auto px-4 py-6 w-full flex-1">

            <!-- ============ CARD PROFIL UTAMA ============ -->
            <section class="mb-6">
                <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 via-indigo-500 to-purple-600 rounded-3xl shadow-xl p-6 sm:p-8 text-white">

                    <!-- Ornamen bintang -->
                    <div class="absolute top-4 right-4 text-5xl opacity-20 select-none">✨</div>
                    <div class="absolute bottom-4 left-4 text-4xl opacity-15 select-none">🌌</div>

                    <div class="relative flex flex-col sm:flex-row items-center gap-6">

                        <!-- Avatar besar + frame -->
                        <div class="profile-float shrink-0">
                            <div class="profile-glow relative w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-gradient-to-br from-amber-200 to-amber-400 border-4 border-amber-300 flex items-center justify-center shadow-2xl overflow-hidden">

                                <!-- Avatar inisial nama -->
                                <span class="text-6xl sm:text-7xl font-black text-white drop-shadow-lg">
                                    <?= strtoupper(substr($user_name, 0, 1)) ?>
                                </span>

                                <!-- Layer topi (kalau ada) -->
                                <?php if ($topiAktif): ?>
                                    <div class="absolute top-2 left-1/2 -translate-x-1/2 text-4xl sm:text-5xl pointer-events-none">🎩</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Info user -->
                        <div class="text-center sm:text-left flex-1">
                            <p class="inline-block bg-white/20 text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full">
                                👑 Kapten Galaksi
                            </p>
                            <h1 class="mt-2 text-3xl sm:text-4xl font-black leading-tight">
                                <?= e($user_name) ?>
                            </h1>
                            <p class="mt-1 text-sm sm:text-base font-bold opacity-90">
                                Penjelajah Galaksi Level <?= $user_level ?>
                            </p>

                            <!-- Stat kecil -->
                            <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-3">
                                <div class="inline-flex items-center gap-2 bg-amber-400 border-2 border-amber-300 rounded-2xl px-3 py-1.5 shadow">
                                    <span class="text-xl">⭐</span>
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Bintang</p>
                                        <p class="text-lg font-black text-amber-900 leading-none"><?= $star_coins ?></p>
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-2 bg-white/20 border-2 border-white/30 rounded-2xl px-3 py-1.5 shadow">
                                    <span class="text-xl">🏆</span>
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase text-white/80 leading-none">Misi</p>
                                        <p class="text-lg font-black leading-none"><?= $misi_selesai ?>/<?= $total_misi ?></p>
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-2 bg-white/20 border-2 border-white/30 rounded-2xl px-3 py-1.5 shadow">
                                    <span class="text-xl">🔥</span>
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase text-white/80 leading-none">Streak</p>
                                        <p class="text-lg font-black leading-none"><?= $streak ?> Hari</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ============ BADGE PENCAPAIAN ============ -->
            <section class="mb-6">
                <h2 class="text-lg sm:text-xl font-black mb-3">🏅 Lencana Pencapaian</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                    <?php
                    $badges = [
                        ['icon' => '🎯', 'nama' => 'Kuis Pertama',   'deskripsi' => 'Selesaikan 1 kuis', 'aktif' => true,  'warna' => 'from-amber-100 to-amber-200 border-amber-300'],
                        ['icon' => '⚡', 'nama' => '3 Days Streak',  'deskripsi' => 'Belajar 3 hari',    'aktif' => true,  'warna' => 'from-rose-100 to-rose-200 border-rose-300'],
                        ['icon' => '🌌', 'nama' => 'Master Matematika', 'deskripsi' => '100% di Matematika', 'aktif' => true, 'warna' => 'from-violet-100 to-violet-200 border-violet-300'],
                        ['icon' => '🔒', 'nama' => 'Kolektor Sejati', 'deskripsi' => 'Beli 5 item', 'aktif' => false, 'warna' => 'from-slate-100 to-slate-200 border-slate-300'],
                    ];
                    foreach ($badges as $b):
                    ?>
                        <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br <?= $b['warna'] ?> <?= !$b['aktif'] ? 'opacity-50' : 'hover:scale-105 transition-transform' ?>">
                            <span class="text-4xl mb-1"><?= $b['icon'] ?></span>
                            <p class="text-xs font-black leading-tight"><?= $b['nama'] ?></p>
                            <p class="text-[10px] font-bold opacity-70 mt-0.5"><?= $b['deskripsi'] ?></p>
                        </div>
                    <?php endforeach; ?>

                </div>
            </section>

            <!-- ============ KOLEKSI SAYA ============ -->
            <section>
                <h2 class="text-lg sm:text-xl font-black mb-3">🎒 Koleksi Saya</h2>

                <?php if (empty($koleksiSaya)): ?>
                    <div class="bg-white rounded-2xl p-8 text-center border-2 border-dashed border-violet-200">
                        <p class="text-4xl mb-2">🎒</p>
                        <p class="text-sm font-extrabold text-slate-600">Koleksimu masih kosong</p>
                        <p class="text-xs font-bold text-slate-400 mt-1">Beli item di Toko Bintang untuk mengisi koleksimu!</p>
                        <a href="shop.php" class="inline-block mt-4 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm px-6 py-2.5 rounded-full shadow transition-transform hover:scale-105">
                            Ke Toko Bintang 🛍️
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        <?php foreach ($koleksiSaya as $item):
                            $sedangDipakai = ($topiAktif === $item['id']) || ($kacamataAktif === $item['id']);
                        ?>
                            <article class="flex flex-col bg-white border-2 <?= $sedangDipakai ? 'border-emerald-400 bg-emerald-50' : 'border-violet-200' ?> rounded-2xl p-3 shadow-sm"
                                     data-item-id="<?= e($item['id']) ?>">

                                <div class="w-16 h-16 mx-auto mb-2"><?= $item['ikon'] ?></div>
                                <h3 class="text-xs font-black leading-tight text-center"><?= e($item['nama']) ?></h3>

                                <?php if ($sedangDipakai): ?>
                                    <span class="mt-2 w-full text-center text-xs font-extrabold bg-emerald-400 text-white py-1.5 rounded-full">
                                        ✓ Dipakai
                                    </span>
                                <?php else: ?>
                                    <button type="button" class="btn-pakai-profile w-full mt-2 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-xs py-1.5 rounded-full transition-transform hover:scale-105"
                                            data-item-id="<?= e($item['id']) ?>">
                                        Gunakan 👕
                                    </button>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

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
                star_coins: <?= (int) $star_coins ?>,
                topi: <?= json_encode($topiAktif, JSON_UNESCAPED_UNICODE) ?>,
                kacamata: <?= json_encode($kacamataAktif, JSON_UNESCAPED_UNICODE) ?>
            },
            items: <?= json_encode($items ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        };
    </script>
    <script src="assets/js/app.js" defer></script>
</body>
</html>