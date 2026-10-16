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

// Ambil item yang dimiliki user dari DB
$itemDimiliki = [];
try {
    $rows = db_all('SELECT item_id FROM user_items WHERE user_id = ?', [$user_id]);
    foreach ($rows as $r) $itemDimiliki[$r['item_id']] = true;
} catch (Exception $e) {}

$topiAktif     = $user['avatar_topi'] ?? null;
$kacamataAktif = $user['avatar_kacamata'] ?? null;

// Filter koleksi
$koleksiSaya = [];
foreach ($items as $item) {
    if (isset($itemDimiliki[$item['id']])) $koleksiSaya[] = $item;
}

$totalItem = count($koleksiSaya);

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

// Helper: ambil emoji/ikon item by id
function getIkonItem($items, $id) {
    foreach ($items as $it) {
        if ($it['id'] === $id) return $it['ikon'];
    }
    return '';
}
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
        @keyframes sparkle { 0%,100%{opacity:0.3;transform:scale(0.8);}50%{opacity:1;transform:scale(1.2);} }
        @keyframes bob { 0%,100%{transform:translateY(0);}50%{transform:translateY(-3px);} }
        @keyframes bounce-in { 0%{transform:scale(0.5);opacity:0;}60%{transform:scale(1.1);}100%{transform:scale(1);opacity:1;} }
        @keyframes pop { 0%{transform:scale(1);}50%{transform:scale(1.1);}100%{transform:scale(1);} }
        .profile-float { animation: float-frame 3s ease-in-out infinite; }
        .profile-glow  { animation: glow-ring 2.5s ease-in-out infinite; }
        .sparkle { animation: sparkle 1.5s ease-in-out infinite; }
        .astronot-bob { animation: bob 2.5s ease-in-out infinite; }
        .bounce-in { animation: bounce-in 0.5s ease-out; }
        .pop { animation: pop 0.4s ease-out; }
    </style>
