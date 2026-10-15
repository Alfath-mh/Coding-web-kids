<?php
// ============================================================
// config/planets.php
// Sumber data terpusat: planet, kuis misi wajib, latihan tambahan,
// dan opsi mode game.
// ============================================================
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');

// ------------------------------------------------------------
// Data 4 Planet Belajar
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
// Bank Soal MISI WAJIB (reward koin penuh)
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
// Bank Soal LATIHAN TAMBAHAN (soal BERBEDA, tanpa reward koin)
// ------------------------------------------------------------
$latihan = [
    'matematika' => [
        'pertanyaan'  => 'Arazka punya 15 kelereng. Ia memberikan 7 kelereng kepada temannya. Berapa sisa kelereng Arazka?',
        'pilihan'     => ['A' => '7 kelereng', 'B' => '8 kelereng', 'C' => '9 kelereng'],
        'jawaban'     => 'B',
        'penjelasan'  => '15 dikurangi 7 sama dengan 8.',
        'umpan_salah' => [
            'A' => 'Hmm, 7 itu jumlah yang diberikan, bukan sisanya. Coba kurangi 15 dengan 7 ya!',
            'C' => '9 itu sedikit terlalu banyak. Coba hitung mundur 7 langkah dari angka 15.',
        ],
    ],
    'sains' => [
        'pertanyaan'  => 'Bagian tumbuhan yang menyerap air dan zat hara dari dalam tanah adalah ...',
        'pilihan'     => ['A' => 'Daun', 'B' => 'Batang', 'C' => 'Akar'],
        'jawaban'     => 'C',
        'penjelasan'  => 'Akar menyerap air dan zat hara dari dalam tanah untuk makanan tumbuhan.',
        'umpan_salah' => [
            'A' => 'Daun tempat memasak makanan (fotosintesis), bukan penyerap air. Cari bagian yang ada di dalam tanah!',
            'B' => 'Batang menyalurkan air ke seluruh tubuh tumbuhan, tapi bukan penyerap utamanya. Cari yang paling bawah!',
        ],
    ],
    'bahasa' => [
        'pertanyaan'  => 'Kalimat yang menggunakan tanda baca dengan benar adalah ...',
        'pilihan'     => ['A' => 'Arazka pergi ke bulan.', 'B' => 'arazka pergi ke bulan', 'C' => 'Arazka pergi ke bulan'],
        'jawaban'     => 'A',
        'penjelasan'  => 'Kalimat yang benar diawali huruf kapital dan diakhiri tanda titik.',
        'umpan_salah' => [
            'B' => 'Hampir benar! Tapi kalimat harus diawali huruf kapital dan diakhiri tanda titik. Coba perhatikan huruf pertamanya!',
            'C' => 'Tinggal satu langkah lagi! Kalimat yang baik diakhiri dengan tanda titik, koma, atau tanda tanya.',
        ],
    ],
];

// ------------------------------------------------------------
// Definisi opsi mode game (dipakai di planet-detail.php)
// ------------------------------------------------------------
$modeGame = [
    'wajib' => [
        'id'          => 'wajib',
        'judul'       => 'Misi Wajib',
        'subjudul'    => 'Kuis Utama',
        'deskripsi'   => 'Selesaikan misi utama planet ini untuk mengumpulkan Koin Bintang dan membuka level berikutnya.',
        'ikon'        => '🎯',
        'label_koin'  => '+ Koin Penuh',
        'sumber_soal' => 'kuis',
        'warna'       => [
            'bg'      => 'from-violet-100 to-indigo-200',
            'border'  => 'border-indigo-300',
            'tombol'  => 'bg-indigo-500 hover:bg-indigo-600 focus-visible:ring-indigo-300',
            'badge'   => 'bg-amber-300 text-amber-900',
        ],
    ],
    'latihan' => [
        'id'          => 'latihan',
        'judul'       => 'Latihan Tambahan',
        'subjudul'    => 'Opsional',
        'deskripsi'   => 'Soal tambahan untuk memperkuat pemahamanmu. Tidak wajib, tanpa reward koin, boleh diulang sesuka hati.',
        'ikon'        => '🎓',
        'label_koin'  => 'Tanpa Koin',
        'sumber_soal' => 'latihan',
        'warna'       => [
            'bg'      => 'from-emerald-100 to-teal-200',
            'border'  => 'border-emerald-300',
            'tombol'  => 'bg-emerald-500 hover:bg-emerald-600 focus-visible:ring-emerald-300',
            'badge'   => 'bg-sky-300 text-sky-900',
        ],
    ],
];

// ------------------------------------------------------------
// Helper: cari planet berdasarkan ID
// ------------------------------------------------------------
function cariPlanet($planets, $id)
{
    foreach ($planets as $p) {
        if ($p['id'] === $id) { return $p; }
    }
    return null;
}