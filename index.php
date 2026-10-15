<?php
// ============================================================
// BRIGHT SPACE - EdTech Platform untuk Siswa SD
// Sub-Tahap 2A: Setup Layout & Header
// Stack: PHP Native + HTML5 + Tailwind (CDN) + Vanilla JS
// ============================================================

$appName   = 'BRIGHT SPACE';
$pageTitle = 'BRIGHT SPACE - Petualangan Belajar di Luar Angkasa';
$starCoins = 100; // Poin awal siswa (akan dikelola pada sub-tahap berikutnya)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BRIGHT SPACE: platform belajar interaktif bertema luar angkasa untuk siswa Sekolah Dasar.">
    <meta name="theme-color" content="#E0F2FE">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Google Font: Nunito (bulat & ramah anak) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cosmic: {
                            bg:     '#E0F2FE', // bg-cosmic     : Biru langit lembut
                            card:   '#EDE9FE', // card-cosmic   : Ungu muda pastel
                            accent: '#FEF3C7', // accent-cosmic : Kuning hangat / bintang
                            text:   '#1E1B4B'  // text-cosmic   : Indigo gelap deep space
                        }
                    },
                    fontFamily: {
                        nunito: ['Nunito', 'ui-rounded', 'system-ui', 'sans-serif']
                    }
                }
            }
        };
    </script>

    <style>
        /* Animasi ringan (hanya transform) agar hemat CPU/GPU */
        @keyframes rocket-wiggle {
            0%, 100% { transform: rotate(0deg) translateY(0); }
            25%      { transform: rotate(-8deg) translateY(-2px); }
            75%      { transform: rotate(8deg) translateY(-2px); }
        }
        @keyframes coin-pop {
            0%   { transform: scale(1); }
            50%  { transform: scale(1.18); }
            100% { transform: scale(1); }
        }
        .logo-link:hover .rocket-icon,
        .logo-link:focus-visible .rocket-icon {
            animation: rocket-wiggle 0.6s ease-in-out infinite;
        }
        .coin-pop { animation: coin-pop 0.4s ease-out; }

        /* Hormati pengaturan "kurangi gerakan" milik perangkat */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <!-- ===================== HEADER (STICKY) ===================== -->
    <header class="sticky top-0 z-50 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
        <nav class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3" aria-label="Navigasi utama">

            <!-- Logo + Ikon Roket SVG -->
            <a href="index.php" class="logo-link flex items-center gap-2 rounded-xl focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent" aria-label="<?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?> - Beranda">
                <span class="rocket-icon inline-flex items-center justify-center w-11 h-11 rounded-full bg-cosmic-accent shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7" aria-hidden="true">
                        <!-- Api roket -->
                        <path d="M9.5 17.5c-.5 1.6-.3 3.2.5 4.5 1-.6 1.6-1.2 2-2 .4.8 1 1.4 2 2 .8-1.3 1-2.9.5-4.5z" fill="#FB923C"/>
                        <path d="M10.8 17.5c-.2 1-.1 1.8.4 2.6.5-.5.7-1 .8-1.6.1.6.3 1.1.8 1.6.5-.8.6-1.6.4-2.6z" fill="#FDE047"/>
                        <!-- Sirip -->
                        <path d="M8 11.5 4.5 15l.5 3 3.5-2z" fill="#A78BFA"/>
                        <path d="M16 11.5 19.5 15l-.5 3-3.5-2z" fill="#A78BFA"/>
                        <!-- Badan -->
                        <path d="M12 1.5c3 2 4.8 5.6 4.8 9.6 0 2.2-.4 4.2-1.1 6.4H8.3c-.7-2.2-1.1-4.2-1.1-6.4 0-4 1.8-7.6 4.8-9.6z" fill="#F8FAFC" stroke="#1E1B4B" stroke-width="1.2" stroke-linejoin="round"/>
                        <!-- Hidung -->
                        <path d="M12 1.5c1.2.8 2.2 1.9 3 3.2H9c.8-1.3 1.8-2.4 3-3.2z" fill="#F472B6"/>
                        <!-- Jendela -->
                        <circle cx="12" cy="10" r="2.2" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="1.2"/>
                    </svg>
                </span>
                <span class="text-xl sm:text-2xl font-black tracking-wide leading-none">
                    BRIGHT <span class="text-violet-600">SPACE</span>
                </span>
            </a>

            <!-- Counter Star Coins + Tombol Toko Bintang -->
            <div class="flex items-center gap-2 sm:gap-3">

                <!-- Badge Star Coins -->
                <div class="flex items-center gap-1.5 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-2 pr-3 py-1 shadow-sm" role="status" aria-live="polite" aria-label="Jumlah Star Coins">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 shrink-0" aria-hidden="true">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                 fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                    </svg>
                    <span id="star-coin-count" class="font-extrabold text-base sm:text-lg leading-none"><?= (int) $starCoins ?></span>
                    <span class="hidden sm:inline font-bold text-sm leading-none">Star Coins</span>
                </div>

                <!-- Tombol Toko Bintang -->
                <button type="button" id="btn-toko-bintang"
                        class="flex items-center gap-1.5 bg-violet-500 text-white font-extrabold text-sm sm:text-base px-3 sm:px-4 py-2 rounded-full shadow-md transition-transform duration-150 hover:scale-105 hover:bg-violet-600 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 9l1.5-5h15L21 9"/>
                        <path d="M3 9c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3"/>
                        <path d="M5 12v8h14v-8"/>
                        <path d="M10 20v-4h4v4"/>
                    </svg>
                    <span>Toko Bintang</span>
                </button>
            </div>
        </nav>
    </header>

    <!-- ===================== MAIN CONTAINER ===================== -->
    <main id="main-content" class="max-w-6xl mx-auto px-4 py-8 w-full flex-grow">
        <!-- Area kosong: kartu 4 Planet Belajar akan ditambahkan pada Sub-Tahap berikutnya -->
    </main>

    <!-- ===================== TOAST NOTIFIKASI ===================== -->
    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-50 hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg" role="status" aria-live="polite"></div>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 py-4 text-center text-xs sm:text-sm font-bold">
            Optimized for Low-Spec School Hardware (RAM 2GB-4GB) | BRIGHT SPACE EdTech
        </div>
    </footer>

    <!-- ===================== VANILLA JS ===================== -->
    <script>
        (function () {
            'use strict';

            var coinEl  = document.getElementById('star-coin-count');
            var toastEl = document.getElementById('toast');
            var toastTimer = null;

            // Memperbarui counter Star Coins (dipakai kuis pada sub-tahap berikutnya)
            window.setStarCoins = function (value) {
                var n = Math.max(0, parseInt(value, 10) || 0);
                coinEl.textContent = n;
                coinEl.classList.remove('coin-pop');
                void coinEl.offsetWidth; // restart animasi
                coinEl.classList.add('coin-pop');
            };

            window.getStarCoins = function () {
                return parseInt(coinEl.textContent, 10) || 0;
            };

            window.showToast = function (message) {
                toastEl.textContent = message;
                toastEl.classList.remove('hidden');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(function () {
                    toastEl.classList.add('hidden');
                }, 2200);
            };

            document.getElementById('btn-toko-bintang').addEventListener('click', function () {
                window.showToast('Toko Bintang segera dibuka! Kumpulkan Star Coins dulu ya!');
            });
        })();
    </script>
</body>
</html>