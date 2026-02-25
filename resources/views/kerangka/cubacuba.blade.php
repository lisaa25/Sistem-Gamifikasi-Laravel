@extends('layout.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cubacuba.css') }}">
<style>
    #notifikasi-container {
        z-index: 9999;
    }

    /* CSS untuk modal badge, jika Anda ingin menyatukannya di sini */
    .modal-badge-custom {
        display: none;
        /* Awalnya tersembunyi */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 10000;
        /* Pastikan lebih tinggi dari notifikasi */
    }

    .modal-content-badge-custom {
        background-color: #fefefe;
        margin: auto;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        width: 90%;
        max-width: 500px;
        position: relative;
        animation: fadeIn 0.3s ease-out;
        /* Animasi masuk */
    }

    .close-badge-custom {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-badge-custom:hover,
    .close-badge-custom:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-title-badge-custom {
        color: #4CAF50;
        font-size: 1.8em;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .badge-info-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .awarded-badge-image {
        width: 120px;
        height: 120px;
        object-fit: contain;
        border-radius: 50%;
        border: 5px solid #FFD700;
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
    }

    .badge-name {
        font-size: 1.5em;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .badge-description {
        font-size: 1em;
        color: #666;
        margin-bottom: 5px;
    }

    .badge-date {
        font-size: 0.9em;
        color: #999;
    }

    .modal-button-badge-custom {
        background-color: #4CAF50;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.1em;
        transition: background-color 0.3s ease;
    }

    .modal-button-badge-custom:hover {
        background-color: #45a049;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('cubacuba')
@include('partials._badge_modal') {{-- Pastikan ini tetap ada di sini --}}
<div id="kuis">
    <div class="countdown-container" id="countdown">
        <div class="countdown-number" id="count">3</div>
    </div>

    <div class="quiz-container fade-in" id="quiz" style="display: none;">
        <div class="question-info">
            Soal <span id="nomor-soal"></span>/<span id="total-soal"></span>
        </div>
        <div class="question-box">
            <div class="question-text" id="soal-teks"></div>
        </div>
        <div class="answers" id="tombol-jawaban">
            {{-- Tombol jawaban akan di-generate oleh JavaScript --}}
        </div>
    </div>

    <div id="notifikasi-container"
        style="position: fixed; bottom: 0; left: 0; width: 100%; background-color: rgba(0, 0, 0, 0.7); color: white; text-align: center; padding: 15px; display: none;">
        <div id="notifikasi-pesan" style="font-weight: bold;"></div>
        <div id="notifikasi-poin" style="font-size: 0.9em;"></div>
    </div>

    <div id="hasil-kuis" style="display: none; text-align: center; font-size: 20px; margin-top: 30px;">
        <h2>Kuis Selesai! <br>
            Berikut Hasil Kuismu: </h2>
        <p>Jumlah Soal Dijawab: <span id="total-dijawab">0</span></p>
        <p>Jumlah Benar: <span id="jumlah-benar">0</span></p>
        <p>Jumlah Salah: <span id="jumlah-salah">0</span></p>
        <p id="nilai-kuis">Nilai Kamu: <span id="nilai-kuis-akhir">0</span></p>
        <p>Koin dari Jawaban Benar: <span id="koin-jawaban-benar">0</span></p>
        <p>Koin Bonus (Lulus Kuis): <span id="koin-bonus-lulus">0</span></p>
        <p>Total Koin Didapatkan: <span id="total-koin-didapatkan">0</span></p>
        <button id="ulangi-kuis-button" onclick="resetKuis()">Ulangi Kuis</button>
        <a href="{{ route('leaderboard.siswa') }}" class="button-leaderboard">Lihat Leaderboard</a>
        <button id="lanjut-level-button" style="display: none;">Lanjut</button>
    </div>
    <audio id="correct-sound" src="{{ asset('sound/correct.mp3') }}" preload="auto"></audio>
    <audio id="incorrect-sound" src="{{ asset('sound/wrong.mp3') }}" preload="auto"></audio>

</div>
@endsection

@push('scripts')
<script>
    // Variabel-variabel yang DIJAMIN ada di DOM sejak awal load halaman,
    // atau yang digunakan secara global di banyak fungsi
    const daftarSoal = @json($daftarSoalUntukJs);
    const materiId = @json($materi->id ?? null);

    let nomorSoalSaatIni = 1;
    let skorBenar = 0;
    let skorSalah = 0;
    let soalSudahDijawab = false;

    const jawabanSiswaData = [];

    const countdownEl = document.getElementById('count');
    const countdownBox = document.getElementById('countdown');
    const quizBox = document.getElementById('quiz');
    const answersEl = document.getElementById('tombol-jawaban');
    const notifikasiContainerEl = document.getElementById('notifikasi-container');
    const notifikasiPesanEl = document.getElementById('notifikasi-pesan');
    const notifikasiPoinEl = document.getElementById('notifikasi-poin');
    const nomorSoalEl = document.getElementById('nomor-soal');
    const totalSoalEl = document.getElementById('total-soal');
    const hasilKuisEl = document.getElementById('hasil-kuis');
    const jumlahBenarEl = document.getElementById('jumlah-benar');
    const jumlahSalahEl = document.getElementById('jumlah-salah');
    const totalDijawabEl = document.getElementById('total-dijawab');

    const koinJawabanBenarEl = document.getElementById('koin-jawaban-benar');
    const koinBonusLulusEl = document.getElementById('koin-bonus-lulus');
    const totalKoinDidapatkanEl = document.getElementById('total-koin-didapatkan');

    const nilaiKuisAkhirEl = document.getElementById('nilai-kuis-akhir');
    const soalTeksEl = document.getElementById('soal-teks');

    // Ini adalah elemen-elemen yang juga harus ada saat DOMContentLoaded
    const lanjutLevelButton = document.getElementById('lanjut-level-button');
    const ulangiKuisButton = document.getElementById('ulangi-kuis-button');
    const lihatLeaderboardButton = document.querySelector('.button-leaderboard');

    const correctSound = document.getElementById('correct-sound');
    const incorrectSound = document.getElementById('incorrect-sound');

    // ==== BAGIAN INI DIHAPUS/TIDAK ADA LAGI KARENA MENYEBABKAN ERROR 'null' ====
    // const badgeModalButton = document.getElementById('badgeModalButton');
    // const closeBadgeModalButton = document.getElementById('closeBadgeModalButton');
    // ===========================================================================


    document.addEventListener('DOMContentLoaded', () => {
        console.log('Daftar Soal yang diterima dari backend (awal load):', daftarSoal);
        if (daftarSoal && daftarSoal.length > 0) {
            daftarSoal.forEach((soal, index) => {
                if (!soal.id) {
                    console.error(`ERROR: Soal pada indeks ${index} tidak memiliki ID!`, soal);
                }
                if (!soal.pilihan || soal.pilihan.length === 0) {
                    console.error(
                        `ERROR: Soal pada indeks ${index} tidak memiliki pilihan jawaban atau kosong!`,
                        soal);
                }
                if (!soal.teks) {
                    console.error(`ERROR: Soal pada indeks ${index} tidak memiliki teks soal!`, soal);
                }
                if (!soal.jawaban) {
                    console.error(`ERROR: Soal pada indeks ${index} tidak memiliki jawaban benar!`,
                        soal);
                }
            });
            totalSoalEl.textContent = daftarSoal.length;
            startCountdown();
        } else {
            countdownBox.style.display = 'none';
            quizBox.style.display = 'flex';
            quizBox.classList.add('show');
            soalTeksEl.textContent = "Maaf, belum ada soal kuis untuk materi ini.";
            answersEl.innerHTML = "<p>Silakan kembali ke daftar materi.</p>";
            const questionInfo = quizBox.querySelector('.question-info');
            if (questionInfo) {
                questionInfo.style.display = 'none';
            }
        }
    });

    function tampilkanPilihanJawaban() {
        answersEl.innerHTML = '';
        const currentSoal = daftarSoal[nomorSoalSaatIni - 1];

        console.log(`--- Menampilkan Soal #${nomorSoalSaatIni} ---`);
        console.log('Objek currentSoal:', currentSoal);

        if (!currentSoal) {
            console.error(
                "Kesalahan: currentSoal tidak ditemukan untuk nomor soal ini. Mungkin ada masalah dengan daftarSoal."
            );
            answersEl.innerHTML = '<p>Soal tidak dapat dimuat.</p>';
            return;
        }

        if (!currentSoal.pilihan || currentSoal.pilihan.length === 0) {
            console.error("Kesalahan: Pilihan jawaban tidak ditemukan atau kosong untuk soal ID:", currentSoal.id,
                currentSoal);
            answersEl.innerHTML = '<p>Pilihan jawaban tidak tersedia untuk soal ini.</p>';
            return;
        }

        const colors = ['blue', 'cyan', 'orange', 'pink'];
        let colorIndex = 0;

        currentSoal.pilihan.forEach(pilihan => {
            const tombol = document.createElement('button');
            tombol.classList.add('answer');
            tombol.classList.add(colors[colorIndex % colors.length]);
            tombol.textContent = pilihan;
            tombol.addEventListener('click', handleJawaban);
            answersEl.appendChild(tombol);
            colorIndex++;
        });
    }

    function tampilkanSoalSaatIni() {
        hasilKuisEl.style.display = 'none';

        if (nomorSoalSaatIni <= daftarSoal.length) {
            countdownBox.style.display = 'none';
            quizBox.style.display = 'flex';
            quizBox.classList.add('show');
            quizBox.classList.remove('fade-in');

            nomorSoalEl.textContent = nomorSoalSaatIni;
            totalSoalEl.textContent = daftarSoal.length;
            soalTeksEl.textContent = daftarSoal[nomorSoalSaatIni - 1].teks;
            tampilkanPilihanJawaban();
        } else {
            tampilkanHasilKuis();
        }
    }

    function handleJawaban(event) {
        if (soalSudahDijawab) return;

        soalSudahDijawab = true;
        const tombolTerpilih = event.target;
        const currentSoal = daftarSoal[nomorSoalSaatIni - 1];
        const jawabanBenar = currentSoal.jawaban;
        const pilihanTerpilih = tombolTerpilih.textContent;

        let isCorrect = (pilihanTerpilih === jawabanBenar);

        let soalIdUntukBackend = null;
        if (currentSoal && typeof currentSoal.id !== 'undefined' && currentSoal.id !== null) {
            soalIdUntukBackend = currentSoal.id;
        } else {
            console.error(`ERROR: Soal ID tidak ditemukan untuk soal nomor ${nomorSoalSaatIni}. currentSoal:`,
                currentSoal);
            alert("Ada masalah dengan data soal. Kuis mungkin tidak bisa disimpan.");
            soalSudahDijawab = false; // Izinkan user coba lagi atau refresh
            return; // Jangan lanjutkan tanpa ID soal yang valid
        }

        console.log(`Mencatat jawaban untuk Soal ID: ${soalIdUntukBackend}`);
        console.log(`Jawaban User: "${pilihanTerpilih}", Jawaban Benar: "${jawabanBenar}", Is Correct: ${isCorrect}`);

        const jawabanObject = {
            soal_id: soalIdUntukBackend,
            jawaban_user: pilihanTerpilih,
            is_correct: isCorrect
        };

        const existingAnswerIndex = jawabanSiswaData.findIndex(j => j.soal_id === soalIdUntukBackend);

        if (existingAnswerIndex !== -1) {
            jawabanSiswaData[existingAnswerIndex] = jawabanObject;
            console.log(`Jawaban Soal ID ${soalIdUntukBackend} diupdate.`);
        } else {
            jawabanSiswaData.push(jawabanObject);
            console.log(`Jawaban Soal ID ${soalIdUntukBackend} ditambahkan.`);
        }

        notifikasiContainerEl.style.display = 'block';
        if (isCorrect) {
            notifikasiPesanEl.textContent = "Jawaban Benar!";
            notifikasiPoinEl.textContent = "+10 Koin"; // Ini adalah koin per soal
            skorBenar++;
            tombolTerpilih.classList.add('correct');
            if (correctSound) {
                correctSound.currentTime = 0;
                correctSound.play().catch(e => console.error("Error playing correct sound:", e));
            }
        } else {
            notifikasiPesanEl.textContent = "Jawaban Salah!";
            notifikasiPoinEl.textContent = "+0 Koin";
            skorSalah++;
            tombolTerpilih.classList.add('incorrect');

            answersEl.querySelectorAll('.answer').forEach(button => {
                if (button.textContent === jawabanBenar) {
                    button.classList.add('correct');
                }
            });
            if (incorrectSound) {
                incorrectSound.currentTime = 0;
                incorrectSound.play().catch(e => console.error("Error playing incorrect sound:", e));
            }
        }

        answersEl.querySelectorAll('.answer').forEach(button => {
            button.removeEventListener('click', handleJawaban);
            button.disabled = true;
        });

        setTimeout(() => {
            notifikasiContainerEl.style.display = 'none';
            soalSudahDijawab = false;

            answersEl.querySelectorAll('.answer').forEach(button => {
                button.disabled = false;
                button.classList.remove('correct', 'incorrect');
            });

            nomorSoalSaatIni++;
            tampilkanSoalSaatIni();
        }, 2000);
    }

    async function kirimHasilKuisKeBackend(data) {
        try {
            console.log('Payload yang akan dikirim ke backend:', data);
            const response = await fetch("/simpan-hasil-kuis", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const contentType = response.headers.get("content-type");
                let errorData;
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    errorData = await response.json();
                    console.error('Backend Error (JSON Response):', errorData);
                    throw new Error(errorData.message || JSON.stringify(errorData.errors || errorData));
                } else {
                    const errorText = await response.text();
                    console.error('Backend Error (Non-JSON/Text Response):', errorText);
                    throw new Error(
                        'Server mengembalikan respon yang tidak valid. Mohon periksa log server. Status: ' +
                        response.status + '. Respon: ' + errorText);
                }
            }

            return await response.json();
        } catch (error) {
            console.error("Terjadi kesalahan saat mengirim hasil kuis:", error);
            alert("Terjadi kesalahan saat menyimpan hasil kuis. Silakan coba lagi. (" + error.message + ")");
            return {
                success: false,
                message: error.message || "Kesalahan jaringan."
            };
        }
    }

    // --- FUNGSI BARU UNTUK MENUTUP MODAL BADGE ---
    function closeBadgeModal() {
        const badgeModal = document.getElementById('badgeAwardedModal'); // Menggunakan ID yang benar
        if (badgeModal) {
            // Untuk animasi fade-out
            badgeModal.style.opacity = 0;
            setTimeout(() => {
                badgeModal.style.display = 'none';
                badgeModal.style.opacity = 1; // Reset opacity untuk kemunculan berikutnya
            }, 300); // Sesuaikan dengan durasi transisi CSS
        }
    }
    // --- AKHIR FUNGSI BARU ---

    async function tampilkanHasilKuis() {
        quizBox.style.display = 'none';
        hasilKuisEl.style.display = 'block';
        hasilKuisEl.classList.add('show');

        const totalSoal = daftarSoal.length;
        const nilaiKuis = totalSoal > 0 ? (skorBenar / totalSoal) * 100 : 0;

        totalDijawabEl.textContent = skorBenar + skorSalah;
        jumlahBenarEl.textContent = skorBenar;
        jumlahSalahEl.textContent = skorSalah;
        nilaiKuisAkhirEl.textContent = Math.round(nilaiKuis);

        // Sembunyikan semua tombol di awal, nanti akan ditampilkan berdasarkan kondisi
        ulangiKuisButton.style.display = 'none';
        if (lihatLeaderboardButton) lihatLeaderboardButton.style.display = 'none';
        lanjutLevelButton.style.display = 'none';

        console.log('--- Memulai tampilkanHasilKuis ---');
        console.log('Nilai Kuis Akhir (Frontend Calc):', Math.round(nilaiKuis));
        console.log('Materi ID:', materiId);
        console.log('Jawaban Siswa yang akan dikirim (FINAL):', jawabanSiswaData);

        if (materiId === null) {
            console.error("Error: Materi ID tidak ditemukan. Hasil kuis tidak dapat disimpan.");
            alert("Kesalahan: ID Materi tidak tersedia. Hasil kuis tidak dapat disimpan.");
            ulangiKuisButton.style.display = 'inline-block';
            if (lihatLeaderboardButton) lihatLeaderboardButton.style.display = 'inline-block';
            koinJawabanBenarEl.textContent = '0';
            koinBonusLulusEl.textContent = '0';
            totalKoinDidapatkanEl.textContent = '0';
            console.log('Kondisi: Materi ID null. Tombol ditampilkan: Ulangi & Leaderboard.');
            return;
        }

        const hasilBackend = await kirimHasilKuisKeBackend({
            materi_id: materiId,
            skor_benar: skorBenar,
            skor_salah: skorSalah,
            nilai_kuis: nilaiKuis,
            jawaban_siswa: jawabanSiswaData
        });

        console.log('Respons dari Backend (hasilBackend):', hasilBackend);

        if (hasilBackend.success) {
            koinJawabanBenarEl.textContent = hasilBackend.koin_dari_jawaban || 0;
            koinBonusLulusEl.textContent = hasilBackend.koin_bonus || 0;
            totalKoinDidapatkanEl.textContent = hasilBackend.koin_didapatkan || 0;

            if (hasilBackend.can_continue) {
                console.log(
                    'Kondisi: LULUS (can_continue TRUE dari backend). Menampilkan Lanjut Level & Lihat Leaderboard.'
                );
                if (lanjutLevelButton) {
                    lanjutLevelButton.style.display = 'inline-block';
                    lanjutLevelButton.onclick = function() {
                        alert('Kuis Selesai dengan nilai ' + Math.round(nilaiKuis) +
                            '! Lanjut ke Level Berikutnya!');
                        window.location.href = '/pembelajaran';
                    };
                } else {
                    console.error("Error: lanjutLevelButton is null after backend response.");
                }
                ulangiKuisButton.style.display = 'none';
            } else {
                console.log(
                    'Kondisi: TIDAK LULUS (can_continue FALSE dari backend). Menampilkan Ulangi Kuis & Leaderboard.'
                );
                lanjutLevelButton.style.display = 'none';
                ulangiKuisButton.style.display = 'inline-block';
            }

            if (lihatLeaderboardButton) lihatLeaderboardButton.style.display = 'inline-block';

            // --- START: PERBAIKAN UTAMA DI SINI ---
            // Mendapatkan elemen modal badge HANYA ketika dibutuhkan (setelah hasil kuis dari backend)
            const badgeModal = document.getElementById('badgeAwardedModal'); // <--- PENTING: GANTI ID DI SINI
            const badgeImage = document.getElementById('badgeImage');
            const badgeName = document.getElementById('badgeName');
            const badgeDescription = document.getElementById('badgeDescription');
            const badgeDate = document.getElementById('badgeDate'); // Tambahkan ini

            // Dapatkan tombol penutup 'X' dan 'Oke!' dari dalam modal
            const closeSpanButton = badgeModal ? badgeModal.querySelector('.close-badge-custom') : null;
            const okeButton = badgeModal ? badgeModal.querySelector('.modal-button-badge-custom') : null;

            console.log('Debug: badgeAwardedModal element found?', !!badgeModal);
            console.log('Debug: closeSpanButton element found?', !!closeSpanButton);
            console.log('Debug: okeButton element found?', !!okeButton);


            if (hasilBackend.newly_awarded_badge) {
                console.log('Lencana baru didapatkan:', hasilBackend.newly_awarded_badge);

                if (badgeImage) badgeImage.src = hasilBackend.newly_awarded_badge.gambar;
                if (badgeName) badgeName.textContent = hasilBackend.newly_awarded_badge.nama;
                if (badgeDescription) badgeDescription.textContent = hasilBackend.newly_awarded_badge.deskripsi;
                if (badgeDate && hasilBackend.newly_awarded_badge.tanggal_dicapai) {
                    badgeDate.textContent = 'Dicapai: ' + hasilBackend.newly_awarded_badge.tanggal_dicapai;
                } else if (badgeDate) {
                    badgeDate.textContent = ''; // Kosongkan jika tidak ada tanggal
                }

                setTimeout(() => {
                    if (badgeModal) {
                        badgeModal.style.display = 'flex'; // Tampilkan modal
                        // Jika Anda punya CSS transition untuk opacity pada .modal-badge-custom,
                        // Anda bisa tambahkan ini setelah display: flex;
                        // setTimeout(() => { badgeModal.style.opacity = 1; }, 50);
                    } else {
                        console.error("Error: badgeAwardedModal is null during show animation attempt.");
                    }
                }, 1000); // Tunda 1 detik agar modal hasil kuis terlihat dulu

                // Attach event listener ke tombol 'X'
                if (closeSpanButton) {
                    closeSpanButton.onclick = () => closeBadgeModal();
                } else {
                    console.error("Error: closeSpanButton (X) is null, cannot attach onclick handler.");
                }
                // Tombol 'Oke!' sudah punya onclick="closeBadgeModal()" di HTML, jadi tidak perlu di sini
                // --- AKHIR PERBAIKAN UTAMA ---

            }

        } else {
            console.error('Frontend menerima error dari backend:', hasilBackend.message, hasilBackend.errors);
            console.log('Kondisi: Backend Error. Menampilkan Ulangi Kuis & Leaderboard.');
            lanjutLevelButton.style.display = 'none';
            ulangiKuisButton.style.display = 'inline-block';
            if (lihatLeaderboardButton) lihatLeaderboardButton.style.display = 'inline-block';
            alert('Gagal memproses hasil kuis di server. Anda bisa mengulang kuis.\nDetail: ' + (hasilBackend
                .message || ''));
            koinJawabanBenarEl.textContent = '0';
            koinBonusLulusEl.textContent = '0';
            totalKoinDidapatkanEl.textContent = '0';
        }

        setTimeout(() => {
            console.log('Display style Lanjut Level:', lanjutLevelButton ? lanjutLevelButton.style.display :
                'Element not found');
            console.log('Display style Ulangi Kuis:', ulangiKuisButton ? ulangiKuisButton.style.display :
                'Element not found');
            if (lihatLeaderboardButton) {
                console.log('Display style Lihat Leaderboard:', lihatLeaderboardButton.style.display);
            }
        }, 100);
        console.log('--- Selesai tampilkanHasilKuis ---');
    }

    function startCountdown() {
        countdownBox.style.display = 'block';
        quizBox.style.display = 'none';
        quizBox.classList.remove('show');

        let currentCount = 3;
        countdownEl.textContent = currentCount;

        let timer = setInterval(() => {
            currentCount--;
            countdownEl.textContent = currentCount;

            if (currentCount <= 0) {
                clearInterval(timer);
                countdownBox.style.display = 'none';
                tampilkanSoalSaatIni();
            }
        }, 1000);
    }

    function resetKuis() {
        nomorSoalSaatIni = 1;
        skorBenar = 0;
        skorSalah = 0;
        soalSudahDijawab = false;
        jawabanSiswaData.length = 0;

        hasilKuisEl.style.display = 'none';
        hasilKuisEl.classList.remove('show');
        // Pastikan tombol-tombol disembunyikan/ditampilkan sesuai kondisi awal reset
        lanjutLevelButton.style.display = 'none';
        ulangiKuisButton.style.display = 'none';
        if (lihatLeaderboardButton) lihatLeaderboardButton.style.display = 'none';

        koinJawabanBenarEl.textContent = '0';
        koinBonusLulusEl.textContent = '0';
        totalKoinDidapatkanEl.textContent = '0';

        if (daftarSoal && daftarSoal.length > 0) {
            startCountdown();
        } else {
            countdownBox.style.display = 'none';
            quizBox.style.display = 'flex';
            quizBox.classList.add('show');

            soalTeksEl.textContent = "Maaf, belum ada soal kuis untuk materi ini.";
            answersEl.innerHTML = "<p>Silakan kembali ke daftar materi.</p>";

            const questionInfo = quizBox.querySelector('.question-info');
            if (questionInfo) {
                questionInfo.style.display = 'none';
            }
        }
    }
</script>
@endpush