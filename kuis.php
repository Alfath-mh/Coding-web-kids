<?php
// ============================================================
// kuis.php — Halaman kuis langsung (tanpa database)
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

$planetId = isset($_GET['planet']) ? trim($_GET['planet']) : '';
$planet   = cariPlanet($planets, $planetId);

if (!$planet || $planet['status'] !== 'Terbuka') {
    header('Location: index.php');
    exit;
}

$soalList = get_soal($planet['id'], 'kuis', 5);
$pageTitle = $planet['nama'] . ' - Kuis';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - BRIGHT SPACE</title>
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
        @keyframes pop-in { 0%{opacity:0;transform:scale(0.8) translateY(10px);}100%{opacity:1;transform:scale(1) translateY(0);} }
        @keyframes shake { 0%,100%{transform:translateX(0);}25%{transform:translateX(-6px);}75%{transform:translateX(6px);} }
        @keyframes bounce-in { 0%{transform:scale(0);}60%{transform:scale(1.15);}100%{transform:scale(1);} }
        .pop-in { animation: pop-in 0.3s ease-out; }
        .shake { animation: shake 0.4s ease-out; }
        .bounce-in { animation: bounce-in 0.5s ease-out; }
        .opsi-btn { transition: all 0.2s ease-out; }
        .opsi-btn:hover:not(:disabled) { transform: translateY(-3px) scale(1.02); }
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-40 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
        <nav class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
            <a href="index.php" class="inline-flex items-center gap-2 bg-white border-2 border-violet-200 hover:border-violet-400 font-extrabold text-sm px-4 py-2 rounded-full shadow-sm">
                <span>⬅️</span>
                <span>Kembali</span>
            </a>
            <div class="flex items-center gap-2">
                <div id="progress-teks" class="bg-violet-100 border-2 border-violet-200 rounded-full px-4 py-1.5 text-sm font-black text-violet-700">
                    Soal <span id="soal-sekarang">1</span> dari <span id="soal-total">5</span>
                </div>
                <div class="bg-amber-100 border-2 border-amber-300 rounded-full px-3 py-1.5 text-sm font-black text-amber-800 flex items-center gap-1">
                    🪙 <span id="coin-jumlah"><?= (int) $user['star_coins'] ?></span>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-6 w-full flex-1">

        <div class="w-full bg-white rounded-full h-4 overflow-hidden border-2 border-violet-200 shadow-sm mb-5">
            <div id="progress-bar" class="h-full bg-gradient-to-r from-violet-500 to-indigo-500 rounded-full transition-all duration-500" style="width: 0%;"></div>
        </div>

        <section id="kartu-soal" class="bg-white rounded-3xl shadow-xl border-4 border-violet-100 p-6 sm:p-8 pop-in">
            <div class="flex items-center justify-center gap-3 mb-5">
                <div class="w-14 h-14 shrink-0"><?= $planet['ikon'] ?></div>
                <div class="text-center">
                    <p class="text-xs font-extrabold uppercase tracking-wider text-violet-600"><?= e($planet['nama']) ?></p>
                    <p class="text-lg font-black">Ayo Jawab Soal Ini! 🚀</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-violet-50 to-indigo-50 border-2 border-violet-200 rounded-2xl p-5 mb-6">
                <p id="teks-soal" class="text-base sm:text-lg font-extrabold leading-snug text-center text-slate-800">
                    Memuat soal...
                </p>
            </div>

            <div id="emoji-soal" class="text-center text-5xl sm:text-6xl mb-4 select-none">🤔</div>
            <div id="pilihan-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>

            <div id="feedback-box" class="hidden mt-5 flex items-start gap-3 bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-4">
                <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-emerald-100 border-2 border-emerald-300 rounded-full text-2xl">🤖</div>
                <div class="flex-1">
                    <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-600">Arazka</p>
                    <p id="feedback-judul" class="text-base font-black text-emerald-900">Hore! Benar 🎉</p>
                    <p id="feedback-teks" class="mt-1 text-sm font-bold text-emerald-800 leading-snug"></p>
                </div>
            </div>
        </section>
    </main>

    <div id="modal-hasil" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4 bg-slate-900/70" style="display:none;">
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 sm:p-8 text-center bounce-in border-4 border-amber-300">
            <div class="text-6xl mb-3">🎉</div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800">Misi Selesai!</h2>
            <p class="mt-1 text-sm font-bold text-slate-500">Kerja bagus, Kapten <?= e($user['nama']) ?>!</p>

            <div class="mt-5 bg-gradient-to-br from-amber-50 to-yellow-100 border-4 border-amber-300 rounded-3xl p-5">
                <p class="text-xs font-extrabold uppercase tracking-wider text-amber-700">Skor Kamu</p>
                <p class="mt-2 text-4xl sm:text-5xl font-black text-amber-800">
                    <span id="hasil-benar">0</span><span class="text-2xl opacity-60">/</span><span id="hasil-total">5</span>
                </p>
                <p class="mt-1 text-sm font-extrabold text-amber-700">Akurasi: <span id="hasil-akurasi">0</span>%</p>
            </div>

            <div class="mt-5 flex justify-center gap-3">
                <div class="bg-cosmic-accent border-2 border-amber-300 rounded-2xl px-4 py-3 flex items-center gap-2">
                    <span class="text-2xl">⭐</span>
                    <div class="text-left">
                        <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Bintang</p>
                        <p class="text-xl font-black text-amber-900 leading-none">+<span id="hasil-bintang">0</span></p>
                    </div>
                </div>
                <div class="bg-cosmic-accent border-2 border-amber-300 rounded-2xl px-4 py-3 flex items-center gap-2">
                    <span class="text-2xl">🪙</span>
                    <div class="text-left">
                        <p class="text-[10px] font-extrabold uppercase text-amber-900 leading-none">Koin</p>
                        <p class="text-xl font-black text-amber-900 leading-none">+<span id="hasil-koin">0</span></p>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex items-start gap-3 bg-violet-50 border-2 border-violet-200 rounded-2xl p-4 text-left">
                <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-violet-100 border-2 border-violet-300 rounded-full text-2xl">🤖</div>
                <div class="flex-1">
                    <p class="text-xs font-extrabold uppercase tracking-wider text-violet-600">Arazka</p>
                    <p id="hasil-komentar" class="text-sm font-bold text-violet-900 leading-snug">Kerja bagus, Kapten!</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="kuis.php?planet=<?= e($planet['id']) ?>" class="bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-3 rounded-full shadow">
                    🔁 Coba Lagi
                </a>
                <a href="index.php" class="bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm py-3 rounded-full shadow">
                    🚀 Kembali ke Misi
                </a>
            </div>
        </div>
    </div>

    <div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[100] hidden bg-cosmic-text text-white font-bold text-sm px-4 py-2 rounded-full shadow-lg text-center max-w-[90%]" style="display:none;"></div>

    <script>
        window.BRIGHT_SPACE = {
            user: {
                id: 1,
                nama: <?= json_encode($user['nama'], JSON_UNESCAPED_UNICODE) ?>,
                star_coins: <?= (int) $user['star_coins'] ?>
            },
            planet: {
                id: <?= json_encode($planet['id']) ?>,
                nama: <?= json_encode($planet['nama'], JSON_UNESCAPED_UNICODE) ?>
            },
            soalList: <?= json_encode($soalList, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            rewardKoin: 10,
            rewardBintang: 1
        };
    </script>
    <script>
    (function () {
        'use strict';
        var BS = window.BRIGHT_SPACE;
        if (!BS || !BS.soalList || !BS.soalList.length) return;
        if (!document.getElementById('pilihan-container')) return;

        var soalList     = BS.soalList;
        var totalSoal    = soalList.length;
        var indeks       = 0;
        var benar        = 0;
        var koinTotal    = parseInt(BS.user.star_coins, 10) || 0;
        var sudahDijawab = false;

        var emojiMap = {
            'matematika':  ['🔢', '➕', '✖️', '🧮', '📐'],
            'sains':       ['🔬', '🧪', '🌍', '🚀', '⭐'],
            'bahasa':      ['📚', '✏️', '📖', '💬', '🎭'],
            'seni-coding': ['🎨', '💻', '🖌️', '🎭', '✨']
        };
        var emojis = emojiMap[BS.planet.id] || ['🤔'];

        var teksSoal     = document.getElementById('teks-soal');
        var emojiSoal    = document.getElementById('emoji-soal');
        var pilihanBox   = document.getElementById('pilihan-container');
        var progressBar  = document.getElementById('progress-bar');
        var soalSekarang = document.getElementById('soal-sekarang');
        var soalTotalEl  = document.getElementById('soal-total');
        var feedbackBox  = document.getElementById('feedback-box');
        var fbJudul      = document.getElementById('feedback-judul');
        var fbTeks       = document.getElementById('feedback-teks');
        var coinJumlah   = document.getElementById('coin-jumlah');

        var modalHasil    = document.getElementById('modal-hasil');
        var hasilBenar    = document.getElementById('hasil-benar');
        var hasilTotal    = document.getElementById('hasil-total');
        var hasilAkurasi  = document.getElementById('hasil-akurasi');
        var hasilBintang  = document.getElementById('hasil-bintang');
        var hasilKoin     = document.getElementById('hasil-koin');
        var hasilKomentar = document.getElementById('hasil-komentar');

        function showToast(pesan, durasi) {
            var t = document.getElementById('toast');
            if (!t) return;
            t.textContent = pesan;
            t.classList.remove('hidden');
            t.style.display = '';
            clearTimeout(t._timer);
            t._timer = setTimeout(function () {
                t.classList.add('hidden');
                t.style.display = 'none';
            }, durasi || 2400);
        }

        function renderSoal() {
            var soal = soalList[indeks];
            if (!soal) { tampilkanHasil(); return; }

            sudahDijawab = false;
            feedbackBox.classList.add('hidden');

            teksSoal.textContent = soal.pertanyaan;
            emojiSoal.textContent = emojis[indeks % emojis.length] || '🤔';

            soalSekarang.textContent = indeks + 1;
            soalTotalEl.textContent = totalSoal;
            progressBar.style.width = ((indeks / totalSoal) * 100) + '%';

            var kartu = document.getElementById('kartu-soal');
            kartu.classList.remove('pop-in');
            void kartu.offsetWidth;
            kartu.classList.add('pop-in');

            pilihanBox.innerHTML = '';
            Object.keys(soal.pilihan).forEach(function (huruf) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('data-huruf', huruf);
                btn.className = 'opsi-btn w-full flex items-center gap-3 text-left bg-white border-2 border-violet-200 hover:border-violet-400 rounded-2xl px-4 py-4 font-bold text-base shadow-sm hover:shadow-md';

                var bulat = document.createElement('span');
                bulat.className = 'shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-violet-500 text-white font-black text-lg';
                bulat.textContent = huruf;

                var teks = document.createElement('span');
                teks.className = 'flex-1';
                teks.textContent = soal.pilihan[huruf];

                btn.appendChild(bulat);
                btn.appendChild(teks);
                pilihanBox.appendChild(btn);
            });
        }

        pilihanBox.addEventListener('click', function (e) {
            var btn = e.target.closest('.opsi-btn');
            if (!btn || btn.disabled || sudahDijawab) return;

            sudahDijawab = true;
            var soal = soalList[indeks];
            var huruf = btn.getAttribute('data-huruf');
            var semua = pilihanBox.querySelectorAll('.opsi-btn');

            if (huruf === soal.jawaban) {
                benar++;
                Array.prototype.forEach.call(semua, function (o) {
                    o.disabled = true;
                    if (o === btn) {
                        o.className = 'opsi-btn w-full flex items-center gap-3 text-left bg-emerald-100 border-2 border-emerald-400 rounded-2xl px-4 py-4 font-bold text-base';
                    } else {
                        o.className = 'opsi-btn w-full flex items-center gap-3 text-left bg-white/60 border-2 border-violet-100 rounded-2xl px-4 py-4 font-bold text-base opacity-50';
                    }
                });

                fbJudul.textContent = 'Hore! Benar 🎉 (+10 Koin)';
                fbTeks.textContent = soal.penjelasan || 'Jawabanmu tepat sekali!';
                feedbackBox.classList.remove('hidden');
                feedbackBox.className = 'mt-5 flex items-start gap-3 bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-4 pop-in';

                koinTotal += BS.rewardKoin;
                coinJumlah.textContent = koinTotal;

                showToast('🎉 Benar! +' + BS.rewardKoin + ' Koin', 1500);

                setTimeout(function () {
                    indeks++;
                    if (indeks >= totalSoal) {
                        progressBar.style.width = '100%';
                        tampilkanHasil();
                    } else {
                        renderSoal();
                    }
                }, 1200);
            } else {
                Array.prototype.forEach.call(semua, function (o) {
                    o.disabled = true;
                    if (o.getAttribute('data-huruf') === soal.jawaban) {
                        o.className = 'opsi-btn w-full flex items-center gap-3 text-left bg-emerald-50 border-2 border-emerald-400 rounded-2xl px-4 py-4 font-bold text-base';
                    } else if (o === btn) {
                        o.className = 'opsi-btn w-full flex items-center gap-3 text-left bg-rose-100 border-2 border-rose-400 rounded-2xl px-4 py-4 font-bold text-base shake';
                    } else {
                        o.className = 'opsi-btn w-full flex items-center gap-3 text-left bg-white/60 border-2 border-violet-100 rounded-2xl px-4 py-4 font-bold text-base opacity-50';
                    }
                });

                var petunjuk = (soal.umpan_salah && soal.umpan_salah[huruf]) || 'Coba baca lagi ya, Kapten.';
                fbJudul.textContent = 'Belum tepat 💪';
                fbTeks.textContent = petunjuk;
                feedbackBox.classList.remove('hidden');
                feedbackBox.className = 'mt-5 flex items-start gap-3 bg-amber-50 border-2 border-amber-200 rounded-2xl p-4 pop-in';

                showToast('💪 Jawaban belum tepat, tapi jangan menyerah!', 2000);

                setTimeout(function () {
                    indeks++;
                    if (indeks >= totalSoal) {
                        progressBar.style.width = '100%';
                        tampilkanHasil();
                    } else {
                        renderSoal();
                    }
                }, 1800);
            }
        });

        function tampilkanHasil() {
            var akurasi = Math.round((benar / totalSoal) * 100);
            var bintang = Math.round(benar / 2);
            if (benar === totalSoal) bintang = 3;
            var koinDiperoleh = benar * BS.rewardKoin;
            if (benar === totalSoal) koinDiperoleh += 10;

            hasilBenar.textContent = benar;
            hasilTotal.textContent = totalSoal;
            hasilAkurasi.textContent = akurasi;
            hasilBintang.textContent = bintang;
            hasilKoin.textContent = koinDiperoleh;

            var komentar = '';
            if (akurasi === 100) komentar = 'SEMPURNA, Kapten! 🏆 Semua jawaban benar!';
            else if (akurasi >= 80) komentar = 'Hebat, Kapten! 🌟 Dikit lagi sempurna!';
            else if (akurasi >= 60) komentar = 'Bagus! 💪 Terus semangat belajar ya!';
            else if (akurasi >= 40) komentar = 'Kerja bagus sudah mencoba! 🌈 Ayo latihan lagi!';
            else komentar = 'Jangan sedih, Kapten! Setiap usaha itu hebat! 😊';
            hasilKomentar.textContent = komentar;

            setTimeout(function () {
                modalHasil.classList.remove('hidden');
                modalHasil.style.display = '';
            }, 400);
        }

        renderSoal();
    })();
    </script>
</body>
</html>