<?php
// ============================================================
// arazka.php — Chat CS AI (tanpa database)
// ============================================================
define('BRIGHT_SPACE', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/config/database.php';
require_login();

$user = current_user();

function e($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); }

$arazkaSvg = '<svg viewBox="0 0 100 100" class="w-full h-full" aria-hidden="true">
    <rect x="31" y="74" width="38" height="20" rx="10" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2.5"/>
    <polygon points="50,78 52,83 57,83.5 53.5,87 54.5,92 50,89.5 45.5,92 46.5,87 43,83.5 48,83" fill="#FCD34D"/>
    <line x1="50" y1="22" x2="50" y2="13" stroke="#1E1B4B" stroke-width="3" stroke-linecap="round"/>
    <polygon points="50,1 52,5.5 57,6 53.3,9.2 54.4,14 50,11.5 45.6,14 46.7,9.2 43,6 48,5.5" fill="#FCD34D" stroke="#B45309" stroke-width="1"/>
    <circle cx="50" cy="48" r="29" fill="#FFFFFF" stroke="#1E1B4B" stroke-width="2.5"/>
    <circle cx="21" cy="48" r="5" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2"/>
    <circle cx="79" cy="48" r="5" fill="#A78BFA" stroke="#1E1B4B" stroke-width="2"/>
    <rect x="22" y="34" width="56" height="30" rx="15" fill="#312E81" stroke="#1E1B4B" stroke-width="2"/>
    <circle cx="40" cy="47" r="4.2" fill="#7DD3FC"/>
    <circle cx="60" cy="47" r="4.2" fill="#7DD3FC"/>
    <path d="M44 56q6 5 12 0" fill="none" stroke="#7DD3FC" stroke-width="2.5" stroke-linecap="round"/>
</svg>';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanya Arazka - BRIGHT SPACE</title>
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
        @keyframes bob { 0%,100%{transform:translateY(0);}50%{transform:translateY(-6px);} }
        .arazka-bob { animation: bob 2.5s ease-in-out infinite; }
    </style>