</head>
<body class="font-nunito text-slate-800 min-h-screen antialiased" style="background-color:#F0F7FF;">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <?php include __DIR__ . '/components/header.php'; ?>

        <main class="max-w-5xl mx-auto px-4 py-6 w-full flex-1">

            <!-- KARTU PROFIL + KARAKTER -->
            <section class="mb-6">
                <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 via-indigo-500 to-purple-600 rounded-3xl shadow-xl p-6 sm:p-8 text-white">

                    <div class="absolute top-4 right-4 text-5xl opacity-20 select-none">✨</div>
                    <div class="absolute bottom-4 left-4 text-4xl opacity-15 select-none">🌌</div>
                    <div class="absolute top-1/3 left-1/4 text-2xl sparkle select-none">⭐</div>
                    <div class="absolute bottom-1/4 right-1/3 text-xl sparkle select-none" style="animation-delay: 0.5s;">✨</div>

                    <div class="relative flex flex-col sm:flex-row items-center gap-6">

                        <!-- KARAKTER UTAMA -->
                        <div class="profile-float shrink-0">
                            <div class="profile-glow relative w-44 h-44 sm:w-52 sm:h-52 rounded-full bg-gradient-to-br from-amber-200 via-yellow-300 to-amber-400 border-4 border-amber-300 flex items-center justify-center shadow-2xl">

                                <!-- KARAKTER ASTRONOT SVG -->
                                <div class="astronot-bob relative w-36 h-36 sm:w-44 sm:h-44">
                                    <svg viewBox="0 0 200 200" class="w-full h-full" aria-hidden="true">

                                        <!-- Bayangan -->
                                        <ellipse cx="100" cy="185" rx="50" ry="8" fill="#000" opacity="0.15"/>

                                        <!-- Badan -->
                                        <rect x="70" y="130" width="60" height="45" rx="20" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                        <rect x="85" y="140" width="30" height="20" rx="4" fill="#C4B5FD" stroke="#1E1B4B" stroke-width="2"/>
                                        <circle cx="93" cy="148" r="2" fill="#FCD34D"/>
                                        <circle cx="100" cy="148" r="2" fill="#34D399"/>
                                        <circle cx="107" cy="148" r="2" fill="#F87171"/>
                                        <rect x="88" y="154" width="24" height="3" rx="1" fill="#7DD3FC"/>

                                        <!-- Lengan -->
                                        <ellipse cx="62" cy="145" rx="14" ry="20" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                        <ellipse cx="138" cy="145" rx="14" ry="20" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>

                                        <!-- Kaki -->
                                        <rect x="78" y="172" width="18" height="12" rx="6" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                        <rect x="104" y="172" width="18" height="12" rx="6" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>

                                        <!-- Helm -->
                                        <circle cx="100" cy="85" r="52" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                        <line x1="100" y1="30" x2="100" y2="15" stroke="#1E1B4B" stroke-width="3" stroke-linecap="round"/>
                                        <circle cx="100" cy="10" r="6" fill="#FCD34D" stroke="#B45309" stroke-width="2"/>

                                        <!-- Kaca helm -->
                                        <rect x="58" y="55" width="84" height="60" rx="30" fill="#1E1B4B" stroke="#1E1B4B" stroke-width="3"/>
                                        <path d="M72 68 q10-8 25-8" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round"/>

                                        <!-- Mata -->
                                        <circle cx="83" cy="85" r="9" fill="#7DD3FC"/>
                                        <circle cx="117" cy="85" r="9" fill="#7DD3FC"/>
                                        <circle cx="85" cy="87" r="4" fill="#1E1B4B"/>
                                        <circle cx="119" cy="87" r="4" fill="#1E1B4B"/>
                                        <circle cx="82" cy="82" r="2.5" fill="#FFFFFF"/>
                                        <circle cx="116" cy="82" r="2.5" fill="#FFFFFF"/>

                                        <!-- Pipi -->
                                        <ellipse cx="70" cy="98" rx="6" ry="4" fill="#F9A8D4" opacity="0.7"/>
                                        <ellipse cx="130" cy="98" rx="6" ry="4" fill="#F9A8D4" opacity="0.7"/>

                                        <!-- Senyum -->
                                        <path d="M92 100 q8 6 16 0" fill="none" stroke="#7DD3FC" stroke-width="3" stroke-linecap="round"/>

                                        <!-- Badge KAPTEN -->
                                        <text x="100" y="165" text-anchor="middle" font-family="Nunito, sans-serif" font-size="9" font-weight="900" fill="#7C3AED">KAPTEN</text>

                                        <!-- ======================= -->
                                        <!-- LAYER KACAMATA (di atas mata) -->
                                        <!-- ======================= -->
                                        <?php if ($kacamataAktif): ?>
                                            <g class="bounce-in">
                                                <!-- Bingkai kacamata -->
                                                <rect x="66" y="76" width="28" height="20" rx="8" fill="#FDE68A" stroke="#1E1B4B" stroke-width="2.5" opacity="0.95"/>
                                                <rect x="106" y="76" width="28" height="20" rx="8" fill="#FDE68A" stroke="#1E1B4B" stroke-width="2.5" opacity="0.95"/>
                                                <line x1="94" y1="84" x2="106" y2="84" stroke="#1E1B4B" stroke-width="2.5" stroke-linecap="round"/>
                                                <!-- Bintang kecil di lensa -->
                                                <polygon points="80,80 81,83 84,83.2 81.5,85 82.3,88 80,86.5 77.7,88 78.5,85 76,83.2 79,83" fill="#F59E0B"/>
                                                <polygon points="120,80 121,83 124,83.2 121.5,85 122.3,88 120,86.5 117.7,88 118.5,85 116,83.2 119,83" fill="#F59E0B"/>
                                            </g>
                                        <?php endif; ?>

                                        <!-- ======================= -->
                                        <!-- LAYER TOPI (di atas helm) -->
                                        <!-- ======================= -->
                                        <?php if ($topiAktif): ?>
                                            <g class="bounce-in">
                                                <?php if ($topiAktif === 'topi-astronaut'): ?>
                                                    <!-- Topi Astronaut Pastel -->
                                                    <path d="M55 42 a45 30 0 0 1 90 0 z" fill="#C4B5FD" stroke="#1E1B4B" stroke-width="3" stroke-linejoin="round"/>
                                                    <path d="M68 40 a32 20 0 0 1 64 0 z" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="2" stroke-linejoin="round"/>
                                                    <rect x="48" y="40" width="104" height="12" rx="6" fill="#F9A8D4" stroke="#1E1B4B" stroke-width="3"/>
                                                <?php elseif ($topiAktif === 'mahkota-galaksi'): ?>
                                                    <!-- Mahkota -->
                                                    <path d="M55 45 l-3-25 15 12 13-20 13 20 15-12-3 25 z" fill="#FCD34D" stroke="#B45309" stroke-width="3" stroke-linejoin="round"/>
                                                    <rect x="52" y="42" width="96" height="10" rx="3" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2.5"/>
                                                    <circle cx="52" cy="18" r="4" fill="#F472B6"/>
                                                    <circle cx="148" cy="18" r="4" fill="#F472B6"/>
                                                    <circle cx="100" cy="12" r="4" fill="#22D3EE"/>
                                                <?php endif; ?>
                                            </g>
                                        <?php endif; ?>

                                    </svg>
                                </div>

                                <div class="absolute top-2 right-4 text-2xl sparkle select-none">✨</div>
                                <div class="absolute bottom-6 left-2 text-xl sparkle select-none" style="animation-delay: 0.7s;">⭐</div>
                            </div>
                        </div>

                        <!-- INFO USER -->
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

                            <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-3">
                                <div class="inline-flex items-center gap-2 bg-amber-400 border-2 border-amber-300 rounded-2xl px-3 py-1.5 shadow">
                                    <span class="text-xl">⭐</span>
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Bintang</p>
                                        <p class="text-lg font-black text-amber-900 leading-none"><?= $star_coins ?></p>
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-2 bg-white/20 border-2 border-white/30 rounded-2xl px-3 py-1.5 shadow">
                                    <span class="text-xl">🎒</span>
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase text-white/80 leading-none">Item</p>
                                        <p class="text-lg font-black leading-none"><?= $totalItem ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- BADGE -->
            <section class="mb-6">
                <h2 class="text-lg sm:text-xl font-black mb-3">🏅 Lencana Pencapaian</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-amber-100 to-amber-200 border-amber-300">
                        <span class="text-4xl mb-1">🎯</span>
                        <p class="text-xs font-black leading-tight">Kuis Pertama</p>
                        <p class="text-[10px] font-bold opacity-70 mt-0.5">Selesaikan 1 kuis</p>
                    </div>
                    <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-rose-100 to-rose-200 border-rose-300">
                        <span class="text-4xl mb-1">⚡</span>
                        <p class="text-xs font-black leading-tight">3 Days Streak</p>
                        <p class="text-[10px] font-bold opacity-70 mt-0.5">Belajar 3 hari</p>
                    </div>
                    <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-violet-100 to-violet-200 border-violet-300">
                        <span class="text-4xl mb-1">🌌</span>
                        <p class="text-xs font-black leading-tight">Master Matematika</p>
                        <p class="text-[10px] font-bold opacity-70 mt-0.5">100% di Matematika</p>
                    </div>
                    <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 <?= $totalItem >= 3 ? 'bg-gradient-to-br from-emerald-100 to-emerald-200 border-emerald-300' : 'bg-slate-100 border-slate-200 opacity-50' ?>">
                        <span class="text-4xl mb-1">🏆</span>
                        <p class="text-xs font-black leading-tight">Kolektor Sejati</p>
                        <p class="text-[10px] font-bold opacity-70 mt-0.5">Beli 3 item</p>
                    </div>
                </div>
            </section>

            <!-- KOLEKSI SAYA -->
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg sm:text-xl font-black">🎒 Koleksi Saya</h2>
                    <a href="shop.php" class="text-xs sm:text-sm font-extrabold text-violet-600 hover:underline">
                        + Beli Item Baru →
                    </a>
                </div>

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
                    <div id="koleksi-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        <?php foreach ($koleksiSaya as $item):
                            $sedangDipakai = ($topiAktif === $item['id']) || ($kacamataAktif === $item['id']);
                            $bisaDipakai   = (($item['slot'] ?? '') === 'topi') || (($item['slot'] ?? '') === 'kacamata');
                        ?>
                            <article class="item-koleksi flex flex-col bg-white border-2 <?= $sedangDipakai ? 'border-emerald-400 bg-emerald-50' : 'border-violet-200' ?> rounded-2xl p-3 shadow-sm transition-all"
                                     data-item-id="<?= e($item['id']) ?>"
                                     data-item-slot="<?= e($item['slot'] ?? '') ?>">

                                <div class="w-16 h-16 mx-auto mb-2 flex items-center justify-center"><?= $item['ikon'] ?></div>
                                <h3 class="text-xs font-black leading-tight text-center flex-1"><?= e($item['nama']) ?></h3>

                                <?php if ($sedangDipakai): ?>
                                    <!-- DIPAKAI: tombol LEPAS -->
                                    <button type="button"
                                            class="btn-lepas-profile w-full mt-2 bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-xs py-1.5 rounded-full transition-transform hover:scale-105"
                                            data-item-id="<?= e($item['id']) ?>"
                                            data-item-slot="<?= e($item['slot'] ?? '') ?>">
                                        Lepas 🚫
                                    </button>
                                <?php elseif ($bisaDipakai): ?>
                                    <!-- BISA DIPAKAI: tombol GUNAKAN -->
                                    <button type="button"
                                            class="btn-pakai-profile w-full mt-2 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-xs py-1.5 rounded-full transition-transform hover:scale-105"
                                            data-item-id="<?= e($item['id']) ?>">
                                        Gunakan 👕
                                    </button>
                                <?php else: ?>
                                    <span class="mt-2 w-full text-center text-xs font-bold bg-slate-100 text-slate-500 py-1.5 rounded-full">
                                        Koleksi
                                    </span>
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
                star_coins: <?= (int) $star_coins ?>
            }
        };
    </script>
    <script>
    (function () {
        'use strict';

        var toastEl = document.getElementById('toast');

        function showToast(pesan, durasi) {
            if (!toastEl) return;
            toastEl.textContent = pesan;
            toastEl.classList.remove('hidden');
            toastEl.style.display = '';
            clearTimeout(toastEl._timer);
            toastEl._timer = setTimeout(function () {
                toastEl.classList.add('hidden');
                toastEl.style.display = 'none';
            }, durasi || 2400);
        }

        function reloadSetelah(pesan, delay) {
            showToast(pesan, 1500);
            setTimeout(function () { window.location.reload(); }, delay || 800);
        }

        // ============ TOMBOL GUNAKAN ============
        document.querySelectorAll('.btn-pakai-profile').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = btn.getAttribute('data-item-id');
                if (!id) return;

                btn.disabled = true;
                btn.textContent = 'Memakai...';

                fetch('api/pakai_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ item_id: id })
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.ok) {
                        showToast('❌ ' + (data.error || 'Gagal pakai'), 3000);
                        btn.disabled = false;
                        btn.textContent = 'Gunakan 👕';
                        return;
                    }
                    reloadSetelah('👕 Berhasil dipakai!');
                })
                .catch(function () {
                    showToast('❌ Gagal koneksi server', 3000);
                    btn.disabled = false;
                    btn.textContent = 'Gunakan 👕';
                });
            });
        });

        // ============ TOMBOL LEPAS ============
        document.querySelectorAll('.btn-lepas-profile').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = btn.getAttribute('data-item-id');
                var slot = btn.getAttribute('data-item-slot');
                if (!id) return;

                btn.disabled = true;
                btn.textContent = 'Melepas...';

                fetch('api/lepas_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ item_id: id, slot: slot })
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.ok) {
                        showToast('❌ ' + (data.error || 'Gagal lepas'), 3000);
                        btn.disabled = false;
                        btn.textContent = 'Lepas 🚫';
                        return;
                    }
                    reloadSetelah('🚫 Berhasil dilepas!');
                })
                .catch(function () {
                    showToast('❌ Gagal koneksi server', 3000);
                    btn.disabled = false;
                    btn.textContent = 'Lepas 🚫';
                });
            });
        });
    })();
    </script>
</body>
</html>