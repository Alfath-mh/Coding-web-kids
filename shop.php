<?php
// ============================================================
// shop.php — Toko Bintang (localStorage, tanpa database)
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
    <title>Toko Bintang - BRIGHT SPACE</title>
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
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-40 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
        <nav class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
            <a href="index.php" class="text-lg font-black">BRIGHT <span class="text-violet-600">SPACE</span></a>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-2.5 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/>
                    </svg>
                    <span id="shop-saldo" class="font-extrabold text-base"><?= (int) $user['star_coins'] ?></span>
                </div>
                <a href="logout.php" class="bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-sm px-3 py-2 rounded-full shadow">Keluar</a>
            </div>
        </nav>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1">

        <section class="mb-6">
            <div class="relative overflow-hidden bg-gradient-to-r from-violet-500 to-indigo-500 rounded-3xl shadow-xl p-6 sm:p-8 text-white">
                <div class="absolute top-4 right-4 text-6xl opacity-20 select-none">🛍️</div>
                <div class="relative">
                    <span class="inline-block bg-white/20 text-xs font-extrabold uppercase tracking-wider px-3 py-1.5 rounded-full">🛍️ Space Shop</span>
                    <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">Toko Bintang</h1>
                    <p class="mt-1 text-sm font-bold opacity-90">Tukar Koin Bintangmu dengan item keren!</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-lg sm:text-xl font-black mb-3">🛒 Item Tersedia</h2>
            <div id="shop-full-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($items as $item): ?>
                    <article class="item-toko flex flex-col bg-white border-2 border-violet-200 rounded-3xl p-5 shadow-sm hover:shadow-lg transition-all"
                             data-item-id="<?= e($item['id']) ?>"
                             data-item-nama="<?= e($item['nama']) ?>"
                             data-item-harga="<?= (int) $item['harga'] ?>">
                        <div class="w-24 h-24 mx-auto mb-3 flex items-center justify-center"><?= $item['ikon'] ?></div>
                        <h3 class="text-base font-black leading-tight text-center"><?= e($item['nama']) ?></h3>
                        <p class="mt-1 text-xs font-bold opacity-70 text-center flex-1"><?= e($item['deskripsi']) ?></p>
                        <div class="mt-3 inline-flex items-center justify-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full px-3 py-1 mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4">
                                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/>
                            </svg>
                            <span class="text-sm font-extrabold"><?= (int) $item['harga'] ?> Koin</span>
                        </div>
                        <div class="btn-area mt-3">
                            <button type="button" class="btn-beli w-full bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2.5 rounded-full shadow">
                                Beli 🛒
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" style="display:none;"></div>

    <script>
        window.BRIGHT_SPACE = {
            user: {
                id: 1,
                nama: <?= json_encode($user['nama'], JSON_UNESCAPED_UNICODE) ?>,
                star_coins: <?= (int) $user['star_coins'] ?>
            }
        };
    </script>
    <script>
    (function () {
        'use strict';
        var BS = window.BRIGHT_SPACE;
        if (!BS || !BS.user) return;

        var KEY_KOIN = 'bs_koin_1';
        var KEY_KOLEKSI = 'bs_koleksi_1';

        var koinSekarang = (function () {
            var v = localStorage.getItem(KEY_KOIN);
            return v !== null ? parseInt(v, 10) : BS.user.star_coins;
        })();

        var koleksi = (function () {
            try {
                var v = localStorage.getItem(KEY_KOLEKSI);
                return v ? JSON.parse(v) : [];
            } catch (e) { return []; }
        })();

        var grid = document.getElementById('shop-full-grid');
        var saldoEl = document.getElementById('shop-saldo');
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

        function simpanKoin() { localStorage.setItem(KEY_KOIN, String(koinSekarang)); }
        function simpanKoleksi() { localStorage.setItem(KEY_KOLEKSI, JSON.stringify(koleksi)); }
        function sudahDimiliki(id) {
            for (var i = 0; i < koleksi.length; i++) if (koleksi[i].id === id) return true;
            return false;
        }

        function renderTombol(card) {
            var id = card.getAttribute('data-item-id');
            var area = card.querySelector('.btn-area');
            if (!area) return;
            if (sudahDimiliki(id)) {
                area.innerHTML = '<div class="w-full text-center bg-emerald-100 text-emerald-700 font-extrabold text-sm py-2.5 rounded-full">Dimiliki ✅</div>';
            } else {
                area.innerHTML = '<button type="button" class="btn-beli w-full bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2.5 rounded-full shadow">Beli 🛒</button>';
            }
        }

        grid.querySelectorAll('.item-toko').forEach(renderTombol);
        if (saldoEl) saldoEl.textContent = koinSekarang;

        grid.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-beli');
            if (!btn || btn.disabled) return;
            var card = btn.closest('.item-toko');
            var id = card.getAttribute('data-item-id');
            var nama = card.getAttribute('data-item-nama');
            var harga = parseInt(card.getAttribute('data-item-harga'), 10) || 0;

            if (sudahDimiliki(id)) { showToast('Kamu sudah punya item ini! ✅'); return; }
            if (koinSekarang < harga) {
                showToast('😢 Koin belum cukup! Butuh ' + (harga - koinSekarang) + ' koin lagi.', 3000);
                return;
            }

            koinSekarang -= harga;
            simpanKoin();
            koleksi.push({ id: id, nama: nama });
            simpanKoleksi();

            if (saldoEl) saldoEl.textContent = koinSekarang;
            renderTombol(card);
            showToast('🎉 ' + nama + ' jadi milikmu!', 2500);
        });
    })();
    </script>
</body>
</html>