/* ============================================================
 * BRIGHT SPACE - assets/js/app.js
 * ============================================================ */
(function () {
    'use strict';

    // ---------- Data dari PHP ----------
    var DATA_KUIS    = (window.BRIGHT_SPACE && window.BRIGHT_SPACE.kuis)    || {};
    var DATA_LATIHAN = (window.BRIGHT_SPACE && window.BRIGHT_SPACE.latihan) || {};
    var PLANET_INFO  = (window.BRIGHT_SPACE && window.BRIGHT_SPACE.planet)  || null;
    var GAME_MODES   = (window.BRIGHT_SPACE && window.BRIGHT_SPACE.gameModes) || {};
    var ITEM_TOKO    = window.BRIGHT_SPACE_ITEMS || [];

    var IKON_BINTANG_KECIL =
        '<svg viewBox="0 0 24 24" class="w-full h-full" aria-hidden="true">' +
        '<polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.5" stroke-linejoin="round"/></svg>';

    var PEMBUKA_SALAH = ['Hampir tepat! 💪', 'Ayo coba lagi, kamu pasti bisa! 🌈', 'Semangat, Kapten! Satu langkah lagi! 🚀'];
    var JUDUL_BELI = ['Yeay, berhasil! 🎉', 'Keren sekali, Kapten! 🌟', 'Hore, item baru! 🚀'];
    var TIPS = [
        'Klik kartu planet yang terbuka untuk memulai petualangan! 🚀',
        'Setiap jawaban benar memberimu Koin Bintang. Yuk kumpulkan sebanyak-banyaknya! ⭐',
        'Kalau jawabanmu belum tepat, jangan sedih. Aku akan memberi petunjuk! 💡',
        'Tukarkan Koin Bintangmu di Toko Bintang untuk mendapatkan item keren! 🛍️',
        'Ada 5 mode game seru di tiap planet. Coba semuanya ya! 🎮'
    ];

    var KELAS_KARTU_BELI  = 'item-toko flex flex-col text-center bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-3 transition-colors duration-200';
    var KELAS_STATUS_BELI = 'item-status mt-1 text-xs font-extrabold text-emerald-700';
    var KELAS_TOMBOL_BELI = 'btn-beli w-full bg-emerald-200 text-emerald-800 font-extrabold text-sm py-2 rounded-full cursor-default';

    // ---------- DOM ----------
    var coinEl    = document.getElementById('star-coin-count');
    var coinBadge = document.getElementById('star-coin-badge');
    var toastEl   = document.getElementById('toast');
    var gridEl    = document.getElementById('grid-planet');

    var modal     = document.getElementById('kuis-modal');
    var ikonEl    = document.getElementById('kuis-ikon');
    var judulEl   = document.getElementById('kuis-judul');
    var hadiahEl  = document.getElementById('kuis-hadiah');
    var tanyaEl   = document.getElementById('kuis-pertanyaan');
    var pilihanEl = document.getElementById('kuis-pilihan');
    var lanjutBtn = document.getElementById('kuis-lanjut');
    var tutupBtn  = document.getElementById('kuis-tutup');

    var tokoModal = document.getElementById('toko-modal');
    var tokoGrid  = document.getElementById('toko-grid');
    var tokoSaldo = document.getElementById('toko-saldo');
    var tokoTutup = document.getElementById('toko-tutup');
    var tokoBtn   = document.getElementById('btn-toko-bintang');

    var wBtn    = document.getElementById('arazka-btn');
    var wBubble = document.getElementById('arazka-bubble');
    var wTeks   = document.getElementById('arazka-teks');

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
    var koinTotal  = coinEl ? (parseInt(coinEl.textContent, 10) || 0) : 0;
    var koinTampil = koinTotal;
    var timerKoin  = null;
    var timerToast = null;
    var timerWidget = null;
    var indeksTips = 0;
    var selesai    = {};
    var dimiliki   = {};
    var modalAktif = null;
    var planetAktif = null;
    var soalAktif   = null;
    var modeAktif   = 'kuis';
    var sudahBenar  = false;
    var jumlahSalah = 0;
    var dapatKoinBaru = false;
    var belanjaBaru = false;
    var fokusSebelum = null;
    var sudahBangunToko = false;

    function setMood(el, mood) {
        if (!el) { return; }
        el.removeAttribute('data-mood');
        void el.offsetWidth;
        if (mood) { el.setAttribute('data-mood', mood); }
    }

    // ---------- STAR COINS ----------
    function tampilSaldoToko() { if (tokoSaldo) { tokoSaldo.textContent = koinTotal; } }

    function denyutKoin() {
        if (!coinBadge) { return; }
        coinBadge.classList.remove('coin-pop');
        void coinBadge.offsetWidth;
        coinBadge.classList.add('coin-pop');
    }

    function animasiKoin() {
        if (!coinEl) { return; }
        clearInterval(timerKoin);
        if (koinTampil === koinTotal) { return; }
        var langkah = Math.max(1, Math.ceil(Math.abs(koinTotal - koinTampil) / 15));
        timerKoin = setInterval(function () {
            var sisa  = koinTotal - koinTampil;
            var arah  = sisa > 0 ? 1 : -1;
            var gerak = Math.min(Math.abs(sisa), langkah);
            koinTampil += arah * gerak;
            coinEl.textContent = koinTampil;
            if (koinTampil === koinTotal) { clearInterval(timerKoin); denyutKoin(); }
        }, 40);
    }

    window.setStarCoins = function (nilai) {
        clearInterval(timerKoin);
        koinTotal = Math.max(0, parseInt(nilai, 10) || 0);
        koinTampil = koinTotal;
        if (coinEl) { coinEl.textContent = koinTotal; }
        tampilSaldoToko();
        denyutKoin();
    };

    window.getStarCoins = function () { return koinTotal; };

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

    window.showToast = function (pesan, durasi) {
        if (!toastEl) { return; }
        toastEl.textContent = pesan;
        toastEl.classList.remove('hidden');
        toastEl.style.display = '';
        clearTimeout(timerToast);
        timerToast = setTimeout(function () {
            toastEl.classList.add('hidden');
            toastEl.style.display = 'none';
        }, durasi || 2400);
    };

    // ---------- Modal ----------
    function tampilkanModal(root) {
        if (!root) { return; }
        modalAktif = root;
        root.classList.remove('hidden');
        root.removeAttribute('hidden');
        root.style.display = '';
        document.body.classList.add('overflow-hidden');
    }

    function sembunyikanModal(root) {
        if (!root) { return; }
        root.classList.add('hidden');
        root.setAttribute('hidden', '');
        root.style.display = 'none';
        if (modalAktif === root) { modalAktif = null; }
        document.body.classList.remove('overflow-hidden');
        document.documentElement.classList.remove('overflow-hidden');
        if (fokusSebelum && typeof fokusSebelum.focus === 'function') { fokusSebelum.focus(); }
    }

    // ---------- ARAZKA ----------
    function arazkaDiam() {
        if (!wBubble) { return; }
        clearTimeout(timerWidget);
        wBubble.classList.add('hidden');
        wBubble.style.display = 'none';
        wBtn.setAttribute('aria-expanded', 'false');
        setMood(wBtn, '');
    }

    function arazkaBicara(teks, mood, durasi) {
        if (!wBubble) { return; }
        wTeks.textContent = teks;
        wBubble.classList.remove('hidden');
        wBubble.style.display = '';
        wBtn.setAttribute('aria-expanded', 'true');
        setMood(wBtn, mood || 'halo');
        clearTimeout(timerWidget);
        timerWidget = setTimeout(arazkaDiam, durasi || 7000);
    }

    function tipBerikut() {
        var tip = TIPS[indeksTips % TIPS.length];
        indeksTips++;
        return tip;
    }

    function percikBintang(wadah) {
        if (!wadah) { return; }
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
        if (!ara || !ara.bubble) { return; }
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

    // ---------- KUIS ----------
    function bukaKuisDenganData(idPlanet, namaPlanet, poin, ikon, soal, mode) {
        if (!soal) {
            window.showToast('Soal untuk mode ini sedang disiapkan ya, Kapten! 🚀', 3000);
            return;
        }

        arazkaDiam();

        planetAktif = { id: idPlanet, nama: namaPlanet, poin: parseInt(poin, 10) || 0, ikon: ikon };
        soalAktif   = soal;
        modeAktif   = mode || 'kuis';
        sudahBenar  = false;
        jumlahSalah = 0;
        dapatKoinBaru = false;

        ikonEl.innerHTML     = ikon;
        var labelMode = '';
        if (modeAktif === 'latihan') { labelMode = ' — Latihan'; }
        else if (modeAktif === 'kuis') { labelMode = ' — Kuis Kilat'; }
        judulEl.textContent  = namaPlanet + labelMode;
        hadiahEl.textContent = (modeAktif === 'latihan') ? 'Tanpa Koin (Latihan)' : '+' + planetAktif.poin + ' Koin';
        tanyaEl.textContent  = soal.pertanyaan;

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

        var sapaan = (modeAktif === 'latihan')
            ? 'Ini latihan tambahan ya, Kapten! Tidak ada koin, tapi kamu makin jago! 💪'
            : 'Baca soalnya pelan-pelan, lalu pilih jawaban A, B, atau C. Aku di sini untuk membantumu!';

        bicara(araKuis, 'halo', 'netral', 'Siap, Kapten? 🚀', sapaan, false);

        tampilkanModal(modal);
        var pertama = pilihanEl.querySelector('button');
        if (pertama) { pertama.focus(); }
    }

    function tutupKuis() {
        if (!modal) { return; }
        sembunyikanModal(modal);

        if (modeAktif === 'latihan') {
            arazkaBicara('Latihan selesai! Semangat terus belajar, Kapten! 🌟', 'halo', 6000);
            return;
        }

        if (sudahBenar && dapatKoinBaru) {
            arazkaBicara('Mantap, Kapten! Koinmu sekarang ' + koinTotal + ' ⭐ Yuk pilih mode berikutnya!', 'selebrasi', 8000);
        } else if (sudahBenar) {
            arazkaBicara('Latihan yang bagus! Yuk coba mode lain untuk kumpulkan lebih banyak koin. 🌟', 'halo', 7000);
        } else {
            arazkaBicara('Tidak apa-apa, Kapten! Kamu boleh mencoba lagi kapan saja. 😊', 'semangat', 7000);
        }
    }

    function pilihJawaban(tombol) {
        if (sudahBenar) { return; }
        var huruf = tombol.getAttribute('data-huruf');
        var semuaOpsi = pilihanEl.querySelectorAll('.opsi-jawaban');

        if (huruf === soalAktif.jawaban) {
            sudahBenar = true;
            Array.prototype.forEach.call(semuaOpsi, function (opsi) {
                opsi.disabled = true;
                if (opsi === tombol) {
                    opsi.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-emerald-100 border-2 border-emerald-400 rounded-2xl px-3 py-3 font-bold text-base';
                } else {
                    opsi.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-white/60 border-2 border-violet-100 rounded-2xl px-3 py-3 font-bold text-base opacity-60';
                }
            });

            if (modeAktif === 'latihan') {
                bicara(araKuis, 'selebrasi', 'sukses', 'Hebat, latihan selesai! 🎉',
                    soalAktif.penjelasan + ' Ini latihan, jadi tidak ada koin. Tapi ilmumu bertambah!', true);
                lanjutBtn.classList.remove('hidden');
                lanjutBtn.focus();
                return;
            }

            var pertamaKali = !selesai[planetAktif.id + ':' + modeAktif];
            if (pertamaKali) {
                selesai[planetAktif.id + ':' + modeAktif] = true;
                dapatKoinBaru = true;
                window.tambahStarCoins(planetAktif.poin);

                if (jumlahSalah === 0) {
                    bicara(araKuis, 'selebrasi', 'sukses', 'Hebat sekali, Kapten! 🌟',
                        'Kamu dapat +' + planetAktif.poin + ' Koin Bintang! Langsung benar, sempurna! ' + soalAktif.penjelasan, true);
                } else {
                    bicara(araKuis, 'selebrasi', 'sukses', 'Kamu pantang menyerah, keren! 🎉',
                        'Berkat usahamu, kamu dapat +' + planetAktif.poin + ' Koin Bintang! ' + soalAktif.penjelasan, true);
                }
            } else {
                bicara(araKuis, 'selebrasi', 'sukses', 'Benar lagi, keren! 🌟',
                    soalAktif.penjelasan + ' Koin mode ini sudah kamu dapatkan, yuk coba mode lain!', true);
            }

            lanjutBtn.classList.remove('hidden');
            lanjutBtn.focus();
        } else {
            jumlahSalah++;
            tombol.disabled = true;
            tombol.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-rose-50 border-2 border-rose-300 rounded-2xl px-3 py-3 font-bold text-base opacity-70';

            var petunjuk = (soalAktif.umpan_salah && soalAktif.umpan_salah[huruf]) ||
                           'Coba baca soalnya sekali lagi dengan teliti ya, Kapten.';
            var sisa = pilihanEl.querySelectorAll('.opsi-jawaban:not([disabled])').length;
            var tambahan = (sisa === 1) ? ' Tinggal satu pilihan lagi, ayo semangat!' : '';
            var pembuka = PEMBUKA_SALAH[(jumlahSalah - 1) % PEMBUKA_SALAH.length];

            bicara(araKuis, 'semangat', 'semangat', pembuka, petunjuk + tambahan, false);

            var berikut = pilihanEl.querySelector('.opsi-jawaban:not([disabled])');
            if (berikut) { berikut.focus(); }
        }
    }

    // ---------- TOKO ----------
    function bangunToko() {
        if (sudahBangunToko || !tokoGrid || !ITEM_TOKO.length) { return; }
        tokoGrid.innerHTML = '';
        ITEM_TOKO.forEach(function (item) {
            var kartu = document.createElement('article');
            kartu.className = 'item-toko flex flex-col text-center bg-white border-2 border-violet-200 rounded-2xl p-3 transition-colors duration-200';
            kartu.setAttribute('data-item-id', item.id);
            kartu.setAttribute('data-harga', item.harga);
            kartu.setAttribute('data-nama', item.nama);

            kartu.innerHTML =
                '<div class="w-16 h-16 mx-auto mb-2">' + item.ikon + '</div>' +
                '<h4 class="text-sm font-black leading-tight">' + item.nama + '</h4>' +
                '<p class="text-xs font-bold opacity-70 leading-snug mt-1">' + item.deskripsi + '</p>' +
                '<div class="mt-2 inline-flex items-center justify-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full px-2 py-0.5 mx-auto">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/></svg>' +
                    '<span class="text-xs font-extrabold">' + item.harga + '</span>' +
                '</div>' +
                '<p class="item-status mt-1 text-xs font-extrabold text-violet-700">Tersedia</p>' +
                '<button type="button" class="btn-beli w-full mt-2 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2 rounded-full shadow transition-transform duration-150 hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300">Beli</button>';

            tokoGrid.appendChild(kartu);
        });
        sudahBangunToko = true;
    }

    function semuaItem() { return tokoGrid.querySelectorAll('.item-toko'); }

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
        if (!tokoModal) { return; }
        bangunToko();
        arazkaDiam();
        fokusSebelum = tokoBtn;
        belanjaBaru = false;
        tampilSaldoToko();

        var termurah = hargaTermurah();
        if (termurah === null) {
            bicara(araToko, 'selebrasi', 'sukses', 'Wow, kolektor sejati! 🏆',
                'Semua item di Toko Bintang sudah menjadi milikmu. Keren banget, Kapten!', true);
        } else if (koinTotal < termurah) {
            bicara(araToko, 'semangat', 'semangat', 'Selamat datang, Kapten! 🛍️',
                'Koin Bintangmu belum cukup untuk membeli item apa pun. Yuk selesaikan kuis lagi!', false);
        } else {
            bicara(araToko, 'halo', 'netral', 'Selamat datang di Toko Bintang! 🛍️',
                'Pilih item favoritmu, lalu tukarkan dengan Koin Bintang. Koinmu sekarang ' + koinTotal + ' ⭐', false);
        }

        tampilkanModal(tokoModal);
        var pertama = tokoGrid.querySelector('.btn-beli:not([disabled])');
        (pertama || tokoTutup).focus();
    }

    function tutupToko() {
        if (!tokoModal) { return; }
        sembunyikanModal(tokoModal);
        if (belanjaBaru) {
            arazkaBicara('Gaya sekali, Kapten! Sisa koinmu ' + koinTotal + ' ⭐', 'selebrasi', 8000);
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

    function pakaiBadgeEmas() {
        if (!coinBadge) { return; }
        coinBadge.classList.remove('bg-cosmic-accent', 'border-amber-300');
        coinBadge.classList.add('bg-amber-200', 'border-amber-500', 'ring-2', 'ring-amber-400');
    }

    function beliItem(kartu) {
        var id    = kartu.getAttribute('data-item-id');
        var harga = parseInt(kartu.getAttribute('data-harga'), 10) || 0;
        var nama  = kartu.getAttribute('data-nama');

        if (dimiliki[id]) { return; }

        if (koinTotal < harga) {
            var kurang = harga - koinTotal;
            bicara(araToko, 'semangat', 'semangat', 'Sabar ya, Kapten! 💪',
                'Koin Bintangmu belum cukup. Kamu masih butuh ' + kurang + ' koin lagi untuk ' + nama + '.', false);
            return;
        }

        window.ubahStarCoins(-harga);
        dimiliki[id] = true;
        belanjaBaru = true;
        tandaiItemTerbeli(kartu);
        if (id === 'badge-emas') { pakaiBadgeEmas(); }

        var jumlahMilik = Object.keys(dimiliki).length;
        var judul = JUDUL_BELI[(jumlahMilik - 1) % JUDUL_BELI.length];
        var teks  = nama + ' sekarang jadi milikmu! Sisa Koin Bintangmu ' + koinTotal + ' ⭐';

        bicara(araToko, 'selebrasi', 'sukses', judul, teks, true);

        var berikut = tokoGrid.querySelector('.btn-beli:not([disabled])');
        (berikut || tokoTutup).focus();
    }

    // ---------- EVENT ----------
    if (gridEl) {
        gridEl.addEventListener('click', function (event) {
            var kartu = event.target.closest('.planet-card');
            if (!kartu) { return; }
            if (!kartu.classList.contains('is-open')) {
                event.preventDefault();
                var level = kartu.getAttribute('data-level') || '5';
                window.showToast('Selesaikan misi sebelumnya dulu ya, Kapten! 🔒 Terbuka di Level ' + level + '.', 3200);
            }
        });
    }

    if (pilihanEl) {
        pilihanEl.addEventListener('click', function (event) {
            var tombol = event.target.closest('.opsi-jawaban');
            if (tombol && !tombol.disabled) { pilihJawaban(tombol); }
        });
    }

    if (tokoGrid) {
        tokoGrid.addEventListener('click', function (event) {
            var tombol = event.target.closest('.btn-beli');
            if (!tombol || tombol.disabled) { return; }
            beliItem(tombol.closest('.item-toko'));
        });
    }

    if (tokoBtn)   { tokoBtn.addEventListener('click', bukaToko); }
    if (tutupBtn)  { tutupBtn.addEventListener('click', tutupKuis); }
    if (lanjutBtn) { lanjutBtn.addEventListener('click', tutupKuis); }
    if (tokoTutup) { tokoTutup.addEventListener('click', tutupToko); }

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) { tutupKuis(); }
        });
    }
    if (tokoModal) {
        tokoModal.addEventListener('click', function (event) {
            if (event.target === tokoModal) { tutupToko(); }
        });
    }

    if (wBtn) {
        wBtn.addEventListener('click', function () {
            if (wBubble.classList.contains('hidden')) {
                arazkaBicara(tipBerikut(), 'halo', 8000);
            } else {
                arazkaDiam();
            }
        });
    }

    // ---------- Keyboard ----------
    document.addEventListener('keydown', function (event) {
        if (!modalAktif) { return; }
        if (event.key === 'Escape' || event.key === 'Esc') {
            event.preventDefault();
            if (modalAktif === modal) { tutupKuis(); }
            else if (modalAktif === tokoModal) { tutupToko(); }
            else { resetSemuaModal(); }
            return;
        }
        if (event.key !== 'Tab') { return; }

        var panelAktif = modalAktif.querySelector('.modal-panel');
        if (!panelAktif) { return; }
        var daftar = Array.prototype.filter.call(
            panelAktif.querySelectorAll('button:not([disabled])'),
            function (el) { return el.offsetParent !== null; }
        );
        if (daftar.length === 0) { return; }

        var pertama = daftar[0];
        var terakhir = daftar[daftar.length - 1];

        if (!panelAktif.contains(document.activeElement)) {
            event.preventDefault(); pertama.focus();
        } else if (event.shiftKey && document.activeElement === pertama) {
            event.preventDefault(); terakhir.focus();
        } else if (!event.shiftKey && document.activeElement === terakhir) {
            event.preventDefault(); pertama.focus();
        }
    });

    // ---------- CLEANUP ----------
    function resetSemuaModal() {
        [modal, tokoModal].forEach(function (m) {
            if (!m) { return; }
            m.classList.add('hidden');
            m.setAttribute('hidden', '');
            m.style.display = 'none';
        });
        document.body.classList.remove('overflow-hidden');
        document.documentElement.classList.remove('overflow-hidden');
        modalAktif = null;
        if (wBubble) {
            wBubble.classList.add('hidden');
            wBubble.style.display = 'none';
        }
        if (toastEl) {
            toastEl.classList.add('hidden');
            toastEl.style.display = 'none';
        }
    }

    window.addEventListener('pagehide', resetSemuaModal);
    window.addEventListener('beforeunload', resetSemuaModal);
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) { resetSemuaModal(); }
    });

    // ---------- INIT PLANET DETAIL (5 MODE) ----------
    (function initPlanetDetail() {
        if (!PLANET_INFO) { return; }

        var tombolMode = document.querySelectorAll('.btn-mode');
        if (!tombolMode.length) { return; }

        tombolMode.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var kartu  = btn.closest('.mode-card');
                if (!kartu) { return; }

                var modeId = kartu.getAttribute('data-mode-id');
                var modeData = GAME_MODES[modeId];

                if (!modeData) {
                    window.showToast('Mode ini belum tersedia ya, Kapten! 🚀', 3000);
                    return;
                }

                // KUIS + LATIHAN → modal existing
                if (modeData.type === 'kuis' || modeData.type === 'latihan') {
                    var sumber = (modeData.type === 'latihan') ? DATA_LATIHAN : DATA_KUIS;
                    var soal   = sumber[PLANET_INFO.id];

                    if (!soal) {
                        window.showToast('Soal mode ini sedang disiapkan ya, Kapten! 🚀', 3000);
                        return;
                    }

                    bukaKuisDenganData(
                        PLANET_INFO.id,
                        PLANET_INFO.nama,
                        modeData.reward || PLANET_INFO.poin,
                        PLANET_INFO.ikon,
                        soal,
                        modeData.type
                    );
                    return;
                }

                // PUZZLE, STORY, MATCHING → placeholder
                if (modeData.type === 'puzzle') {
                    window.showToast('🔤 Teka-Teki Kata segera hadir! Arazka sedang menyiapkan huruf-hurufnya.', 3500);
                    arazkaBicara('Mode Teka-Teki Kata sedang disiapkan, Kapten! 🔤', 'halo', 6000);
                    return;
                }
                if (modeData.type === 'story') {
                    window.showToast('📖 Misi Cerita Arazka segera hadir! Ceritanya masih ditulis.', 3500);
                    arazkaBicara('Cerita petualanganku masih ditulis nih, Kapten! 📖', 'halo', 6000);
                    return;
                }
                if (modeData.type === 'matching') {
                    window.showToast('🧩 Cocokkan Rumus segera hadir! Papan kartunya masih digambar.', 3500);
                    arazkaBicara('Papan cocok-cocokan masih digambar, Kapten! 🧩', 'halo', 6000);
                    return;
                }

                window.showToast('Mode ini belum siap, coba mode lain dulu ya! 🚀', 3000);
            });
        });
    })();

    // Reset saat DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', resetSemuaModal);
    } else {
        resetSemuaModal();
    }

    // Arazka menyapa saat halaman dimuat
    setTimeout(function () {
        if (!modalAktif && wBtn) {
            arazkaBicara('Halo, Kapten! Aku Arazka, mentor luar angkasamu. Pilih planet dan mulai petualanganmu! 🚀', 'halo', 9000);
        }
    }, 1200);
})();