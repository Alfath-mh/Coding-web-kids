<?php
// ============================================================
// BRIGHT SPACE - EdTech Platform untuk Siswa SD
// Sub-Tahap 2B: Grid 4 Kartu Planet Mata Pelajaran
// Stack: PHP Native + HTML5 + Tailwind (CDN) + Vanilla JS
// ============================================================

$appName   = 'BRIGHT SPACE';
$pageTitle = 'BRIGHT SPACE - Petualangan Belajar di Luar Angkasa';
$starCoins = 100; // Poin awal siswa (akan dikelola pada sub-tahap berikutnya)

// Fungsi bantu: escape teks agar aman ditampilkan di HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// ------------------------------------------------------------
// Data 4 Planet Belajar (SVG ikon ditulis inline agar ringan)
// ------------------------------------------------------------
$planets = [
    [
        'id'        => 'matematika',
        'nama'      => 'Planet Matematika',
        'deskripsi' => 'Petualangan Angka & Logika',
        'poin'      => 20,
        'status'    => 'Terbuka',
        'level'     => 0,
        'gradien'   => 'from-blue-100 to-indigo-200',
        'border'    => 'border-indigo-300',
        'tombol'    => 'bg-indigo-500 hover:bg-indigo-600 focus-visible:ring-indigo-300',
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <ellipse cx="32" cy="34" rx="30" ry="9" fill="none" stroke="#A5B4FC" stroke-width="3" transform="rotate(-18 32 32)"/>
                <circle cx="32" cy="32" r="20" fill="#6366F1"/>
                <circle cx="25" cy="24" r="5" fill="#818CF8"/>
                <g stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round" fill="none">
                    <path d="M21 28h10M26 23v10"/>
                    <path d="M37 41h9"/>
                    <path d="M38 27l7 7M45 27l-7 7"/>
                </g>
                <path d="M2 34a30 9 0 0 0 60 0" fill="none" stroke="#A5B4FC" stroke-width="3" transform="rotate(-18 32 32)"/>
            </svg>',
    ],
    [
        'id'        => 'sains',
        'nama'      => 'Planet Sains',
        'deskripsi' => 'Eksplorasi Alam & Antariksa',
        'poin'      => 25,
        'status'    => 'Terbuka',
        'level'     => 0,
        'gradien'   => 'from-emerald-100 to-green-200',
        'border'    => 'border-emerald-300',
        'tombol'    => 'bg-emerald-500 hover:bg-emerald-600 focus-visible:ring-emerald-300',
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <ellipse cx="32" cy="34" rx="30" ry="9" fill="none" stroke="#6EE7B7" stroke-width="3" transform="rotate(-18 32 32)"/>
                <circle cx="32" cy="32" r="20" fill="#10B981"/>
                <circle cx="25" cy="24" r="5" fill="#34D399"/>
                <g fill="none" stroke="#FFFFFF" stroke-width="2">
                    <ellipse cx="32" cy="32" rx="14" ry="5.5"/>
                    <ellipse cx="32" cy="32" rx="14" ry="5.5" transform="rotate(60 32 32)"/>
                    <ellipse cx="32" cy="32" rx="14" ry="5.5" transform="rotate(120 32 32)"/>
                </g>
                <circle cx="32" cy="32" r="3" fill="#FFFFFF"/>
                <path d="M2 34a30 9 0 0 0 60 0" fill="none" stroke="#6EE7B7" stroke-width="3" transform="rotate(-18 32 32)"/>
            </svg>',
    ],
    [
        'id'        => 'bahasa',
        'nama'      => 'Planet Bahasa',
        'deskripsi' => 'Dunia Kata & Cerita Seru',
        'poin'      => 15,
        'status'    => 'Terbuka',
        'level'     => 0,
        'gradien'   => 'from-amber-100 to-yellow-200',
        'border'    => 'border-amber-300',
        'tombol'    => 'bg-amber-500 hover:bg-amber-600 focus-visible:ring-amber-300',
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <ellipse cx="32" cy="34" rx="30" ry="9" fill="none" stroke="#FCD34D" stroke-width="3" transform="rotate(-18 32 32)"/>
                <circle cx="32" cy="32" r="20" fill="#F59E0B"/>
                <circle cx="25" cy="24" r="5" fill="#FBBF24"/>
                <path d="M32 25c-4-3-9-3-14-2v18c5-1 10-1 14 2z" fill="#FFFFFF"/>
                <path d="M32 25c4-3 9-3 14-2v18c-5-1-10-1-14 2z" fill="#FEF3C7"/>
                <path d="M32 25v18" stroke="#B45309" stroke-width="2" stroke-linecap="round"/>
                <path d="M2 34a30 9 0 0 0 60 0" fill="none" stroke="#FCD34D" stroke-width="3" transform="rotate(-18 32 32)"/>
            </svg>',
    ],
    [
        'id'        => 'seni-coding',
        'nama'      => 'Planet Seni & Coding',
        'deskripsi' => 'Kreasi Gambar & Algoritma',
        'poin'      => 30,
        'status'    => 'Terkunci',
        'level'     => 5,
        'gradien'   => 'from-purple-100 to-pink-200',
        'border'    => 'border-pink-300',
        'tombol'    => 'bg-purple-500 hover:bg-purple-600 focus-visible:ring-purple-300',
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <ellipse cx="32" cy="34" rx="30" ry="9" fill="none" stroke="#F9A8D4" stroke-width="3" transform="rotate(-18 32 32)"/>
                <circle cx="32" cy="32" r="20" fill="#A855F7"/>
                <circle cx="25" cy="24" r="5" fill="#C084FC"/>
                <g fill="none" stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="26,25 18,32 26,39"/>
                    <polyline points="38,25 46,32 38,39"/>
                    <line x1="34" y1="22" x2="30" y2="42"/>
                </g>
                <circle cx="46" cy="19" r="3" fill="#F9A8D4"/>
                <path d="M2 34a30 9 0 0 0 60 0" fill="none" stroke="#F9A8D4" stroke-width="3" transform="rotate(-18 32 32)"/>
            </svg>',
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BRIGHT SPACE: platform belajar interaktif bertema luar angkasa untuk siswa Sekolah Dasar.">
    <meta name="theme-color" content="#E0F2FE">
    <title><?= e($pageTitle) ?></title>

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
        @keyframes planet-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50%      { transform: translateY(-4px) rotate(4deg); }
        }
        .logo-link:hover .rocket-icon,
        .logo-link:focus-visible .rocket-icon {
            animation: rocket-wiggle 0.6s ease-in-out infinite;
        }
        .coin-pop { animation: coin-pop 0.4s ease-out; }
        .planet-card.is-open:hover .planet-icon,
        .planet-card.is-open:focus-within .planet-icon {
            animation: planet-float 1.4s ease-in-out infinite;
        }

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
            <a href="index.php" class="logo-link flex items-center gap-2 rounded-xl focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent" aria-label="<?= e($appName) ?> - Beranda">
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

        <!-- Judul Bagian Misi -->
        <section aria-labelledby="judul-misi" class="mb-8 text-center">
            <h1 id="judul-misi" class="text-3xl sm:text-4xl font-black">Pilih Planet Petualanganmu!</h1>
            <p class="mt-2 text-base sm:text-lg font-bold opacity-80">
                Selesaikan misi di setiap planet untuk mengumpulkan Koin Bintang ⭐
            </p>
        </section>

        <!-- Grid Kartu Planet: 1 kolom (HP), 2 kolom (Tablet), 4 kolom (Desktop) -->
        <section aria-label="Daftar planet belajar" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($planets as $p): ?>
                <?php $terbuka = ($p['status'] === 'Terbuka'); ?>
                <article class="planet-card <?= $terbuka ? 'is-open hover:-translate-y-2 hover:shadow-xl' : 'opacity-90' ?> relative flex flex-col bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300"
                         aria-labelledby="planet-<?= e($p['id']) ?>">

                    <!-- Badge Poin (pojok atas kartu) -->
                    <div class="absolute top-3 right-3 flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-2.5 py-0.5 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                     fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-extrabold leading-none">+<?= (int) $p['poin'] ?> Koin</span>
                    </div>

                    <!-- Ikon Planet SVG -->
                    <div class="planet-icon w-24 h-24 mx-auto mt-6 mb-3">
                        <?= $p['ikon'] ?>
                    </div>

                    <!-- Nama & Deskripsi -->
                    <h2 id="planet-<?= e($p['id']) ?>" class="text-xl font-black text-center leading-tight"><?= e($p['nama']) ?></h2>
                    <p class="mt-1 text-sm sm:text-base font-bold text-center opacity-80"><?= e($p['deskripsi']) ?></p>

                    <!-- Status Planet -->
                    <div class="mt-3 flex justify-center">
                        <?php if ($terbuka): ?>
                            <span class="inline-flex items-center gap-1 bg-white/70 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                                <?= e($p['status']) ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 bg-white/70 text-slate-600 text-xs font-extrabold px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="5" y="11" width="14" height="9" rx="2"/>
                                    <path d="M8 11V8a4 4 0 0 1 8 0v3"/>
                                </svg>
                                <?= e($p['status']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-auto pt-5">
                        <?php if ($terbuka): ?>
                            <button type="button"
                                    class="btn-jelajah w-full text-white font-extrabold text-sm sm:text-base py-2.5 rounded-full shadow transition-all duration-200 hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-4 <?= e($p['tombol']) ?>"
                                    data-planet="<?= e($p['nama']) ?>">
                                Jelajahi Planet 🚀
                            </button>
                        <?php else: ?>
                            <button type="button" disabled aria-disabled="true"
                                    class="w-full bg-gray-200 text-gray-500 font-extrabold text-sm sm:text-base py-2.5 rounded-full cursor-not-allowed">
                                Buka Level <?= (int) $p['level'] ?> 🔒
                            </button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <!-- ===================== TOAST NOTIFIKASI ===================== -->
    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-50 hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" role="status" aria-live="polite"></div>

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
                }, 2400);
            };

            // Tombol Toko Bintang
            document.getElementById('btn-toko-bintang').addEventListener('click', function () {
                window.showToast('Toko Bintang segera dibuka! Kumpulkan Star Coins dulu ya!');
            });

            // Tombol "Jelajahi Planet" (event delegation: 1 listener untuk semua kartu)
            document.getElementById('main-content').addEventListener('click', function (event) {
                var tombol = event.target.closest('.btn-jelajah');
                if (!tombol) { return; }
                window.showToast('Bersiap menuju ' + tombol.getAttribute('data-planet') + '... 🚀');
            });
        })();
    </script>
</body>
</html>