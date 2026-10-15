/* ============================================================
 * BRIGHT SPACE - assets/js/app.js
 * Seluruh logika Vanilla JS: Star Coins, kuis, maskot Arazka,
 * dan Toko Bintang.
 * Data soal dibaca dari window.BRIGHT_SPACE (diisi di index.php).
 * ============================================================ */
(function () {
    'use strict';

    // ---------- Data kuis dari PHP ----------
    var DATA_KUIS = (window.BRIGHT_SPACE && window.BRIGHT_SPACE.kuis) || {};

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
    var KELAS_KARTU_BELI  = 'item-toko flex flex-col text-center bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-3 transition-colors duration-200';
    var KELAS_STATUS_BELI = 'item-status mt-1 text-xs font-extrabold text-emerald-700';
    var KELAS_TOMBOL_BELI = 'btn-beli w-full bg-emerald-200 text-emerald-800 font-extrabold text-sm py-2 rounded-full cursor-default';

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

    // ============================================================
    //  SISTEM STAR COINS
    // ============================================================
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

    // ============================================================
    //  MASKOT ARAZKA
    // ============================================================

    // ---------- Widget mengambang ----------
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