<?php
// ============================================================
// BRIGHT SPACE - EdTech Platform untuk Siswa SD
// Sub-Tahap 2E: Toko Virtual (Penukaran Koin Bintang)
// Stack: PHP Native + HTML5 + Tailwind (CDN) + Vanilla JS
// ============================================================

$appName   = 'BRIGHT SPACE';
$pageTitle = 'BRIGHT SPACE - Petualangan Belajar di Luar Angkasa';
$starCoins = 120; // Poin awal siswa

// Fungsi bantu: escape teks agar aman ditampilkan di HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// ------------------------------------------------------------
// SVG Maskot Arazka (satu sumber, dipakai di widget & modal).
// Ekspresi diganti lewat atribut data-mood pada pembungkusnya.
// ------------------------------------------------------------
$arazkaSvg = <<<'SVG'
<svg viewBox="0 0 100 100" class="arazka-svg w-full h-full" aria-hidden="true" focusable="false">
    <!-- Badan -->
    <rect x="31" y="74" width="38" height="20" rx="10" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2.5"/>
    <polygon points="50,78 52,83 57,83.5 53.5,87 54.5,92 50,89.5 45.5,92 46.5,87 43,83.5 48,83" fill="#FCD34D"/>
    <!-- Antena -->
    <line x1="50" y1="22" x2="50" y2="13" stroke="#1E1B4B" stroke-width="3" stroke-linecap="round"/>
    <polygon points="50,1 52,5.5 57,6 53.3,9.2 54.4,14 50,11.5 45.6,14 46.7,9.2 43,6 48,5.5" fill="#FCD34D" stroke="#B45309" stroke-width="1"/>
    <!-- Helm -->
    <circle cx="50" cy="48" r="29" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="2.5"/>
    <circle cx="21" cy="48" r="5" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2"/>
    <circle cx="79" cy="48" r="5" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2"/>
    <!-- Kaca helm -->
    <rect x="22" y="34" width="56" height="30" rx="15" fill="#312E81" stroke="#1E1B4B" stroke-width="2"/>
    <path d="M30 39q4-3 9-3" fill="none" stroke="#FFFFFF" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round"/>
    <circle cx="31" cy="57" r="3" fill="#F9A8D4" fill-opacity="0.75"/>
    <circle cx="69" cy="57" r="3" fill="#F9A8D4" fill-opacity="0.75"/>
    <!-- Wajah (diatur lewat CSS berdasarkan data-mood) -->
    <g class="m-normal">
        <circle cx="40" cy="47" r="4.2" fill="#7DD3FC"/>
        <circle cx="60" cy="47" r="4.2" fill="#7DD3FC"/>
    </g>
    <g class="m-senang" fill="none" stroke="#7DD3FC" stroke-width="3" stroke-linecap="round">
        <path d="M35 50q5-8 10 0"/>
        <path d="M55 50q5-8 10 0"/>
    </g>
    <g class="m-brow" fill="none" stroke="#7DD3FC" stroke-width="2.5" stroke-linecap="round">
        <path d="M35 39l10 3"/>
        <path d="M65 39l-10 3"/>
    </g>
    <g class="m-senyum" fill="none" stroke="#7DD3FC" stroke-width="2.5" stroke-linecap="round">
        <path d="M44 56q6 5 12 0"/>
    </g>
    <g class="m-terbuka">
        <path d="M43 55q7 11 14 0z" fill="#F9A8D4"/>
    </g>
</svg>
SVG;

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

// ------------------------------------------------------------
// Bank Soal Kuis (kunci = id planet).
// 'umpan_salah' = petunjuk Arazka yang menyesuaikan dengan
// jawaban keliru yang dipilih siswa (tanpa membocorkan jawaban).
// ------------------------------------------------------------
$kuis = [
    'matematika' => [
        'pertanyaan'  => 'Roket Kapten membawa 8 bintang. Di perjalanan, Kapten menemukan 5 bintang lagi. Berapa jumlah bintang sekarang?',
        'pilihan'     => ['A' => '12 bintang', 'B' => '14 bintang', 'C' => '13 bintang'],
        'jawaban'     => 'C',
        'penjelasan'  => '8 ditambah 5 sama dengan 13.',
        'umpan_salah' => [
            'A' => 'Hmm, 12 itu masih kurang satu langkah. Coba hitung maju 5 kali mulai dari angka 8, boleh pakai jari!',
            'B' => '14 itu terlalu banyak, sepertinya kamu menghitung satu langkah terlalu jauh. Coba hitung maju 5 kali dari angka 8 lagi.',
        ],
    ],
    'sains' => [
        'pertanyaan'  => 'Planet manakah yang dijuluki "Planet Merah"?',
        'pilihan'     => ['A' => 'Mars', 'B' => 'Venus', 'C' => 'Saturnus'],
        'jawaban'     => 'A',
        'penjelasan'  => 'Mars tampak merah karena tanahnya banyak mengandung besi berkarat.',
        'umpan_salah' => [
            'B' => 'Venus memang terang sekali, tetapi warnanya kuning keputihan karena awannya tebal. Cari planet yang tanahnya berkarat ya!',
            'C' => 'Saturnus terkenal dengan cincinnya yang indah, bukan karena warna merahnya. Coba ingat planet tetangga Bumi yang berkarat!',
        ],
    ],
    'bahasa' => [
        'pertanyaan'  => 'Kata yang berlawanan arti dengan "besar" adalah ...',
        'pilihan'     => ['A' => 'tinggi', 'B' => 'kecil', 'C' => 'panjang'],
        'jawaban'     => 'B',
        'penjelasan'  => 'Lawan kata (antonim) dari "besar" adalah "kecil".',
        'umpan_salah' => [
            'A' => 'Lawan kata "tinggi" adalah "rendah". Kita sedang mencari lawan kata untuk ukuran "besar" ya!',
            'C' => 'Lawan kata "panjang" adalah "pendek". Coba cari kata yang berlawanan dengan "besar", bukan "panjang"!',
        ],
    ],
];

