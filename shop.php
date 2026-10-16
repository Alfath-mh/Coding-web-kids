<?php
// ============================================================
// shop.php — Toko Bintang (Session + localStorage, no DB)
// ============================================================
define('BRIGHT_SPACE', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/config/database.php';
require_login();

// Ambil user dari session (current_user() baca session)
$user = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

// Data item dari config (array statis, no DB)
require __DIR__ . '/config/planets.php';

// Ambil koin dari session (fallback kalau gak ada → 120)
$koinSekarang = isset($_SESSION['star_coins']) ? (int) $_SESSION['star_coins'] : 120;
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
    <style>
        @keyframes bounce-in { 0%{transform:scale(0.6);opacity:0;}100%{transform:scale(1);opacity:1;} }
        @keyframes coin-pop { 0%{transform:scale(1);}50%{transform:scale(1.18);}100%{transform:scale(1);} }
        .bounce-in { animation: bounce-in 0.4s ease-out; }
        .coin-pop { animation: coin-pop 0.4s ease-out; }
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div class="lg:ml-72 min-h-screen flex flex-col">

        <?php include __DIR__ . '/components/header.php'; ?>

        <main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1">

            <!-- HERO TOKO -->
            <section class="mb-6">
                <div class="relative overflow-hidden bg-gradient-to-r from-violet-500 to-indigo-500 rounded-3xl shadow-xl p-6 sm:p-8 text-white">
                    <div class="absolute top-4 right-4 text-6xl opacity-20 select-none">🛍️</div>
                    <div class="absolute bottom-2 right-24 text-3xl opacity-15 select-none">✨</div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <span class="inline-block bg-white/20 text-xs font-extrabold uppercase tracking-wider px-3 py-1.5 rounded-full">
                                🛍️ Space Shop
                            </span>
                            <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">Toko Bintang</h1>
                            <p class="mt-1 text-sm font-bold opacity-90">
                                Tukar Koin Bintangmu dengan item keren, <?= e($user['nama']) ?>!
                            </p>
                        </div>

                        <div id="shop-koin-badge" class="shrink-0 inline-flex items-center gap-2 bg-amber-400 border-2 border-amber-300 rounded-2xl pl-2 pr-4 py-2 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7">
                                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/>
                            </svg>
                            <div>
                                <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Koin Kamu</p>
                                <p class="text-xl font-black text-amber-900 leading-none" id="shop-saldo"><?= $koinSekarang ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- GRID ITEM -->
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg sm:text-xl font-black">🛒 Item Tersedia</h2>
                    <span id="koleksi-count-badge" class="text-xs font-extrabold bg-violet-100 border-2 border-violet-200 text-violet-700 px-3 py-1 rounded-full">
                        🎒 <span id="koleksi-count">0</span> dimiliki
                    </span>
                </div>

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
                                <button type="button" class="btn-beli w-full bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2.5 rounded-full shadow transition-transform hover:scale-105 active:scale-95">
                                    Beli 🛒
                                </button>
                            </div>
                        </article>
                    <?php endforeach; ?>
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
                id: 1,
                nama: <?= json_encode($user['nama'], JSON_UNESCAPED_UNICODE) ?>,
                star_coins: <?= (int) $koinSekarang ?>
            }
        };
    </script>
    <script>
    // ============================================================
    // SHOP LOGIC — localStorage, no DB
    // ============================================================
    (function () {
        'use strict';

        var BS = window.BRIGHT_SPACE;
        if (!BS || !BS.user) return;

        var KEY_KOIN     = 'bs_koin_1';
        var KEY_KOLEKSI  = 'bs_koleksi_1';

        // Ambil koin dari localStorage (fallback ke session value)
        var koinSekarang = (function () {
            var v = localStorage.getItem(KEY_KOIN);
            return v !== null ? parseInt(v, 10) : BS.user.star_coins;
        })();

        // Ambil koleksi
        var koleksi = (function () {
            try {
                var v = localStorage.getItem(KEY_KOLEKSI);
                return v ? JSON.parse(v) : [];
            } catch (e) { return []; }
        })();

        var grid         = document.getElementById('shop-full-grid');
        var saldoEl      = document.getElementById('shop-saldo');
        var toastEl      = document.getElementById('toast');
        var koleksiCount = document.getElementById('koleksi-count');

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

        function simpanKoin()     { localStorage.setItem(KEY_KOIN, String(koinSekarang)); }
        function simpanKoleksi()  { localStorage.setItem(KEY_KOLEKSI, JSON.stringify(koleksi)); }

        function sudahDimiliki(id) {
            for (var i = 0; i < koleksi.length; i++) {
                if (koleksi[i].id === id) return true;
            }
            return false;
        }

        function tampilSaldo() {
            if (saldoEl) saldoEl.textContent = koinSekarang;
        }

        function tampilJumlahKoleksi() {
            if (koleksiCount) koleksiCount.textContent = koleksi.length;
        }

        function renderTombol(card) {
            var id = card.getAttribute('data-item-id');
            var area = card.querySelector('.btn-area');
            if (!area) return;

            if (sudahDimiliki(id)) {
                area.innerHTML =
                    '<div class="w-full text-center bg-emerald-100 text-emerald-700 font-extrabold text-sm py-2.5 rounded-full bounce-in">' +
                        'Dimiliki ✅' +
                    '</div>' +
                    '<a href="profile.php" class="block w-full mt-2 text-center bg-violet-100 hover:bg-violet-200 text-violet-700 font-extrabold text-xs py-2 rounded-full">' +
                        'Lihat di Profil →' +
                    '</a>';
            } else {
                area.innerHTML =
                    '<button type="button" class="btn-beli w-full bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2.5 rounded-full shadow transition-transform hover:scale-105 active:scale-95">' +
                        'Beli 🛒' +
                    '</button>';
            }
        }

        // Render semua kartu saat halaman load
        grid.querySelectorAll('.item-toko').forEach(renderTombol);
        tampilSaldo();
        tampilJumlahKoleksi();

        // Handle klik Beli
        grid.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-beli');
            if (!btn || btn.disabled) return;

            var card  = btn.closest('.item-toko');
            var id    = card.getAttribute('data-item-id');
            var nama  = card.getAttribute('data-item-nama');
            var harga = parseInt(card.getAttribute('data-item-harga'), 10) || 0;

            if (sudahDimiliki(id)) {
                showToast('Kamu sudah punya item ini! ✅', 2000);
                return;
            }

            if (koinSekarang < harga) {
                showToast('😢 Koin belum cukup! Butuh ' + (harga - koinSekarang) + ' koin lagi.', 3000);
                return;
            }

            // ============ PROSES BELI ============
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            // Simulasi delay 300ms biar ada feedback visual
            setTimeout(function () {
                // Kurangi koin
                koinSekarang -= harga;
                simpanKoin();
                tampilSaldo();

                // Tambah ke koleksi
                koleksi.push({ id: id, nama: nama, harga: harga, purchased_at: Date.now() });
                simpanKoleksi();
                tampilJumlahKoleksi();

                // Update tombol
                renderTombol(card);

                // Animasi koin
                var badge = document.getElementById('shop-koin-badge');
                if (badge) {
                    badge.classList.remove('coin-pop');
                    void badge.offsetWidth;
                    badge.classList.add('coin-pop');
                }

                // Toast sukses
                showToast('🎉 ' + nama + ' jadi milikmu!', 2500);
            }, 300);
        });

        // Sinkron koin ke sidebar & header kalau ada
        var sidebarKoin = document.getElementById('sidebar-koin');
        var headerKoin  = document.getElementById('star-coin-count');
        if (sidebarKoin) sidebarKoin.textContent = koinSekarang;
        if (headerKoin)  headerKoin.textContent  = koinSekarang;
    })();
    </script>
</body>
</html>