</head>
<body class="font-nunito bg-cosmic-bg text-cosmic-text min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-40 bg-cosmic-card border-b-4 border-cosmic-accent shadow-md">
        <nav class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
            <a href="index.php" class="text-lg font-black">BRIGHT <span class="text-violet-600">SPACE</span></a>
            <a href="logout.php" class="bg-rose-500 hover:bg-rose-600 text-white font-extrabold text-sm px-3 py-2 rounded-full shadow">Keluar</a>
        </nav>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6 w-full flex-1">

        <section class="mb-6 text-center">
            <h1 class="text-3xl sm:text-4xl font-black">🤖 Tanya Arazka</h1>
            <p class="mt-2 text-base font-bold opacity-80">Mentor AI siap bantu kamu, Kapten <?= e($user['nama']) ?>!</p>
        </section>

        <section class="mb-4">
            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-2">✨ Pertanyaan Cepat</p>
            <div class="flex flex-wrap gap-2">
                <button type="button" class="faq-pill bg-white hover:bg-violet-100 border-2 border-violet-200 rounded-full px-4 py-2 text-xs font-extrabold" data-q="Gimana cara dapet Koin Bintang?">❓ Cara dapet Koin</button>
                <button type="button" class="faq-pill bg-white hover:bg-violet-100 border-2 border-violet-200 rounded-full px-4 py-2 text-xs font-extrabold" data-q="Gimana cara buka Planet yang terkunci?">🚀 Buka Planet Terkunci</button>
                <button type="button" class="faq-pill bg-white hover:bg-violet-100 border-2 border-violet-200 rounded-full px-4 py-2 text-xs font-extrabold" data-q="Apa itu Star Coins?">⭐ Apa itu Star Coins?</button>
                <button type="button" class="faq-pill bg-white hover:bg-violet-100 border-2 border-violet-200 rounded-full px-4 py-2 text-xs font-extrabold" data-q="Siapa kamu Arazka?">👋 Kenalan Arazka</button>
            </div>
        </section>

        <section class="bg-white border-2 border-indigo-100 rounded-3xl shadow-md overflow-hidden flex flex-col">
            <div class="flex items-center gap-3 px-5 py-3 bg-gradient-to-r from-violet-100 to-indigo-100 border-b-2 border-indigo-100">
                <div class="arazka-bob w-12 h-12 shrink-0"><?= $arazkaSvg ?></div>
                <div class="flex-1">
                    <p class="text-sm font-black">Arazka</p>
                    <p class="text-xs font-bold text-emerald-600">● Online — Siap bantu!</p>
                </div>
            </div>

            <div id="chat-messages" class="p-4 space-y-3" style="min-height: 350px;">
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-violet-100 border-2 border-violet-200 flex items-center justify-center text-lg">🤖</div>
                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl rounded-tl-sm px-4 py-2.5 max-w-[80%]">
                        <p class="text-sm font-bold leading-snug">Halo, Kapten <?= e($user['nama']) ?>! 🚀 Aku Arazka, siap bantu kamu! Tanya apa aja ya, atau klik tombol pertanyaan cepat di atas! 😊</p>
                    </div>
                </div>
            </div>

            <form id="chat-form" class="flex items-center gap-2 p-3 border-t-2 border-indigo-100 bg-indigo-50/40">
                <input type="text" id="chat-input" autocomplete="off" placeholder="Ketik pertanyaanmu di sini..."
                       class="flex-1 px-4 py-3 rounded-2xl border-2 border-violet-200 focus:border-violet-500 focus:outline-none font-bold text-sm bg-white">
                <button type="submit" class="shrink-0 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm px-5 py-3 rounded-2xl shadow flex items-center gap-1">
                    <span>Kirim</span><span>🚀</span>
                </button>
            </form>
        </section>
    </main>

    <footer class="bg-cosmic-card border-t-4 border-cosmic-accent">
        <div class="max-w-4xl mx-auto px-4 py-4 text-center text-xs font-bold opacity-70">BRIGHT SPACE EdTech</div>
    </footer>

    <script>
    (function () {
        'use strict';
        var form = document.getElementById('chat-form');
        var input = document.getElementById('chat-input');
        var box = document.getElementById('chat-messages');
        var faqPills = document.querySelectorAll('.faq-pill');

        var knowledge = [
            { keys: ['koin', 'bintang'], jawaban: 'Koin Bintang ⭐ bisa kamu dapat dengan menyelesaikan misi di setiap planet! Setiap jawaban benar memberi koin. Semakin tinggi akurasimu, semakin banyak koinmu! 🚀' },
            { keys: ['buka planet', 'terkunci', 'kunci'], jawaban: 'Planet yang terkunci 🔒 akan terbuka kalau kamu sudah naik level, Kapten! Terus selesaikan misi di planet yang terbuka dulu ya! 💪' },
            { keys: ['pakai barang', 'item', 'toko'], jawaban: 'Barang yang sudah kamu beli 🛍️ bisa dipakai lewat halaman Profil → Koleksi Saya → tombol "Gunakan 👕"! ✨' },
            { keys: ['star coin', 'apa itu'], jawaban: 'Star Coins (Koin Bintang) 🪙 adalah mata uang di BRIGHT SPACE! Kamu pakai untuk beli item keren di Toko Bintang! ⭐' },
            { keys: ['siapa', 'kamu siapa', 'arazka'], jawaban: 'Aku Arazka! 🤖 Mentor AI BRIGHT SPACE yang selalu siap bantu Kapten cilik seperti kamu! ✨' },
            { keys: ['capek', 'susah', 'gagal'], jawaban: 'Jangan sedih, Kapten! Setiap usaha itu hebat. Istirahat dulu, nanti coba lagi. Aku yakin kamu bisa! 🌈' },
        ];

        function cariJawaban(teks) {
            var t = teks.toLowerCase();
            for (var i = 0; i < knowledge.length; i++) {
                for (var j = 0; j < knowledge[i].keys.length; j++) {
                    if (t.indexOf(knowledge[i].keys[j]) !== -1) return knowledge[i].jawaban;
                }
            }
            return 'Hmm, aku belum paham soal itu, Kapten. 🤔 Coba tanya pakai kata lain, atau klik tombol pertanyaan cepat di atas ya! 😊';
        }

        function tambahPesan(siapa, teks) {
            var wrap = document.createElement('div');
            wrap.className = 'flex items-start gap-2 ' + (siapa === 'user' ? 'flex-row-reverse' : '');
            var av = document.createElement('div');
            av.className = 'w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-lg ' + (siapa === 'user' ? 'bg-violet-300 text-white' : 'bg-violet-100 border-2 border-violet-200');
            av.textContent = siapa === 'user' ? '👤' : '🤖';
            var bubble = document.createElement('div');
            bubble.className = 'px-4 py-2.5 max-w-[80%] text-sm font-bold leading-snug ' + (siapa === 'user' ? 'bg-violet-500 text-white rounded-2xl rounded-tr-sm' : 'bg-indigo-50 border border-indigo-100 rounded-2xl rounded-tl-sm');
            bubble.textContent = teks;
            wrap.appendChild(av);
            wrap.appendChild(bubble);
            box.appendChild(wrap);
            box.scrollTop = box.scrollHeight;
        }

        function kirimPesan(teks) {
            if (!teks.trim()) return;
            tambahPesan('user', teks);
            input.value = '';
            setTimeout(function () {
                tambahPesan('arazka', cariJawaban(teks));
            }, 500);
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            kirimPesan(input.value);
        });

        faqPills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                kirimPesan(pill.getAttribute('data-q'));
            });
        });
    })();
    </script>
</body>
</html>