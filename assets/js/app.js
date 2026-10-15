/* ============================================================
 * BRIGHT SPACE - assets/js/app.js
 * Full rewrite: sesi 10-15 soal + timer + result Arazka + outfit.
 * ============================================================ */
(function () {
    'use strict';

    var BS = window.BRIGHT_SPACE || {};
    var USER = BS.user || { nama: 'Kapten', star_coins: 120, topi: null, kacamata: null };
    var PLANET = BS.planet || null;
    var GAME_MODES = BS.gameModes || {};
    var SOAL_PER_MODE = BS.soalPerMode || {};
    var ITEM_TOKO = window.BRIGHT_SPACE_ITEMS || [];

    var IKON_BINTANG_KECIL = '<svg viewBox="0 0 24 24" class="w-full h-full"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.5"/></svg>';
    var PEMBUKA_BENAR = ['Benar! ✅', 'Tepat sekali! 🌟', 'Keren! ⭐', 'Hebat! 🎉'];
    var PEMBUKA_SALAH = ['Hampir tepat! 💪', 'Ayo coba lagi! 🌈', 'Semangat! 🚀'];
    var TIPS = [
        'Klik kartu planet untuk mulai petualangan! 🚀',
        'Misi wajib memberi koin besar, Side Quest untuk seru-seruan! 🎯',
        'Jawab semua benar = Bonus Sempurna +20%! 🏆',
        'Timer habis = sesi selesai. Kerjakan dengan tenang ya! ⏱️',
        'Tukar koinmu di Toko Bintang! 🛍️'
    ];

    // ============ DOM ============
    var coinEl = document.getElementById('star-coin-count');
    var coinBadge = document.getElementById('star-coin-badge');
    var toastEl = document.getElementById('toast');

    var modal, tahapSoal, tahapHasil, ikonEl, judulEl, timerBox, timerEl, progTeksEl, progBenarEl, progBarEl, tanyaEl, pilihanEl, lanjutBtn, tutupBtn;
    var hasilBenarEl, hasilTotalEl, hasilAkurasiEl, hasilBarEl, hasilRewardEl, hasilBonusEl, hasilKomentar, hasilSelesai, hasilUlang, hasilPercik, araHasil;
    var tokoModal, tokoGrid, tokoSaldo, tokoTutup, tokoBtn;
    var wBtn, wBubble, wTeks;
    var araKuis, araToko;

    // ============ STATE ============
    var koinTotal = parseInt(USER.star_coins, 10) || 0;
    var koinTampil = koinTotal;
    var timerKoin = null;
    var timerToast = null;
    var timerWidget = null;
    var timerSesi = null;
    var sisaWaktu = 0;
    var indeksTips = 0;
    var dimiliki = {};
    var modalAktif = null;
    var fokusSebelum = null;
    var sudahBangunToko = false;
    var sesi = null;

    function setMood(el, mood) {
        if (!el) return;
        el.removeAttribute('data-mood');
        void el.offsetWidth;
        if (mood) el.setAttribute('data-mood', mood);
    }

    // ============ STAR COINS ============
    function tampilSaldoToko() { if (tokoSaldo) tokoSaldo.textContent = koinTotal; }
    function denyutKoin() { if (!coinBadge) return; coinBadge.classList.remove('coin-pop'); void coinBadge.offsetWidth; coinBadge.classList.add('coin-pop'); }
    function animasiKoin() {
        if (!coinEl) return;
        clearInterval(timerKoin);
        if (koinTampil === koinTotal) return;
        var langkah = Math.max(1, Math.ceil(Math.abs(koinTotal - koinTampil) / 15));
        timerKoin = setInterval(function () {
            var sisa = koinTotal - koinTampil;
            koinTampil += Math.sign(sisa) * Math.min(Math.abs(sisa), langkah);
            coinEl.textContent = koinTampil;
            if (koinTampil === koinTotal) { clearInterval(timerKoin); denyutKoin(); }
        }, 40);
    }
       function ubahKoin(selisih) {
       selisih = parseInt(selisih, 10) || 0;
       if (!selisih) return;
       koinTotal = Math.max(0, koinTotal + selisih);
       tampilSaldoToko();
       var sk = document.getElementById('sidebar-koin');
       if (sk) sk.textContent = koinTotal;
       animasiKoin();
       var sk = document.getElementById('sidebar-koin');
if (sk) sk.textContent = koinTotal;
   }

    window.showToast = function (pesan, durasi) {
        if (!toastEl) return;
        toastEl.textContent = pesan;
        toastEl.classList.remove('hidden');
        toastEl.style.display = '';
        clearTimeout(timerToast);
        timerToast = setTimeout(function () {
            toastEl.classList.add('hidden');
            toastEl.style.display = 'none';
        }, durasi || 2400);
    };

    // ============ MODAL ============
    function tampilkanModal(root) {
        if (!root) return;
        modalAktif = root;
        root.classList.remove('hidden');
        root.removeAttribute('hidden');
        root.style.display = '';
        document.body.classList.add('overflow-hidden');
    }
    function sembunyikanModal(root) {
        if (!root) return;
        root.classList.add('hidden');
        root.setAttribute('hidden', '');
        root.style.display = 'none';
        if (modalAktif === root) modalAktif = null;
        document.body.classList.remove('overflow-hidden');
    }

    // ============ ARAZKA ============
    function arazkaBicara(teks, mood, durasi) {
        if (!wBubble) return;
        wTeks.textContent = teks;
        wBubble.classList.remove('hidden');
        wBubble.style.display = '';
        setMood(wBtn, mood || 'halo');
        clearTimeout(timerWidget);
        timerWidget = setTimeout(function () {
            wBubble.classList.add('hidden');
            wBubble.style.display = 'none';
        }, durasi || 7000);
    }
    function percikBintang(wadah) {
        if (!wadah) return;
        var arah = [[-40,-30],[40,-30],[-46,2],[46,2],[-30,34],[30,34],[0,-44],[0,42]];
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
        if (!ara || !ara.bubble) return;
        var warna = {
            netral: 'bg-violet-50 border-violet-200 text-cosmic-text',
            sukses: 'bg-emerald-50 border-emerald-300 text-emerald-900',
            semangat: 'bg-amber-50 border-amber-300 text-amber-900'
        };
        ara.bubble.className = 'relative flex-1 rounded-2xl border-2 p-3 ' + warna[tema];
        if (ara.judul) ara.judul.textContent = judul;
        if (ara.teks) ara.teks.textContent = teks;
        setMood(ara.avatar, mood);
        if (ara.percik) {
            ara.percik.innerHTML = '';
            if (meriah) percikBintang(ara.percik);
        }
    }

    // ============ TIMER ============
    function formatWaktu(detik) {
        var m = Math.floor(detik / 60);
        var s = detik % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }
    function mulaiTimer(detik) {
        clearInterval(timerSesi);
        sisaWaktu = detik;
        if (detik <= 0) { timerBox.classList.add('hidden'); return; }
        timerBox.classList.remove('hidden');
        timerEl.textContent = formatWaktu(sisaWaktu);
        timerEl.classList.remove('timer-warning');
        timerSesi = setInterval(function () {
            sisaWaktu--;
            timerEl.textContent = formatWaktu(Math.max(0, sisaWaktu));
            if (sisaWaktu <= 30) timerEl.classList.add('timer-warning');
            if (sisaWaktu <= 0) {
                clearInterval(timerSesi);
                window.showToast('⏰ Waktu habis!', 3000);
                tampilkanHasil(true);
            }
        }, 1000);
    }
    function stopTimer() { clearInterval(timerSesi); }

    // ============ SESI KUIS ============
    function mulaiSesi(modeData) {
        var soalList = SOAL_PER_MODE[modeData.id] || [];
        if (soalList.length === 0) {
            window.showToast('Soal sedang disiapkan ya! 🚀', 3000);
            return;
        }

        // Acak
        var pool = soalList.slice().sort(function () { return Math.random() - 0.5; });
        var jumlah = Math.min(modeData.jumlah_soal || 10, pool.length);

        sesi = {
            mode: modeData,
            soalList: pool.slice(0, jumlah),
            indeks: 0,
            benar: 0,
            salah: 0,
            sudahDijawab: false,
            totalSoal: jumlah,
            selesai: false
        };

        ikonEl.innerHTML = PLANET.ikon;
        judulEl.textContent = modeData.judul;

        tahapSoal.classList.remove('hidden');
        tahapHasil.classList.add('hidden');
        tutupBtn.style.display = '';

        renderSoal();
        tampilkanModal(modal);

        // Timer
        if (modeData.timer_detik > 0) {
            mulaiTimer(modeData.timer_detik);
        } else {
            timerBox.classList.add('hidden');
        }
    }

    function renderSoal() {
        if (!sesi) return;
        var soal = sesi.soalList[sesi.indeks];
        if (!soal) { tampilkanHasil(); return; }

        sesi.sudahDijawab = false;

        progTeksEl.textContent = 'Soal ' + (sesi.indeks + 1) + ' dari ' + sesi.totalSoal;
        progBenarEl.textContent = '✅ ' + sesi.benar;
        progBarEl.style.width = ((sesi.indeks) / sesi.totalSoal * 100) + '%';

        tanyaEl.textContent = soal.pertanyaan;
        pilihanEl.innerHTML = '';

        Object.keys(soal.pilihan).forEach(function (huruf) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('data-huruf', huruf);
            btn.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-white border-2 border-violet-200 rounded-2xl px-3 py-3 font-bold text-base hover:bg-cosmic-accent hover:border-amber-300';

            var bulat = document.createElement('span');
            bulat.className = 'shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-violet-500 text-white font-black';
            bulat.textContent = huruf;

            var teks = document.createElement('span');
            teks.textContent = soal.pilihan[huruf];

            btn.appendChild(bulat);
            btn.appendChild(teks);
            pilihanEl.appendChild(btn);
        });

        lanjutBtn.classList.add('hidden');
        lanjutBtn.textContent = (sesi.indeks === sesi.totalSoal - 1) ? 'Lihat Hasil 🏁' : 'Soal Berikutnya →';

        var sapaan = sesi.indeks === 0
            ? 'Sesi dimulai! Jawab dengan teliti ya! 🚀'
            : 'Soal ' + (sesi.indeks + 1) + ' 📝';
        bicara(araKuis, 'halo', 'netral', sapaan, 'Baca pelan-pelan dan pilih yang paling tepat!', false);
    }

    function pilihJawaban(btn) {
        if (!sesi || sesi.sudahDijawab) return;
        var soal = sesi.soalList[sesi.indeks];
        var huruf = btn.getAttribute('data-huruf');
        var semua = pilihanEl.querySelectorAll('.opsi-jawaban');
        sesi.sudahDijawab = true;

        if (huruf === soal.jawaban) {
            sesi.benar++;
            Array.prototype.forEach.call(semua, function (o) {
                o.disabled = true;
                if (o === btn) {
                    o.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-emerald-100 border-2 border-emerald-400 rounded-2xl px-3 py-3 font-bold text-base';
                } else {
                    o.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-white/60 border-2 border-violet-100 rounded-2xl px-3 py-3 font-bold text-base opacity-60';
                }
            });
            bicara(araKuis, 'selebrasi', 'sukses',
                PEMBUKA_BENAR[Math.floor(Math.random() * PEMBUKA_BENAR.length)],
                soal.penjelasan, true);
            progBenarEl.textContent = '✅ ' + sesi.benar;
        } else {
            sesi.salah++;
            Array.prototype.forEach.call(semua, function (o) {
                o.disabled = true;
                if (o.getAttribute('data-huruf') === soal.jawaban) {
                    o.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-emerald-50 border-2 border-emerald-400 rounded-2xl px-3 py-3 font-bold text-base';
                } else if (o === btn) {
                    o.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-rose-50 border-2 border-rose-300 rounded-2xl px-3 py-3 font-bold text-base opacity-70';
                } else {
                    o.className = 'opsi-jawaban w-full flex items-center gap-3 text-left bg-white/60 border-2 border-violet-100 rounded-2xl px-3 py-3 font-bold text-base opacity-60';
                }
            });
            var petunjuk = (soal.umpan_salah && soal.umpan_salah[huruf]) || 'Coba baca lagi ya.';
            bicara(araKuis, 'semangat', 'semangat',
                PEMBUKA_SALAH[Math.floor(Math.random() * PEMBUKA_SALAH.length)],
                petunjuk, false);
        }

        lanjutBtn.classList.remove('hidden');
    }

    function lanjutSoal() {
        if (!sesi || !sesi.sudahDijawab) return;
        sesi.indeks++;
        if (sesi.indeks >= sesi.totalSoal) {
            tampilkanHasil();
        } else {
            renderSoal();
        }
    }

    function tampilkanHasil(karenaHabisWaktu) {
        if (!sesi) return;
        sesi.selesai = true;
        stopTimer();

        var benar = sesi.benar;
        var total = sesi.totalSoal;
        var akurasi = Math.round(benar / total * 100);

        // Reward
        var reward = 0;
        var bonus = false;
        if (sesi.mode.reward > 0) {
            reward = Math.round(sesi.mode.reward * akurasi / 100);
            if (benar === total) {
                bonus = true;
                reward = Math.round(reward * 1.2);
            }
            ubahKoin(reward);
        }

        hasilBenarEl.textContent = benar;
        hasilTotalEl.textContent = total;
        hasilAkurasiEl.textContent = 'Akurasi: ' + akurasi + '%';
        hasilBarEl.style.width = '0%';
        setTimeout(function () { hasilBarEl.style.width = akurasi + '%'; }, 100);

        if (sesi.mode.reward > 0) {
            document.getElementById('hasil-reward-box').classList.remove('hidden');
            hasilRewardEl.textContent = reward;
            hasilBonusEl.classList.toggle('hidden', !bonus);
        } else {
            document.getElementById('hasil-reward-box').classList.add('hidden');
            hasilBonusEl.classList.add('hidden');
        }

        var komentar = '';
        if (karenaHabisWaktu) {
            komentar = 'Waktu habis! Tapi kamu sudah berusaha, Kapten! Coba lagi kapan saja! ⏰';
        } else if (akurasi === 100) {
            komentar = 'SEMPURNA, Kapten! 🏆 Semua jawaban benar!';
        } else if (akurasi >= 80) {
            komentar = 'Hebat! ' + benar + '/' + total + ' benar. Dikit lagi sempurna! 🌟';
        } else if (akurasi >= 60) {
            komentar = 'Bagus! ' + benar + '/' + total + ' benar. Terus semangat! 💪';
        } else if (akurasi >= 40) {
            komentar = 'Kerja bagus! ' + benar + '/' + total + ' benar. Ayo latihan lagi! 🌈';
        } else {
            komentar = 'Jangan sedih, Kapten! Setiap usaha itu hebat. Coba lagi kapan saja! 😊';
        }
        hasilKomentar.textContent = komentar;

        setMood(araHasil, akurasi >= 60 ? 'selebrasi' : 'semangat');
        if (akurasi >= 80) percikBintang(hasilPercik);
        else hasilPercik.innerHTML = '';

        tahapSoal.classList.add('hidden');
        tahapHasil.classList.remove('hidden');
        tutupBtn.style.display = 'none';
    }

    function tutupKuis() {
        if (!modal) return;
        if (sesi && !sesi.selesai && sesi.indeks < sesi.totalSoal) {
            if (!confirm('Yakin keluar? Progres akan hangus.')) return;
        }
        stopTimer();
        sesi = null;
        tahapSoal.classList.remove('hidden');
        tahapHasil.classList.add('hidden');
        tutupBtn.style.display = '';
        sembunyikanModal(modal);
        arazkaBicara('Semangat terus, Kapten! 🌟', 'halo', 5000);
    }

    function ulangSesi() {
        if (!sesi) return;
        var mode = sesi.mode;
        stopTimer();
        sesi = null;
        tahapSoal.classList.remove('hidden');
        tahapHasil.classList.add('hidden');
        tutupBtn.style.display = '';
        sembunyikanModal(modal);
        setTimeout(function () { mulaiSesi(mode); }, 200);
    }

    function selesaiSesi() {
        stopTimer();
        sesi = null;
        tahapSoal.classList.remove('hidden');
        tahapHasil.classList.add('hidden');
        tutupBtn.style.display = '';
        sembunyikanModal(modal);
        arazkaBicara('Koinmu sekarang ' + koinTotal + ' ⭐ Yuk pilih misi lain!', 'selebrasi', 6000);
    }

    // ============ TOKO ============
    function bangunToko() {
        if (sudahBangunToko || !tokoGrid || !ITEM_TOKO.length) return;
        tokoGrid.innerHTML = '';
        ITEM_TOKO.forEach(function (item) {
            var card = document.createElement('article');
            card.className = 'item-toko flex flex-col text-center bg-white border-2 border-violet-200 rounded-2xl p-3';
            card.setAttribute('data-item-id', item.id);
            card.setAttribute('data-harga', item.harga);
            card.setAttribute('data-nama', item.nama);
            card.innerHTML =
                '<div class="w-16 h-16 mx-auto mb-2">' + item.ikon + '</div>' +
                '<h4 class="text-sm font-black leading-tight">' + item.nama + '</h4>' +
                '<p class="text-xs font-bold opacity-70 mt-1">' + item.deskripsi + '</p>' +
                '<div class="mt-2 inline-flex items-center justify-center gap-1 bg-cosmic-accent border-2 border-amber-300 rounded-full px-2 py-0.5 mx-auto">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#FCD34D" stroke="#B45309" stroke-width="1.2"/></svg>' +
                    '<span class="text-xs font-extrabold">' + item.harga + '</span>' +
                '</div>' +
                '<p class="item-status mt-1 text-xs font-extrabold text-violet-700">Tersedia</p>' +
                '<button type="button" class="btn-beli w-full mt-2 bg-violet-500 hover:bg-violet-600 text-white font-extrabold text-sm py-2 rounded-full shadow">Beli</button>';
            tokoGrid.appendChild(card);
        });
        sudahBangunToko = true;
    }

    function bukaToko() {
        if (!tokoModal) return;
        bangunToko();
        fokusSebelum = tokoBtn;
        tampilSaldoToko();
        bicara(araToko, 'halo', 'netral', 'Selamat datang! 🛍️', 'Pilih item favoritmu. Koinmu ' + koinTotal + ' ⭐', false);
        tampilkanModal(tokoModal);
    }

    function tutupToko() {
        if (!tokoModal) return;
        sembunyikanModal(tokoModal);
    }

    function beliItem(card) {
        var id = card.getAttribute('data-item-id');
        var harga = parseInt(card.getAttribute('data-harga'), 10) || 0;
        var nama = card.getAttribute('data-nama');

        if (dimiliki[id]) return;
        if (koinTotal < harga) {
            bicara(araToko, 'semangat', 'semangat', 'Sabar! 💪', 'Kurang ' + (harga - koinTotal) + ' koin lagi untuk ' + nama + '.', false);
            return;
        }

        ubahKoin(-harga);
        dimiliki[id] = true;
        card.className = 'item-toko flex flex-col text-center bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-3';
        card.querySelector('.item-status').textContent = 'Terbeli ✓';
        card.querySelector('.item-status').className = 'item-status mt-1 text-xs font-extrabold text-emerald-700';
        var btn = card.querySelector('.btn-beli');
        btn.disabled = true;
        btn.textContent = 'Dimiliki ✓';
        btn.className = 'btn-beli w-full mt-2 bg-emerald-200 text-emerald-800 font-extrabold text-sm py-2 rounded-full cursor-default';

        // Simpan ke DB via AJAX sederhana
        fetch('api/beli_item.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ item_id: id })
        }).catch(function () {});

        // Pakai outfit di Arazka
        pakaiOutfitItem(id);

        bicara(araToko, 'selebrasi', 'sukses', 'Yeay! 🎉', nama + ' jadi milikmu! Sisa koin: ' + koinTotal, true);
    }

    function pakaiOutfitItem(itemId) {
        var svgWrappers = document.querySelectorAll('.arazka-wrap');
        if (itemId === 'topi-astronaut' || itemId === 'mahkota-galaksi') {
            svgWrappers.forEach(function (w) {
                var topiGroup = w.querySelector('.arazka-topi');
                if (topiGroup) topiGroup.style.display = '';
                var sub = w.querySelector('.arazka-topi-' + (itemId === 'topi-astronaut' ? 'astronaut' : 'mahkota'));
                if (sub) sub.style.display = '';
                // sembunyikan yang lain
                var other = w.querySelector('.arazka-topi-' + (itemId === 'topi-astronaut' ? 'mahkota' : 'astronaut'));
                if (other) other.style.display = 'none';
            });
        }
        if (itemId === 'kacamata-bintang') {
            svgWrappers.forEach(function (w) {
                var k = w.querySelector('.arazka-kacamata');
                if (k) k.style.display = '';
            });
        }
    }

    // Terapkan outfit awal dari DB
    function terapkanOutfitAwal() {
        if (USER.topi) pakaiOutfitItem(USER.topi);
        if (USER.kacamata) pakaiOutfitItem(USER.kacamata);
    }

    // ============ INISIALISASI ============
    function init() {
        modal = document.getElementById('kuis-modal');
        tahapSoal = document.getElementById('kuis-tahap-soal');
        tahapHasil = document.getElementById('kuis-tahap-hasil');
        ikonEl = document.getElementById('kuis-ikon');
        judulEl = document.getElementById('kuis-judul');
        timerBox = document.getElementById('kuis-timer-box');
        timerEl = document.getElementById('kuis-timer');
        progTeksEl = document.getElementById('kuis-progress-teks');
        progBenarEl = document.getElementById('kuis-progress-benar');
        progBarEl = document.getElementById('kuis-progress-bar');
        tanyaEl = document.getElementById('kuis-pertanyaan');
        pilihanEl = document.getElementById('kuis-pilihan');
        lanjutBtn = document.getElementById('kuis-lanjut');
        tutupBtn = document.getElementById('kuis-tutup');

        hasilBenarEl = document.getElementById('hasil-benar');
        hasilTotalEl = document.getElementById('hasil-total');
        hasilAkurasiEl = document.getElementById('hasil-akurasi');
        hasilBarEl = document.getElementById('hasil-bar');
        hasilRewardEl = document.getElementById('hasil-reward');
        hasilBonusEl = document.getElementById('hasil-bonus-teks');
        hasilKomentar = document.getElementById('hasil-komentar');
        hasilSelesai = document.getElementById('hasil-selesai');
        hasilUlang = document.getElementById('hasil-ulang');
        hasilPercik = document.getElementById('hasil-percik');
        araHasil = document.getElementById('arazka-hasil');

        tokoModal = document.getElementById('toko-modal');
        tokoGrid = document.getElementById('toko-grid');
        tokoSaldo = document.getElementById('toko-saldo');
        tokoTutup = document.getElementById('toko-tutup');
        tokoBtn = document.getElementById('btn-toko-bintang');

        wBtn = document.getElementById('arazka-btn');
        wBubble = document.getElementById('arazka-bubble');
        wTeks = document.getElementById('arazka-teks');

        araKuis = {
            avatar: document.getElementById('arazka-modal'),
            percik: document.getElementById('kuis-percik'),
            bubble: document.getElementById('kuis-bubble'),
            judul: document.getElementById('kuis-fb-judul'),
            teks: document.getElementById('kuis-fb-teks')
        };
        araToko = {
            avatar: document.getElementById('arazka-toko'),
            percik: document.getElementById('toko-percik'),
            bubble: document.getElementById('toko-bubble'),
            judul: document.getElementById('toko-fb-judul'),
            teks: document.getElementById('toko-fb-teks')
        };

        // ============ EVENT ============
        // Widget Arazka
        if (wBtn) {
            wBtn.addEventListener('click', function () {
                if (wBubble.classList.contains('hidden')) {
                    arazkaBicara(TIPS[indeksTips++ % TIPS.length], 'halo', 8000);
                } else {
                    wBubble.classList.add('hidden');
                    wBubble.style.display = 'none';
                }
            });
        }

        // Kategori card (planet-detail)
        document.querySelectorAll('.kategori-card').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var kat = btn.getAttribute('data-kategori');
                document.querySelectorAll('.mode-card').forEach(function (m) {
                    m.style.display = (m.getAttribute('data-kategori') === kat) ? '' : 'none';
                });
                document.getElementById('section-kategori').classList.add('hidden');
                document.getElementById('section-game').classList.remove('hidden');
                document.getElementById('judul-game').textContent = (kat === 'wajib' ? '⭐ Misi Wajib' : '💎 Side Quest');
            });
        });
        var btnGanti = document.getElementById('btn-ganti-kategori');
        if (btnGanti) {
            btnGanti.addEventListener('click', function () {
                document.getElementById('section-kategori').classList.remove('hidden');
                document.getElementById('section-game').classList.add('hidden');
            });
        }

        // Tombol mulai game
        document.querySelectorAll('.btn-mode').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var card = btn.closest('.mode-card');
                var modeId = card.getAttribute('data-mode-id');
                var modeData = GAME_MODES[modeId];
                if (!modeData) { window.showToast('Mode belum tersedia!', 3000); return; }

                if (modeData.type === 'kuis' || modeData.type === 'latihan' || modeData.type === 'story') {
                    fokusSebelum = btn;
                    mulaiSesi(modeData);
                } else {
                    window.showToast(modeData.judul + ' segera hadir! 🚀', 3500);
                    arazkaBicara('Mode ' + modeData.judul + ' sedang disiapkan! 🚀', 'halo', 5000);
                }
            });
        });

        // Pilihan jawaban
        if (pilihanEl) {
            pilihanEl.addEventListener('click', function (e) {
                var btn = e.target.closest('.opsi-jawaban');
                if (btn && !btn.disabled) pilihJawaban(btn);
            });
        }
        if (lanjutBtn) lanjutBtn.addEventListener('click', lanjutSoal);
        if (tutupBtn) tutupBtn.addEventListener('click', tutupKuis);
        if (hasilSelesai) hasilSelesai.addEventListener('click', selesaiSesi);
        if (hasilUlang) hasilUlang.addEventListener('click', ulangSesi);

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) tutupKuis();
            });
        }

        // Toko
        if (tokoBtn) tokoBtn.addEventListener('click', bukaToko);
        if (tokoTutup) tokoTutup.addEventListener('click', tutupToko);
        if (tokoModal) {
            tokoModal.addEventListener('click', function (e) {
                if (e.target === tokoModal) tutupToko();
            });
        }
        if (tokoGrid) {
            tokoGrid.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-beli');
                if (btn && !btn.disabled) beliItem(btn.closest('.item-toko'));
            });
        }

        // Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (modalAktif === modal) tutupKuis();
                else if (modalAktif === tokoModal) tutupToko();
            }
        });

        // Terapkan outfit
        terapkanOutfitAwal();

        // Sapaan Arazka personal
        setTimeout(function () {
            if (!modalAktif) {
                arazkaBicara('Halo, Kapten ' + USER.nama + '! Siap bertualang hari ini? 🚀', 'halo', 9000);
            }
        }, 1200);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();