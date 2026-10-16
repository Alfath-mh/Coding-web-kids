<?php
// ============================================================
// profile.php — Profil Kapten (tanpa database)
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

$topiAktif     = $_SESSION['avatar_topi'] ?? null;
$kacamataAktif = $_SESSION['avatar_kacamata'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kapten - BRIGHT SPACE</title>
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
        @keyframes float-frame { 0%,100%{transform:translateY(0);}50%{transform:translateY(-6px);} }
        @keyframes glow-ring { 0%,100%{box-shadow:0 0 0 0 rgba(139,92,246,0.4);}50%{box-shadow:0 0 0 16px rgba(139,92,246,0);} }
        @keyframes sparkle { 0%,100%{opacity:0.3;transform:scale(0.8);}50%{opacity:1;transform:scale(1.2);} }
        @keyframes bob { 0%,100%{transform:translateY(0);}50%{transform:translateY(-3px);} }
        .profile-float { animation: float-frame 3s ease-in-out infinite; }
        .profile-glow  { animation: glow-ring 2.5s ease-in-out infinite; }
        .sparkle { animation: sparkle 1.5s ease-in-out infinite; }
        .astronot-bob { animation: bob 2.5s ease-in-out infinite; }
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

        <section class="mb-6">
            <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 via-indigo-500 to-purple-600 rounded-3xl shadow-xl p-6 sm:p-8 text-white">
                <div class="absolute top-4 right-4 text-5xl opacity-20">✨</div>
                <div class="absolute bottom-4 left-4 text-4xl opacity-15">🌌</div>
                <div class="absolute top-1/3 left-1/4 text-2xl sparkle">⭐</div>

                <div class="relative flex flex-col sm:flex-row items-center gap-6">
                    <div class="profile-float shrink-0">
                        <div class="profile-glow relative w-44 h-44 rounded-full bg-gradient-to-br from-amber-200 via-yellow-300 to-amber-400 border-4 border-amber-300 flex items-center justify-center shadow-2xl">
                            <div class="astronot-bob relative w-36 h-36">
                                <svg viewBox="0 0 200 200" class="w-full h-full" aria-hidden="true">
                                    <ellipse cx="100" cy="185" rx="50" ry="8" fill="#000" opacity="0.15"/>
                                    <rect x="70" y="130" width="60" height="45" rx="20" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                    <rect x="85" y="140" width="30" height="20" rx="4" fill="#C4B5FD" stroke="#1E1B4B" stroke-width="2"/>
                                    <circle cx="93" cy="148" r="2" fill="#FCD34D"/>
                                    <circle cx="100" cy="148" r="2" fill="#34D399"/>
                                    <circle cx="107" cy="148" r="2" fill="#F87171"/>
                                    <rect x="88" y="154" width="24" height="3" rx="1" fill="#7DD3FC"/>
                                    <ellipse cx="62" cy="145" rx="14" ry="20" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                    <ellipse cx="138" cy="145" rx="14" ry="20" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                    <rect x="78" y="172" width="18" height="12" rx="6" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                    <rect x="104" y="172" width="18" height="12" rx="6" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                    <circle cx="100" cy="85" r="52" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="3"/>
                                    <line x1="100" y1="30" x2="100" y2="15" stroke="#1E1B4B" stroke-width="3" stroke-linecap="round"/>
                                    <circle cx="100" cy="10" r="6" fill="#FCD34D" stroke="#B45309" stroke-width="2"/>
                                    <rect x="58" y="55" width="84" height="60" rx="30" fill="#1E1B4B" stroke="#1E1B4B" stroke-width="3"/>
                                    <path d="M72 68 q10-8 25-8" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round"/>
                                    <circle cx="83" cy="85" r="9" fill="#7DD3FC"/>
                                    <circle cx="117" cy="85" r="9" fill="#7DD3FC"/>
                                    <circle cx="85" cy="87" r="4" fill="#1E1B4B"/>
                                    <circle cx="119" cy="87" r="4" fill="#1E1B4B"/>
                                    <circle cx="82" cy="82" r="2.5" fill="#FFFFFF"/>
                                    <circle cx="116" cy="82" r="2.5" fill="#FFFFFF"/>
                                    <ellipse cx="70" cy="98" rx="6" ry="4" fill="#F9A8D4" opacity="0.7"/>
                                    <ellipse cx="130" cy="98" rx="6" ry="4" fill="#F9A8D4" opacity="0.7"/>
                                    <path d="M92 100 q8 6 16 0" fill="none" stroke="#7DD3FC" stroke-width="3" stroke-linecap="round"/>
                                    <text x="100" y="165" text-anchor="middle" font-family="Nunito, sans-serif" font-size="9" font-weight="900" fill="#7C3AED">KAPTEN</text>
                                </svg>
                            </div>
                            <div class="absolute top-2 right-4 text-2xl sparkle">✨</div>
                            <div class="absolute bottom-6 left-2 text-xl sparkle">⭐</div>
                        </div>
                    </div>

                    <div class="text-center sm:text-left flex-1">
                        <p class="inline-block bg-white/20 text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full">👑 Kapten Galaksi</p>
                        <h1 class="mt-2 text-3xl sm:text-4xl font-black leading-tight"><?= e($user['nama']) ?></h1>
                        <p class="mt-1 text-sm font-bold opacity-90">Penjelajah Galaksi Level 1</p>

                        <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-3">
                            <div class="inline-flex items-center gap-2 bg-amber-400 border-2 border-amber-300 rounded-2xl px-3 py-1.5 shadow">
                                <span class="text-xl">⭐</span>
                                <div>
                                    <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Bintang</p>
                                    <p class="text-lg font-black text-amber-900 leading-none"><?= (int) $user['star_coins'] ?></p>
                                </div>
                            </div>
                            <div class="inline-flex items-center gap-2 bg-white/20 border-2 border-white/30 rounded-2xl px-3 py-1.5 shadow">
                                <span class="text-xl">🎒</span>
                                <div>
                                    <p class="text-[10px] font-extrabold uppercase text-white/80 leading-none">Item</p>
                                    <p class="text-lg font-black leading-none" id="stat-item">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-lg font-black mb-3">🏅 Lencana Pencapaian</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-amber-100 to-amber-200 border-amber-300">
                    <span class="text-4xl mb-1">🎯</span>
                    <p class="text-xs font-black leading-tight">Kuis Pertama</p>
                    <p class="text-[10px] font-bold opacity-70">Selesaikan 1 kuis</p>
                </div>
                <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-rose-100 to-rose-200 border-rose-300">
                    <span class="text-4xl mb-1">⚡</span>
                    <p class="text-xs font-black leading-tight">3 Days Streak</p>
                    <p class="text-[10px] font-bold opacity-70">Belajar 3 hari</p>
                </div>
                <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-violet-100 to-violet-200 border-violet-300">
                    <span class="text-4xl mb-1">🌌</span>
                    <p class="text-xs font-black leading-tight">Master Matematika</p>
                    <p class="text-[10px] font-bold opacity-70">100% di Matematika</p>
                </div>
                <div class="flex flex-col items-center text-center p-3 rounded-2xl border-2 bg-gradient-to-br from-emerald-100 to-emerald-200 border-emerald-300">
                    <span class="text-4xl mb-1">🏆</span>
                    <p class="text-xs font-black leading-tight">Kolektor Sejati</p>
                    <p class="text-[10px] font-bold opacity-70">Beli 3 item</p>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-black">🎒 Koleksi Saya</h2>
                <a href="shop.php" class="text-xs font-extrabold text-violet-600 hover:underline">+ Beli Item Baru →</a>
            </div>

            <div id="koleksi-container">
                <div id="koleksi-empty" class="bg-white rounded-2xl p-8 text-center border-2 border-dashed border-violet-200">
                    <p class="text-4xl mb-2">🎒</p>
                    <p class="text-sm font-extrabold text-slate-600">Koleksimu masih kosong</p>
                    <p class="text-xs font-bold text-slate-400 mt-1">Beli item di Toko Bintang untuk mengisi koleksimu!</p>
                    <a href="shop.php" class="inline-block mt-4 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm px-6 py-2.5 rounded-full shadow">Ke Toko Bintang 🛍️</a>
                </div>
                <div id="koleksi-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 hidden"></div>
            </div>
        </section>
    </main>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" style="display:none;"></div>

    <script>
        window.BRIGHT_SPACE_ITEMS = <?= json_encode($items, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
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

        var KEY_KOLEKSI = 'bs_koleksi_1';
        var koleksi = (function () {
            try {
                var v = localStorage.getItem(KEY_KOLEKSI);
                return v ? JSON.parse(v) : [];
            } catch (e) { return []; }
        })();

        var statItem = document.getElementById('stat-item');
        if (statItem) statItem.textContent = koleksi.length;

        if (koleksi.length === 0) return;

        var emptyEl = document.getElementById('koleksi-empty');
        var gridEl = document.getElementById('koleksi-grid');
        if (!emptyEl || !gridEl) return;

        emptyEl.classList.add('hidden');
        gridEl.classList.remove('hidden');
        gridEl.innerHTML = '';

        var itemMaster = window.BRIGHT_SPACE_ITEMS || [];

        koleksi.forEach(function (item) {
            var master = null;
            for (var i = 0; i < itemMaster.length; i++) {
                if (itemMaster[i].id === item.id) { master = itemMaster[i]; break; }
            }

            var card = document.createElement('article');
            card.className = 'flex flex-col bg-white border-2 border-violet-200 rounded-2xl p-3 shadow-sm text-center';
            card.innerHTML =
                '<div class="text-4xl text-center mb-2">' + (master ? '🎁' : '🎁') + '</div>' +
                '<h3 class="text-xs font-black leading-tight flex-1">' + item.nama + '</h3>' +
                '<span class="mt-2 w-full text-center text-xs font-bold bg-slate-100 text-slate-500 py-1.5 rounded-full">Koleksi</span>';
            gridEl.appendChild(card);
        });
    })();
    </script>
</body>
</html>