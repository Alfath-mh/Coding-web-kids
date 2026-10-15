<?php
// ============================================================
// BRIGHT SPACE - EdTech Platform untuk Siswa SD
// File utama: menyiapkan data lalu menyatukan semua komponen.
// Stack: PHP Native + HTML5 + Tailwind (CDN) + Vanilla JS
// ============================================================

// Penanda agar file di folder components/ tidak bisa dibuka langsung
define('BRIGHT_SPACE', true);

$appName   = 'BRIGHT SPACE';
$pageTitle = 'BRIGHT SPACE - Petualangan Belajar di Luar Angkasa';
$starCoins = 120; // Poin awal siswa

// Fungsi bantu: escape teks agar aman ditampilkan di HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// ------------------------------------------------------------
// Pemeriksaan awal: pastikan semua file komponen & aset ada.
// Jika ada yang hilang, tampilkan jalur lengkap file tersebut
// (membantu debugging di XAMPP Windows).
// ------------------------------------------------------------
$fileWajib = [
    __DIR__ . '/components/header.php',
    __DIR__ . '/components/planet-grid.php',
    __DIR__ . '/components/modal-quiz.php',
    __DIR__ . '/components/modal-shop.php',
    __DIR__ . '/assets/js/app.js',
];
foreach ($fileWajib as $fileCek) {
    if (!is_file($fileCek)) {
        http_response_code(500);
        exit('BRIGHT SPACE: file tidak ditemukan -> ' . e($fileCek)
            . '. Pastikan nama file & foldernya benar (cek juga ekstensi tersembunyi seperti .php.txt).');
    }
}
unset($fileWajib, $fileCek);

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

    <!-- Header: navbar, logo, counter Star Coins, tombol Toko -->
    <?php include __DIR__ . '/components/header.php'; ?>

    <!-- Konten utama: hero + grid planet -->
    <main id="main-content" class="max-w-6xl mx-auto px-4 py-8 w-full flex-grow">
        <?php include __DIR__ . '/components/planet-grid.php'; ?>
    </main>

    <!-- Widget Arazka + Modal Kuis -->
    <?php include __DIR__ . '/components/modal-quiz.php'; ?>

    <!-- Modal Toko Virtual -->
    <?php include __DIR__ . '/components/modal-shop.php'; ?>

    <!-- Toast notifikasi -->
    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[80] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" role="status" aria-live="polite"></div>

    <!-- Footer -->
    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-6xl mx-auto px-4 pt-4 pb-24 md:pb-4 text-center text-xs sm:text-sm font-bold">
            Optimized for Low-Spec School Hardware (RAM 2GB-4GB) | BRIGHT SPACE EdTech
        </div>
    </footer>

    <!-- Data dari PHP untuk JavaScript (dibaca oleh assets/js/app.js) -->
    <script>
        window.BRIGHT_SPACE = {
            kuis: <?= json_encode($kuis, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        };
    </script>

    <!-- Logika Vanilla JS -->
    <script src="assets/js/app.js" defer></script>
</body>
</html>