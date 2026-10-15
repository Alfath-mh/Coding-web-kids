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

// Ambil item yang sudah dimiliki user dari DB
$itemDimiliki = [];
try {
    $rows = db_all('SELECT item_id FROM user_items WHERE user_id = ?', [$user_id]);
    foreach ($rows as $r) $itemDimiliki[$r['item_id']] = true;
} catch (Exception $e) {}

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Bintang - <?= e($appName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cosmic: { bg: '#E0F2FE', card: '#EDE9FE', accent: '#FEF3C7', text: '#1E1B4B' } },
            fontFamily: { nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif'] }
        } } };
    </script>
</head>
<body class="font-nunito text-slate-800 min-h-screen antialiased" style="background-color:#F0F7FF;">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <?php include __DIR__ . '/components/header.php'; ?>

        <main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1">

            <!-- HERO BANNER TOKO -->
            <section class="mb-6">
                <div class="relative overflow-hidden bg-gradient-to-r from-violet-500 to-indigo-500 rounded-3xl shadow-xl p-6 sm:p-8 text-white">
                    <div class="absolute top-4 right-4 text-6xl opacity-20 select-none">🛍️</div>
                    <div class="absolute bottom-2 right-24 text-3xl opacity-15 select-none">✨</div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <span class="inline-block bg-white/20 text-xs font-extrabold uppercase tracking-wider px-3 py-1.5 rounded-full">
                                🛍️ Space Shop
                            </span>
                            <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">
                                Toko Bintang
                            </h1>
                            <p class="mt-1 text-sm font-bold opacity-90">
                                Tukar Koin Bintangmu dengan item keren!
                            </p>
                        </div>

                        <div class="shrink-0 inline-flex items-center gap-2 bg-amber-400 border-2 border-amber-300 rounded-2xl pl-2 pr-4 py-2 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7">
                                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/>
                            </svg>
                            <div>
                                <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Koin Kamu</p>
                                <p class="text-xl font-black text-amber-900 leading-none" id="shop-saldo"><?= (int) $star_coins ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- GRID ITEM SHOP -->
            <section>
                <h2 class="text-lg sm:text-xl font-black mb-3">🛒 Item Tersedia</h2>

                <div id="shop-full-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($items as $item): $sudahDimiliki = isset($itemDimiliki[$item['id']]); ?>
                        <article class="item-toko flex flex-col bg-white border-2 border-violet-200 rounded-3xl p-5 shadow-sm hover:shadow-lg transition-all"
                                 data-item-id="<?= e($item['id']) ?>"
                                 data-harga="<?= (int) $item['harga'] ?>"
                                 data-nama="<?= e($item['nama']) ?>">

                            <!-- Ikon SVG/Emoji -->
                            <div class="w-24 h-24 mx-auto mb-3"><?= $item['ikon'] ?></div>

                            <!-- Nama & Deskripsi -->
                            <h3 class="text-base font-black leading-tight text-center"><?= e($item['nama']) ?></h3>
                            <p class="mt-1 text-xs font-bold opacity-70 text-center flex-1"><?= e($item['deskripsi']) ?></p>

                            <!-- Harga -->
                            <div class="mt-3 inline-flex items-center justify-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full px-3 py-1 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4">
                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/>
                                </svg>
                                <span class="text-sm font-extrabold"><?= (int) $item['harga'] ?> Koin</span>
                            </div>

                            <!-- Tombol Beli / Dimiliki -->
                            <?php if ($sudahDimiliki): ?>
                                <div class="mt-3 w-full text-center bg-emerald-100 text-emerald-700 font-extrabold text-sm py-2.5 rounded-full">
                                    ✓ Dimiliki
                                </div>
                                <?php if (($item['slot'] ?? '') !== null && ($item['slot'] ?? '') !== ''): ?>
                                    <button type="button"
                                            class="btn-pakai w-full mt-2 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2 rounded-full transition-transform hover:scale-105"
                                            data-item-id="<?= e($item['id']) ?>">
                                        Gunakan 👕
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button type="button"
                                        class="btn-beli w-full mt-3 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2.5 rounded-full shadow transition-transform hover:scale-105 active:scale-95">
                                    Beli 🛒
                                </button>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

        </main>

        <footer class="bg-white border-t-2 border-slate-100 mt-8">
            <div class="max-w-6xl mx-auto px-4 py-4 text-center text-xs font-bold text-slate-400">
                BRIGHT SPACE EdTech · Optimized for Low-Spec School Hardware
            </div>
        </footer>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" style="display:none;"></div>

    <!-- DATA untuk JS -->
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
        window.BRIGHT_SPACE_ITEMS = <?= json_encode($items ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="assets/js/app.js" defer></script>
</body>
</html>