// ------------------------------------------------------------
// Daftar Item Toko Bintang (ikon SVG inline, harga dalam koin)
// ------------------------------------------------------------
$items = [
    [
        'id'        => 'kacamata-bintang',
        'nama'      => 'Kacamata Bintang',
        'deskripsi' => 'Lihat bintang jadi lebih berkilau!',
        'harga'     => 40,
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <circle cx="32" cy="32" r="30" fill="#E0F2FE"/>
                <path d="M6 30h4M54 30h4" stroke="#1E1B4B" stroke-width="3" stroke-linecap="round"/>
                <rect x="10" y="22" width="19" height="16" rx="6" fill="#FDE68A" stroke="#1E1B4B" stroke-width="2.5"/>
                <rect x="35" y="22" width="19" height="16" rx="6" fill="#FDE68A" stroke="#1E1B4B" stroke-width="2.5"/>
                <path d="M29 30h6" stroke="#1E1B4B" stroke-width="2.5" stroke-linecap="round"/>
                <polygon points="19.5,26 20.8,29 24,29.2 21.5,31.2 22.4,34.4 19.5,32.6 16.6,34.4 17.5,31.2 15,29.2 18.2,29" fill="#F59E0B"/>
                <polygon points="44.5,26 45.8,29 49,29.2 46.5,31.2 47.4,34.4 44.5,32.6 41.6,34.4 42.5,31.2 40,29.2 43.2,29" fill="#F59E0B"/>
            </svg>',
    ],
    [
        'id'        => 'topi-astronaut',
        'nama'      => 'Topi Astronaut Pastel',
        'deskripsi' => 'Topi lucu untuk Kapten cilik!',
        'harga'     => 50,
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <circle cx="32" cy="32" r="30" fill="#EDE9FE"/>
                <line x1="32" y1="20" x2="32" y2="14" stroke="#1E1B4B" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="32" cy="12" r="3.5" fill="#FCD34D" stroke="#B45309" stroke-width="1.5"/>
                <path d="M12 40a20 20 0 0 1 40 0z" fill="#C4B5FD" stroke="#1E1B4B" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M18 38a14 14 0 0 1 28 0z" fill="#7DD3FC" stroke="#1E1B4B" stroke-width="2" stroke-linejoin="round"/>
                <path d="M23 32q3-5 8-5" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/>
                <rect x="9" y="38" width="46" height="8" rx="4" fill="#F9A8D4" stroke="#1E1B4B" stroke-width="2.5"/>
            </svg>',
    ],
    [
        'id'        => 'planet-pelangi',
        'nama'      => 'Stiker Planet Pelangi',
        'deskripsi' => 'Planet warna-warni yang ceria!',
        'harga'     => 60,
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <defs><clipPath id="klip-pelangi"><circle cx="32" cy="32" r="18"/></clipPath></defs>
                <circle cx="32" cy="32" r="30" fill="#EDE9FE"/>
                <g clip-path="url(#klip-pelangi)">
                    <rect x="12" y="14" width="40" height="6" fill="#F87171"/>
                    <rect x="12" y="20" width="40" height="6" fill="#FB923C"/>
                    <rect x="12" y="26" width="40" height="6" fill="#FDE047"/>
                    <rect x="12" y="32" width="40" height="6" fill="#4ADE80"/>
                    <rect x="12" y="38" width="40" height="6" fill="#60A5FA"/>
                    <rect x="12" y="44" width="40" height="6" fill="#A78BFA"/>
                </g>
                <circle cx="32" cy="32" r="18" fill="none" stroke="#1E1B4B" stroke-width="2.5"/>
                <circle cx="25" cy="25" r="3" fill="#FFFFFF" fill-opacity="0.7"/>
            </svg>',
    ],
    [
        'id'        => 'roket-neon',
        'nama'      => 'Roket Neon',
        'deskripsi' => 'Roket bercahaya yang melesat cepat!',
        'harga'     => 80,
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <circle cx="32" cy="32" r="30" fill="#312E81"/>
                <path d="M22 36l-7 8 1 6 8-5z" fill="#F472B6"/>
                <path d="M42 36l7 8-1 6-8-5z" fill="#F472B6"/>
                <path d="M32 10c7 5 10 13 10 21 0 5-1 9-2.5 13h-15C23 40 22 36 22 31c0-8 3-16 10-21z" fill="#22D3EE" stroke="#A5F3FC" stroke-width="2" stroke-linejoin="round"/>
                <circle cx="32" cy="27" r="5" fill="#1E1B4B" stroke="#F0ABFC" stroke-width="2"/>
                <path d="M27 46c-1 4 0 8 5 11 5-3 6-7 5-11z" fill="#FDE047" stroke="#FB923C" stroke-width="1.5" stroke-linejoin="round"/>
            </svg>',
    ],
    [
        'id'        => 'badge-emas',
        'nama'      => 'Warna Badge Emas',
        'deskripsi' => 'Badge Koinmu jadi berkilau emas!',
        'harga'     => 100,
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <circle cx="32" cy="32" r="30" fill="#FEF3C7"/>
                <polygon points="22,8 30,8 34,26 26,26" fill="#A78BFA"/>
                <polygon points="42,8 34,8 30,26 38,26" fill="#818CF8"/>
                <circle cx="32" cy="39" r="16" fill="#FBBF24" stroke="#B45309" stroke-width="2.5"/>
                <circle cx="32" cy="39" r="11" fill="#FCD34D" stroke="#F59E0B" stroke-width="1.5"/>
                <polygon points="32,31 34.4,36.4 40.2,37 35.8,40.8 37.1,46.5 32,43.5 26.9,46.5 28.2,40.8 23.8,37 29.6,36.4" fill="#FFFFFF" stroke="#B45309" stroke-width="1" stroke-linejoin="round"/>
            </svg>',
    ],
    [
        'id'        => 'mahkota-galaksi',
        'nama'      => 'Mahkota Galaksi',
        'deskripsi' => 'Mahkota spesial untuk Kapten hebat!',
        'harga'     => 150,
        'ikon'      => '<svg viewBox="0 0 64 64" class="w-full h-full" aria-hidden="true">
                <circle cx="32" cy="32" r="30" fill="#FEF3C7"/>
                <path d="M12 44l-3-22 12 10 11-16 11 16 12-10-3 22z" fill="#FCD34D" stroke="#B45309" stroke-width="2.5" stroke-linejoin="round"/>
                <rect x="12" y="44" width="40" height="7" rx="2" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2"/>
                <circle cx="9" cy="21" r="3" fill="#F472B6"/>
                <circle cx="55" cy="21" r="3" fill="#F472B6"/>
                <circle cx="32" cy="15" r="3" fill="#22D3EE"/>
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
        /* Animasi ringan (hanya transform/opacity) agar hemat CPU/GPU */
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
        @keyframes modal-in {
            from { opacity: 0; transform: translateY(14px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes arazka-lompat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            30%      { transform: translateY(-10px) rotate(-6deg); }
            60%      { transform: translateY(0) rotate(5deg); }
        }
        @keyframes arazka-miring {
            0%, 100% { transform: rotate(0deg); }
            50%      { transform: rotate(-7deg); }
        }
        @keyframes percik {
            from { opacity: 1; transform: translate(0, 0) scale(0.4); }
            to   { opacity: 0; transform: translate(var(--dx), var(--dy)) scale(1.1); }
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
        .modal-panel { animation: modal-in 0.25s ease-out; }

        /* ---------- Maskot Arazka: ekspresi berdasarkan data-mood ---------- */
        .arazka-wrap .m-senang,
        .arazka-wrap .m-brow,
        .arazka-wrap .m-terbuka { display: none; }

        .arazka-wrap[data-mood="selebrasi"] .m-normal,
        .arazka-wrap[data-mood="selebrasi"] .m-senyum { display: none; }
        .arazka-wrap[data-mood="selebrasi"] .m-senang,
        .arazka-wrap[data-mood="selebrasi"] .m-terbuka { display: inline; }
        .arazka-wrap[data-mood="semangat"] .m-brow { display: inline; }

        /* Animasi terbatas (beberapa kali saja), bukan terus-menerus */
        .arazka-wrap[data-mood="selebrasi"] .arazka-svg { animation: arazka-lompat 0.7s ease-in-out 3; }
        .arazka-wrap[data-mood="semangat"] .arazka-svg  { animation: arazka-miring 0.8s ease-in-out 2; }

        /* Percikan bintang saat berhasil */
        .percik {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 14px;
            height: 14px;
            margin: -7px 0 0 -7px;
            animation: percik 0.9s ease-out forwards;
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
                <div id="star-coin-badge" class="flex items-center gap-1.5 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-2 pr-3 py-1 shadow-sm" role="status" aria-live="polite" aria-label="Jumlah Star Coins">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 shrink-0" aria-hidden="true">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                 fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                    </svg>
                    <span id="star-coin-count" class="font-extrabold text-base sm:text-lg leading-none"><?= (int) $starCoins ?></span>
                    <span class="hidden sm:inline font-bold text-sm leading-none">Star Coins</span>
                </div>

                <!-- Tombol Toko Bintang -->
                <button type="button" id="btn-toko-bintang" aria-haspopup="dialog"
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
        <section id="grid-planet" aria-label="Daftar planet belajar" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($planets as $p): ?>
                <?php $terbuka = ($p['status'] === 'Terbuka'); ?>
                <article class="planet-card <?= $terbuka ? 'is-open hover:-translate-y-2 hover:shadow-xl' : 'opacity-90' ?> relative flex flex-col cursor-pointer bg-gradient-to-br <?= e($p['gradien']) ?> border-2 <?= e($p['border']) ?> rounded-3xl p-5 shadow-md transition-all duration-300"
                         aria-labelledby="planet-<?= e($p['id']) ?>"
                         data-planet-id="<?= e($p['id']) ?>"
                         data-planet-nama="<?= e($p['nama']) ?>"
                         data-poin="<?= (int) $p['poin'] ?>"
                         data-level="<?= (int) $p['level'] ?>">

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
                                <span class="status-teks"><?= e($p['status']) ?></span>
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 bg-white/70 text-slate-600 text-xs font-extrabold px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="5" y="11" width="14" height="9" rx="2"/>
                                    <path d="M8 11V8a4 4 0 0 1 8 0v3"/>
                                </svg>
                                <span class="status-teks"><?= e($p['status']) ?></span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-auto pt-5">
                        <?php if ($terbuka): ?>
                            <button type="button"
                                    class="btn-jelajah w-full text-white font-extrabold text-sm sm:text-base py-2.5 rounded-full shadow transition-all duration-200 hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-4 <?= e($p['tombol']) ?>">
                                Jelajahi Planet 🚀
                            </button>
                        <?php else: ?>
                            <!-- Tidak memakai atribut disabled agar klik tetap memunculkan pemberitahuan ramah -->
                            <button type="button" aria-disabled="true"
                                    class="btn-terkunci w-full bg-gray-200 text-gray-500 font-extrabold text-sm sm:text-base py-2.5 rounded-full cursor-not-allowed focus:outline-none focus-visible:ring-4 focus-visible:ring-gray-300">
                                Buka Level <?= (int) $p['level'] ?> 🔒
                            </button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <!-- ===================== WIDGET ARAZKA (MENGAMBANG) ===================== -->
    <aside id="arazka-widget" class="fixed bottom-4 right-4 z-40 flex items-end gap-2" aria-label="Arazka, mentor AI BRIGHT SPACE">
        <div id="arazka-bubble" class="hidden relative max-w-[14rem] sm:max-w-xs bg-white border-2 border-violet-300 rounded-2xl px-3 py-2 shadow-lg" role="status" aria-live="polite">
            <p class="text-xs font-extrabold text-violet-600">Arazka</p>
            <p id="arazka-teks" class="text-sm font-bold leading-snug"></p>
            <!-- Ekor balon bicara -->
            <span class="absolute -right-1.5 bottom-5 w-3 h-3 rotate-45 bg-white border-t-2 border-r-2 border-violet-300"></span>
        </div>
        <button type="button" id="arazka-btn" aria-expanded="false" aria-label="Ngobrol dengan Arazka, mentor AI-mu"
                class="arazka-wrap w-16 h-16 sm:w-20 sm:h-20 shrink-0 p-1 rounded-full bg-cosmic-card border-4 border-cosmic-accent shadow-lg transition-transform duration-150 hover:scale-110 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
            <?= $arazkaSvg ?>
        </button>
    </aside>

    <!-- ===================== MODAL KUIS ===================== -->
    <div id="kuis-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-cosmic-text/60"
         role="dialog" aria-modal="true" aria-labelledby="kuis-judul">
        <div id="kuis-panel" class="modal-panel relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-cosmic-card border-4 border-cosmic-accent rounded-3xl shadow-2xl p-5 sm:p-6">

            <!-- Tombol Tutup (X) -->
            <button type="button" id="kuis-tutup" aria-label="Tutup kuis"
                    class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-white/80 text-cosmic-text transition-transform duration-150 hover:scale-110 hover:bg-white focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>

            <!-- Kepala Modal: ikon planet + judul -->
            <div class="flex items-center gap-3 pr-10">
                <div id="kuis-ikon" class="w-16 h-16 shrink-0"></div>
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-wide text-violet-600">Misi Kuis</p>
                    <h2 id="kuis-judul" class="text-xl sm:text-2xl font-black leading-tight"></h2>
                </div>
            </div>

            <!-- Hadiah -->
            <div class="mt-3 inline-flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-3 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                             fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm font-extrabold">Hadiah: <span id="kuis-hadiah"></span></span>
            </div>

            <!-- Pertanyaan -->
            <p id="kuis-pertanyaan" class="mt-4 text-lg sm:text-xl font-extrabold leading-snug"></p>

            <!-- Pilihan Jawaban (A, B, C) dibuat oleh JavaScript -->
            <div id="kuis-pilihan" class="mt-4 grid gap-3"></div>

            <!-- Arazka di dalam modal: sapaan, petunjuk, dan apresiasi -->
            <div id="kuis-feedback" class="mt-4 flex items-start gap-3" role="status" aria-live="polite">
                <div id="arazka-modal" class="arazka-wrap relative w-16 h-16 shrink-0">
                    <?= $arazkaSvg ?>
                    <div id="kuis-percik" class="absolute inset-0 pointer-events-none"></div>
                </div>
                <div id="kuis-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                    <!-- Ekor balon bicara -->
                    <span class="absolute -left-1.5 top-5 w-3 h-3 rotate-45 bg-inherit border-l-2 border-b-2 border-inherit"></span>
                    <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                    <p id="kuis-fb-judul" class="text-base font-black leading-snug"></p>
                    <p id="kuis-fb-teks" class="mt-1 text-sm font-bold leading-snug"></p>
                </div>
            </div>

            <!-- Tombol Lanjut (muncul setelah jawaban benar) -->
            <button type="button" id="kuis-lanjut"
                    class="hidden w-full mt-4 bg-violet-500 text-white font-extrabold text-base py-3 rounded-full shadow transition-all duration-200 hover:scale-105 hover:bg-violet-600 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
                Tutup / Lanjut Misi 🚀
            </button>
        </div>
    </div>

    <!-- ===================== MODAL TOKO BINTANG ===================== -->
    <div id="toko-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-cosmic-text/60"
         role="dialog" aria-modal="true" aria-labelledby="toko-judul">
        <div class="modal-panel relative flex flex-col w-full max-w-2xl max-h-[90vh] overflow-hidden bg-cosmic-card border-4 border-cosmic-accent rounded-3xl shadow-2xl p-5 sm:p-6">

            <!-- Tombol Tutup (X) -->
            <button type="button" id="toko-tutup" aria-label="Tutup toko"
                    class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-white/80 text-cosmic-text transition-transform duration-150 hover:scale-110 hover:bg-white focus:outline-none focus-visible:ring-4 focus-visible:ring-cosmic-accent">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>

            <!-- Bagian atas (tetap terlihat): judul, saldo, dan Arazka -->
            <div class="shrink-0">
                <div class="flex items-center gap-3 pr-10">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center rounded-full bg-cosmic-accent shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 9l1.5-5h15L21 9"/>
                            <path d="M3 9c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3"/>
                            <path d="M5 12v8h14v-8"/>
                            <path d="M10 20v-4h4v4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wide text-violet-600">Tukar Koin Bintang</p>
                        <h2 id="toko-judul" class="text-xl sm:text-2xl font-black leading-tight">Toko Bintang</h2>
                    </div>
                </div>

                <!-- Saldo koin -->
                <div class="mt-3 inline-flex items-center gap-1.5 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1.5 pr-3 py-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" aria-hidden="true">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                 fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-sm font-extrabold">Koinmu: <span id="toko-saldo"><?= (int) $starCoins ?></span></span>
                </div>

                <!-- Arazka di toko -->
                <div class="mt-3 flex items-start gap-3" role="status" aria-live="polite">
                    <div id="arazka-toko" class="arazka-wrap relative w-16 h-16 shrink-0">
                        <?= $arazkaSvg ?>
                        <div id="toko-percik" class="absolute inset-0 pointer-events-none"></div>
                    </div>
                    <div id="toko-bubble" class="relative flex-1 rounded-2xl border-2 p-3 bg-violet-50 border-violet-200">
                        <span class="absolute -left-1.5 top-5 w-3 h-3 rotate-45 bg-inherit border-l-2 border-b-2 border-inherit"></span>
                        <p class="text-xs font-extrabold text-violet-600">Arazka</p>
                        <p id="toko-fb-judul" class="text-base font-black leading-snug"></p>
                        <p id="toko-fb-teks" class="mt-1 text-sm font-bold leading-snug"></p>
                    </div>
                </div>
            </div>

            <!-- Grid item toko (bagian ini yang bisa digulir) -->
            <div class="mt-4 flex-1 min-h-0 overflow-y-auto pr-1">
                <div id="toko-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <?php foreach ($items as $it): ?>
                        <article class="item-toko flex flex-col text-center bg-white/80 border-2 border-violet-200 rounded-2xl p-3 transition-colors duration-200"
                                 data-item-id="<?= e($it['id']) ?>"
                                 data-harga="<?= (int) $it['harga'] ?>"
                                 data-nama="<?= e($it['nama']) ?>">
                            <div class="w-16 h-16 mx-auto"><?= $it['ikon'] ?></div>
                            <h3 class="mt-2 text-sm sm:text-base font-black leading-tight"><?= e($it['nama']) ?></h3>
                            <p class="mt-1 text-xs font-bold opacity-75 leading-snug"><?= e($it['deskripsi']) ?></p>

                            <!-- Harga -->
                            <div class="mt-2 mx-auto inline-flex items-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full pl-1 pr-2.5 py-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 shrink-0" aria-hidden="true">
                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                                             fill="#FCD34D" stroke="#B45309" stroke-width="1.2" stroke-linejoin="round"/>
                                </svg>
                                <span class="text-sm font-extrabold leading-none"><?= (int) $it['harga'] ?> Koin</span>
                            </div>

                            <!-- Status -->
                            <span class="item-status mt-1 text-xs font-extrabold text-violet-600">Tersedia</span>

                            <!-- Tombol Beli -->
                            <div class="mt-auto pt-2">
                                <button type="button"
                                        class="btn-beli w-full bg-violet-500 text-white font-extrabold text-sm py-2 rounded-full shadow transition-all duration-150 hover:scale-105 hover:bg-violet-600 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">
                                    Beli
                                </button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== TOAST NOTIFIKASI ===================== -->
    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" role="status" aria-live="polite"></div>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 pt-4 pb-24 md:pb-4 text-center text-xs sm:text-sm font-bold">
            Optimized for Low-Spec School Hardware (RAM 2GB-4GB) | BRIGHT SPACE EdTech
        </div>
    </footer>

    <!-- ===================== VANILLA JS ===================== -->
    <script>
        (function () {
            'use strict';

            // ---------- Data kuis dari PHP ----------
            var DATA_KUIS = <?= json_encode($kuis, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

            // ---------- Ikon & teks Arazka ----------
            var IKON_BINTANG_KECIL =
                '<svg viewBox="0 0 24 24" class="w-full h-full" aria-hidden="true">' +
                '<polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.5" stroke-linejoin="round"/></svg>';

            var PEMBUKA_SALAH = [
                'Hampir tepat! 💪',
                'Ayo coba lagi, kamu pasti bisa! 🌈',
                'Semangat, Kapten! Satu langkah lagi! 🚀'
            ];

            var JUDUL_BELI = [
                'Yeay, berhasil! 🎉',
                'Keren sekali, Kapten! 🌟',
                'Hore, item baru! 🚀'
            ];

            var TIPS = [
                'Klik kartu planet yang terbuka untuk memulai kuis, ya Kapten! 🚀',
                'Setiap jawaban benar memberimu Koin Bintang. Yuk kumpulkan sebanyak-banyaknya! ⭐',
                'Kalau jawabanmu belum tepat, jangan sedih. Aku akan memberi petunjuk! 💡',
                'Tukarkan Koin Bintangmu di Toko Bintang untuk mendapatkan item keren! 🛍️',
                'Planet Seni & Coding terbuka di Level 5. Semangat naik level! 🔒'
            ];

            // ---------- Kelas Tailwind untuk status item toko ----------
            var KELAS_KARTU_BELI   = 'item-toko flex flex-col text-center bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-3 transition-colors duration-200';
            var KELAS_STATUS_BELI  = 'item-status mt-1 text-xs font-extrabold text-emerald-700';
            var KELAS_TOMBOL_BELI  = 'btn-beli w-full bg-emerald-200 text-emerald-800 font-extrabold text-sm py-2 rounded-full cursor-default';

            // ---------- Elemen DOM umum ----------
            var coinEl     = document.getElementById('star-coin-count');
            var coinBadge  = document.getElementById('star-coin-badge');
            var toastEl    = document.getElementById('toast');
            var gridEl     = document.getElementById('grid-planet');

            // ---------- Elemen modal kuis ----------
            var modal      = document.getElementById('kuis-modal');
            var ikonEl     = document.getElementById('kuis-ikon');
            var judulEl    = document.getElementById('kuis-judul');
            var hadiahEl   = document.getElementById('kuis-hadiah');
            var tanyaEl    = document.getElementById('kuis-pertanyaan');
            var pilihanEl  = document.getElementById('kuis-pilihan');
            var fbEl       = document.getElementById('kuis-feedback');
            var lanjutBtn  = document.getElementById('kuis-lanjut');
            var tutupBtn   = document.getElementById('kuis-tutup');

            // ---------- Elemen modal toko ----------
            var tokoModal  = document.getElementById('toko-modal');
            var tokoGrid   = document.getElementById('toko-grid');
            var tokoSaldo  = document.getElementById('toko-saldo');
            var tokoTutup  = document.getElementById('toko-tutup');
            var tokoBtn    = document.getElementById('btn-toko-bintang');

            // ---------- Elemen widget Arazka ----------
            var wBtn       = document.getElementById('arazka-btn');
            var wBubble    = document.getElementById('arazka-bubble');
            var wTeks      = document.getElementById('arazka-teks');

            // ---------- Kumpulan elemen Arazka di tiap modal ----------
            var araKuis = {
                avatar: document.getElementById('arazka-modal'),
                percik: document.getElementById('kuis-percik'),
                bubble: document.getElementById('kuis-bubble'),
                judul:  document.getElementById('kuis-fb-judul'),
                teks:   document.getElementById('kuis-fb-teks')
            };
            var araToko = {
                avatar: document.getElementById('arazka-toko'),
                percik: document.getElementById('toko-percik'),
                bubble: document.getElementById('toko-bubble'),
                judul:  document.getElementById('toko-fb-judul'),
                teks:   document.getElementById('toko-fb-teks')
            };

            // ---------- State ----------
            var koinTotal  = parseInt(coinEl.textContent, 10) || 0; // nilai sebenarnya
            var koinTampil = koinTotal;                              // nilai yang sedang tampil
            var timerKoin  = null;
            var timerToast = null;
            var timerWidget = null;
            var indeksTips = 0;
            var selesai    = {};   // planet yang hadiahnya sudah diambil
            var dimiliki   = {};   // item toko yang sudah dibeli
            var modalAktif = null; // modal yang sedang terbuka
            var planetAktif = null;
            var soalAktif   = null;
            var sudahBenar  = false;
            var jumlahSalah = 0;
            var dapatKoinBaru = false;
            var belanjaBaru = false;
            var fokusSebelum = null;

            // ---------- Util: ganti ekspresi Arazka (sekaligus memulai ulang animasi) ----------
            function setMood(el, mood) {
                el.removeAttribute('data-mood');
                void el.offsetWidth;
                if (mood) { el.setAttribute('data-mood', mood); }
            }

            // ---------- Sistem Star Coins ----------
            function tampilSaldoToko() {
                tokoSaldo.textContent = koinTotal;
            }

            function denyutKoin() {
                coinBadge.classList.remove('coin-pop');
                void coinBadge.offsetWidth;
                coinBadge.classList.add('coin-pop');
            }

            // Animasi hitung naik/turun menuju koinTotal
            function animasiKoin() {
                clearInterval(timerKoin);
                if (koinTampil === koinTotal) { return; }
                var langkah = Math.max(1, Math.ceil(Math.abs(koinTotal - koinTampil) / 15));
                timerKoin = setInterval(function () {
                    var sisa  = koinTotal - koinTampil;
                    var arah  = sisa > 0 ? 1 : -1;
                    var gerak = Math.min(Math.abs(sisa), langkah);
                    koinTampil += arah * gerak;
                    coinEl.textContent = koinTampil;
                    if (koinTampil === koinTotal) {
                        clearInterval(timerKoin);
                        denyutKoin();
                    }
                }, 40);
            }

            window.setStarCoins = function (nilai) {
                clearInterval(timerKoin);
                koinTotal = Math.max(0, parseInt(nilai, 10) || 0);
                koinTampil = koinTotal;
                coinEl.textContent = koinTotal;
                tampilSaldoToko();
                denyutKoin();
            };

            window.getStarCoins = function () {
                return koinTotal;
            };

            // Menambah (positif) atau mengurangi (negatif) koin secara dinamis
            window.ubahStarCoins = function (selisih) {
                selisih = parseInt(selisih, 10) || 0;
                if (selisih === 0) { return; }
                koinTotal = Math.max(0, koinTotal + selisih);
                tampilSaldoToko();
                animasiKoin();
            };

            window.tambahStarCoins = function (jumlah) {
                jumlah = parseInt(jumlah, 10) || 0;
                if (jumlah > 0) { window.ubahStarCoins(jumlah); }
            };

            // ---------- Toast ----------
            window.showToast = function (pesan, durasi) {
                toastEl.textContent = pesan;
                toastEl.classList.remove('hidden');
                clearTimeout(timerToast);
                timerToast = setTimeout(function () {
                    toastEl.classList.add('hidden');
                }, durasi || 2400);
            };

            // ---------- Modal umum ----------
            function tampilkanModal(root) {
                modalAktif = root;
                root.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function sembunyikanModal(root) {
                root.classList.add('hidden');
                if (modalAktif === root) { modalAktif = null; }
                document.body.classList.remove('overflow-hidden');
                if (fokusSebelum && typeof fokusSebelum.focus === 'function') {
                    fokusSebelum.focus();
                }
            }

            // ---------- Widget Arazka (mengambang) ----------
            function arazkaDiam() {
                clearTimeout(timerWidget);
                wBubble.classList.add('hidden');
                wBtn.setAttribute('aria-expanded', 'false');
                setMood(wBtn, '');
            }

            function arazkaBicara(teks, mood, durasi) {
                wTeks.textContent = teks;
                wBubble.classList.remove('hidden');
                wBtn.setAttribute('aria-expanded', 'true');
                setMood(wBtn, mood || 'halo');
                clearTimeout(timerWidget);
                timerWidget = setTimeout(arazkaDiam, durasi || 7000);
            }

            function tipBerikut() {
                var totalTerbuka = gridEl.querySelectorAll('.planet-card.is-open').length;
                if (totalTerbuka > 0 && Object.keys(selesai).length >= totalTerbuka) {
                    return 'Wow, semua planet yang terbuka sudah kamu taklukkan! Kumpulkan koin terus sampai Planet Seni & Coding terbuka ya! 🏆';
                }
                var tip = TIPS[indeksTips % TIPS.length];
                indeksTips++;
                return tip;
            }

            // ---------- Arazka di dalam modal (kuis & toko) ----------
            function percikBintang(wadah) {
                var arah = [[-40, -30], [40, -30], [-46, 2], [46, 2], [-30, 34], [30, 34], [0, -44], [0, 42]];
                wadah.innerHTML = '';
                arah.forEach(function (a) {
                    var s = document.createElement('span');
                    s.className = 'percik';
                    s.style.setProperty('--dx', a[0] + 'px');
                    s.style.setProperty('--dy', a[1] + 'px');
                    s.innerHTML = IKON_BINTANG_KECIL;
                    wadah.appendChild(s);
                });
            }

            function bicara(ara, mood, tema, judul, teks, meriah) {
                var warna = {
                    netral:   'bg-violet-50 border-violet-200 text-cosmic-text',
                    sukses:   'bg-emerald-50 border-emerald-300 text-emerald-900',
                    semangat: 'bg-amber-50 border-amber-300 text-amber-900'
                };
                ara.bubble.className = 'relative flex-1 rounded-2xl border-2 p-3 ' + warna[tema];
                ara.judul.textContent = judul;
                ara.teks.textContent = teks;
                setMood(ara.avatar, mood);
                ara.percik.innerHTML = '';
                if (meriah) { percikBintang(ara.percik); }
            }

            // ============================================================
            //  KUIS
            // ============================================================
            function bukaKuis(kartu) {
                var id   = kartu.getAttribute('data-planet-id');
                var soal = DATA_KUIS[id];

                if (!soal) {
                    window.showToast('Soal untuk planet ini sedang disiapkan, tunggu ya, Kapten! 🚀', 3000);
                    return;
                }

                arazkaDiam();

                planetAktif = {
                    id:   id,
                    nama: kartu.getAttribute('data-planet-nama'),
                    poin: parseInt(kartu.getAttribute('data-poin'), 10) || 0,
                    ikon: kartu.querySelector('.planet-icon').innerHTML
                };
                soalAktif     = soal;
                sudahBenar    = false;
                jumlahSalah   = 0;
                dapatKoinBaru = false;
                fokusSebelum  = kartu.querySelector('button');

                ikonEl.innerHTML     = planetAktif.ikon;
                judulEl.textContent  = planetAktif.nama;
                hadiahEl.textContent = '+' + planetAktif.poin + ' Koin';
                tanyaEl.textContent  = soal.pertanyaan;

                // Bangun tombol pilihan A, B, C
                pilihanEl.innerHTML = '';
                Object.keys(soal.pilihan).forEach(function (huruf) {
                    var tombol = document.createElement('button');
                    tombol.type = 'button';
                    tombol.setAttribute('data-huruf', huruf);
                    tombol.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-white border-2 border-violet-200 rounded-2xl px-3 py-3 font-bold text-base transition-colors duration-150 hover:bg-cosmic-accent hover:border-amber-300 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300';

                    var bulat = document.createElement('span');
                    bulat.className = 'shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-violet-500 text-white font-black';
                    bulat.textContent = huruf;

                    var teks = document.createElement('span');
                    teks.textContent = soal.pilihan[huruf];

                    tombol.appendChild(bulat);
                    tombol.appendChild(teks);
                    pilihanEl.appendChild(tombol);
                });

                lanjutBtn.classList.add('hidden');

                // Arazka menyapa di awal kuis
                bicara(araKuis, 'halo', 'netral',
                    'Siap, Kapten? 🚀',
                    'Baca soalnya pelan-pelan, lalu pilih jawaban A, B, atau C. Aku di sini untuk membantumu!',
                    false);

                tampilkanModal(modal);

                var pertama = pilihanEl.querySelector('button');
                if (pertama) { pertama.focus(); }
            }

            function tutupKuis() {
                if (modal.classList.contains('hidden')) { return; }
                sembunyikanModal(modal);

                // Arazka merespons setelah modal ditutup
                var totalTerbuka = gridEl.querySelectorAll('.planet-card.is-open').length;
                if (sudahBenar && dapatKoinBaru) {
                    if (Object.keys(selesai).length >= totalTerbuka) {
                        arazkaBicara('Luar biasa, Kapten! Semua planet terbuka sudah kamu selesaikan. Koinmu sekarang ' + koinTotal + ' ⭐ Yuk belanja di Toko Bintang!', 'selebrasi', 9000);
                    } else {
                        arazkaBicara('Mantap, Kapten! Koinmu sekarang ' + koinTotal + ' ⭐ Yuk pilih planet berikutnya!', 'selebrasi', 8000);
                    }
                } else if (sudahBenar) {
                    arazkaBicara('Latihan yang bagus! Yuk coba planet lain untuk mengumpulkan lebih banyak koin. 🌟', 'halo', 7000);
                } else {
                    arazkaBicara('Tidak apa-apa, Kapten! Kamu boleh mencoba lagi kapan saja. Aku selalu siap membantu! 😊', 'semangat', 7000);
                }
            }

            function tandaiKartuSelesai(id) {
                var kartu = gridEl.querySelector('[data-planet-id="' + id + '"]');
                if (!kartu) { return; }
                var label = kartu.querySelector('.status-teks');
                if (label) { label.textContent = 'Selesai'; }
            }

            function pilihJawaban(tombol) {
                if (sudahBenar) { return; }
                var huruf = tombol.getAttribute('data-huruf');
                var semuaOpsi = pilihanEl.querySelectorAll('.opsi-jawaban');

                if (huruf === soalAktif.jawaban) {
                    // ----- JAWABAN BENAR -----
                    sudahBenar = true;
                    Array.prototype.forEach.call(semuaOpsi, function (opsi) {
                        opsi.disabled = true;
                        if (opsi === tombol) {
                            opsi.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-emerald-100 border-2 border-emerald-400 rounded-2xl px-3 py-3 font-bold text-base';
                        } else {
                            opsi.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-white/60 border-2 border-violet-100 rounded-2xl px-3 py-3 font-bold text-base opacity-60';
                        }
                    });

                    var pertamaKali = !selesai[planetAktif.id];
                    if (pertamaKali) {
                        selesai[planetAktif.id] = true;
                        dapatKoinBaru = true;
                        window.tambahStarCoins(planetAktif.poin);
                        tandaiKartuSelesai(planetAktif.id);

                        if (jumlahSalah === 0) {
                            bicara(araKuis, 'selebrasi', 'sukses',
                                'Hebat sekali, Kapten! 🌟',
                                'Kamu dapat +' + planetAktif.poin + ' Koin Bintang! Langsung benar, sempurna! ' + soalAktif.penjelasan,
                                true);
                        } else {
                            bicara(araKuis, 'selebrasi', 'sukses',
                                'Kamu pantang menyerah, keren! 🎉',
                                'Berkat usahamu, kamu dapat +' + planetAktif.poin + ' Koin Bintang! ' + soalAktif.penjelasan,
                                true);
                        }
                    } else {
                        bicara(araKuis, 'selebrasi', 'sukses',
                            'Benar lagi, keren! 🌟',
                            soalAktif.penjelasan + ' Koin misi ini sudah kamu dapatkan, yuk coba planet lainnya!',
                            true);
                    }

                    lanjutBtn.classList.remove('hidden');
                    fbEl.scrollIntoView({ block: 'nearest' });
                    lanjutBtn.focus();
                } else {
                    // ----- JAWABAN SALAH: Arazka memberi petunjuk adaptif -----
                    jumlahSalah++;
                    tombol.disabled = true;
                    tombol.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-rose-50 border-2 border-rose-300 rounded-2xl px-3 py-3 font-bold text-base opacity-70';

                    var petunjuk = (soalAktif.umpan_salah && soalAktif.umpan_salah[huruf]) ||
                                   'Coba baca soalnya sekali lagi dengan teliti ya, Kapten.';
                    var sisa = pilihanEl.querySelectorAll('.opsi-jawaban:not([disabled])').length;
                    var tambahan = (sisa === 1) ? ' Tinggal satu pilihan lagi, ayo semangat!' : '';
                    var pembuka = PEMBUKA_SALAH[(jumlahSalah - 1) % PEMBUKA_SALAH.length];

                    bicara(araKuis, 'semangat', 'semangat', pembuka, petunjuk + tambahan, false);
                    fbEl.scrollIntoView({ block: 'nearest' });

                    var berikut = pilihanEl.querySelector('.opsi-jawaban:not([disabled])');
                    if (berikut) { berikut.focus(); }
                }
            }

            // ============================================================
            //  TOKO BINTANG
            // ============================================================
            function semuaItem() {
                return tokoGrid.querySelectorAll('.item-toko');
            }

            // Harga item termurah yang belum dimiliki (null jika semua sudah dimiliki)
            function hargaTermurah() {
                var min = null;
                Array.prototype.forEach.call(semuaItem(), function (kartu) {
                    if (dimiliki[kartu.getAttribute('data-item-id')]) { return; }
                    var harga = parseInt(kartu.getAttribute('data-harga'), 10) || 0;
                    if (min === null || harga < min) { min = harga; }
                });
                return min;
            }

            function bukaToko() {
                arazkaDiam();
                fokusSebelum = tokoBtn;
                belanjaBaru = false;
                tampilSaldoToko();

                // Sapaan Arazka menyesuaikan jumlah koin
                var termurah = hargaTermurah();
                if (termurah === null) {
                    bicara(araToko, 'selebrasi', 'sukses',
                        'Wow, kolektor sejati! 🏆',
                        'Semua item di Toko Bintang sudah menjadi milikmu. Keren banget, Kapten!',
                        true);
                } else if (koinTotal < termurah) {
                    bicara(araToko, 'semangat', 'semangat',
                        'Selamat datang, Kapten! 🛍️',
                        'Koin Bintangmu belum cukup untuk membeli item apa pun. Yuk selesaikan kuis lagi untuk mengumpulkan koin!',
                        false);
                } else {
                    bicara(araToko, 'halo', 'netral',
                        'Selamat datang di Toko Bintang! 🛍️',
                        'Pilih item favoritmu, lalu tukarkan dengan Koin Bintang. Koinmu sekarang ' + koinTotal + ' ⭐',
                        false);
                }

                tampilkanModal(tokoModal);

                var pertama = tokoGrid.querySelector('.btn-beli:not([disabled])');
                (pertama || tokoTutup).focus();
            }

            function tutupToko() {
                if (tokoModal.classList.contains('hidden')) { return; }
                sembunyikanModal(tokoModal);

                if (belanjaBaru) {
                    arazkaBicara('Gaya sekali, Kapten! Sisa koinmu ' + koinTotal + ' ⭐ Kumpulkan lagi lewat kuis ya!', 'selebrasi', 8000);
                }
            }

            function tandaiItemTerbeli(kartu) {
                kartu.className = KELAS_KARTU_BELI;
                var status = kartu.querySelector('.item-status');
                status.textContent = 'Terbeli ✓';
                status.className = KELAS_STATUS_BELI;
                var tombol = kartu.querySelector('.btn-beli');
                tombol.disabled = true;
                tombol.textContent = 'Dimiliki ✓';
                tombol.className = KELAS_TOMBOL_BELI;
            }

            // Efek nyata dari item "Warna Badge Emas": badge koin di header berubah emas
            function pakaiBadgeEmas() {
                coinBadge.classList.remove('bg-cosmic-accent', 'border-amber-300');
                coinBadge.classList.add('bg-amber-200', 'border-amber-500', 'ring-2', 'ring-amber-400');
            }

            function beliItem(kartu) {
                var id    = kartu.getAttribute('data-item-id');
                var harga = parseInt(kartu.getAttribute('data-harga'), 10) || 0;
                var nama  = kartu.getAttribute('data-nama');

                if (dimiliki[id]) { return; }

                // ----- KOIN TIDAK CUKUP -----
                if (koinTotal < harga) {
                    var kurang = harga - koinTotal;
                    bicara(araToko, 'semangat', 'semangat',
                        'Sabar ya, Kapten! 💪',
                        'Koin Bintangmu belum cukup, yuk selesaikan kuis lagi! Kamu masih butuh ' + kurang + ' koin lagi untuk ' + nama + '.',
                        false);
                    return;
                }

                // ----- KOIN CUKUP: proses penukaran -----
                window.ubahStarCoins(-harga);
                dimiliki[id] = true;
                belanjaBaru = true;
                tandaiItemTerbeli(kartu);
                if (id === 'badge-emas') { pakaiBadgeEmas(); }

                var jumlahMilik = Object.keys(dimiliki).length;
                var judul = JUDUL_BELI[(jumlahMilik - 1) % JUDUL_BELI.length];
                var teks  = nama + ' sekarang jadi milikmu! Sisa Koin Bintangmu ' + koinTotal + ' ⭐';

                var termurah = hargaTermurah();
                if (termurah === null) {
                    teks += ' Wah, semua item sudah kamu miliki. Kamu kolektor luar angkasa sejati! 🏆';
                } else if (koinTotal < termurah) {
                    teks += ' Selesaikan kuis lagi untuk mengumpulkan koin dan membeli item berikutnya!';
                }

                bicara(araToko, 'selebrasi', 'sukses', judul, teks, true);

                var berikut = tokoGrid.querySelector('.btn-beli:not([disabled])');
                (berikut || tokoTutup).focus();
            }

            // ============================================================
            //  EVENT LISTENER
            // ============================================================
            // Klik kartu planet (event delegation: 1 listener untuk semua kartu)
            gridEl.addEventListener('click', function (event) {
                var kartu = event.target.closest('.planet-card');
                if (!kartu) { return; }

                if (kartu.classList.contains('is-open')) {
                    bukaKuis(kartu);
                } else {
                    var level = kartu.getAttribute('data-level') || '5';
                    window.showToast('Selesaikan misi sebelumnya dulu ya, Kapten! 🔒 Planet ini terbuka di Level ' + level + '.', 3200);
                }
            });

            // Klik pilihan jawaban
            pilihanEl.addEventListener('click', function (event) {
                var tombol = event.target.closest('.opsi-jawaban');
                if (tombol && !tombol.disabled) { pilihJawaban(tombol); }
            });

            // Klik tombol Beli di toko (event delegation)
            tokoGrid.addEventListener('click', function (event) {
                var tombol = event.target.closest('.btn-beli');
                if (!tombol || tombol.disabled) { return; }
                beliItem(tombol.closest('.item-toko'));
            });

            tokoBtn.addEventListener('click', bukaToko);
            tutupBtn.addEventListener('click', tutupKuis);
            lanjutBtn.addEventListener('click', tutupKuis);
            tokoTutup.addEventListener('click', tutupToko);

            // Klik area gelap di luar panel menutup modal
            modal.addEventListener('click', function (event) {
                if (event.target === modal) { tutupKuis(); }
            });
            tokoModal.addEventListener('click', function (event) {
                if (event.target === tokoModal) { tutupToko(); }
            });

            // Klik Arazka: tampilkan / sembunyikan balon bicara
            wBtn.addEventListener('click', function () {
                if (wBubble.classList.contains('hidden')) {
                    arazkaBicara(tipBerikut(), 'halo', 8000);
                } else {
                    arazkaDiam();
                }
            });

            // Keyboard: Esc menutup modal, Tab dikunci di dalam modal yang aktif
            document.addEventListener('keydown', function (event) {
                if (!modalAktif) { return; }

                if (event.key === 'Escape') {
                    if (modalAktif === modal) { tutupKuis(); } else { tutupToko(); }
                    return;
                }
                if (event.key !== 'Tab') { return; }

                var panelAktif = modalAktif.querySelector('.modal-panel');
                var daftar = Array.prototype.filter.call(
                    panelAktif.querySelectorAll('button:not([disabled])'),
                    function (el) { return el.offsetParent !== null; }
                );
                if (daftar.length === 0) { return; }

                var pertama = daftar[0];
                var terakhir = daftar[daftar.length - 1];

                if (!panelAktif.contains(document.activeElement)) {
                    event.preventDefault();
                    pertama.focus();
                } else if (event.shiftKey && document.activeElement === pertama) {
                    event.preventDefault();
                    terakhir.focus();
                } else if (!event.shiftKey && document.activeElement === terakhir) {
                    event.preventDefault();
                    pertama.focus();
                }
            });

            // Arazka menyapa saat halaman selesai dimuat
            setTimeout(function () {
                if (!modalAktif) {
                    arazkaBicara('Halo, Kapten! Aku Arazka, mentor luar angkasamu. Pilih satu planet dan mulai petualanganmu! 🚀', 'halo', 9000);
                }
            }, 1200);
        })();
    </script>
</body>
</html>