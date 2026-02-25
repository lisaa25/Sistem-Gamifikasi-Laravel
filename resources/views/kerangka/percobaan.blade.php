@section('percobaan')
    <link rel="stylesheet" href="{{ asset('css/cubacuba.css') }}">
    <div id="kuis">
        <div class="countdown-container" id="countdown">
            <div class="countdown-number" id="count">3</div>
        </div>

        <div class="quiz-container fade-in" id="quiz" style="display: none;">
            <div class="question-info">
                Soal <span id="nomor-soal">1</span>/<span id="total-soal">10</span>
            </div>
            <div class="question-box">
                <div class="question-text" id="soal-teks">Elemen terkuat di bumi, yang tidak dapat dihancurkan kecuali oleh
                    elemen itu
                    sendiri adalah</div>
            </div>

            <div class="answers" id="tombol-jawaban">
                <button class="answer blue" data-jawaban="salah">Titanium</button>
                <button class="answer cyan" data-jawaban="benar">Berlian</button>
                <button class="answer orange" data-jawaban="salah">Emas</button>
                <button class="answer pink" data-jawaban="salah">Perak</button>
            </div>
        </div>

        <div id="notifikasi-container"
            style="position: fixed; bottom: 0; left: 0; width: 100%; background-color: rgba(0, 0, 0, 0.7); color: white; text-align: center; padding: 15px; display: none; z-index: 1000;">
            <div id="notifikasi-pesan" style="font-weight: bold;"></div>
            <div id="notifikasi-poin" style="font-size: 0.9em;"></div>
        </div>

        <div id="hasil-kuis" style="display: none; text-align: center; font-size: 20px; margin-top: 30px;">
            <h2>Kuis Selesai! <br>
                Berikut Hasil Kuismu:
            </h2>
            <p>Jumlah Soal Dijawab: <span id="total-dijawab">0</span></p>
            <p>Jumlah Benar: <span id="jumlah-benar">0</span></p>
            <p>Jumlah Salah: <span id="jumlah-salah">0</span></p>
            <p>Koin Didapatkan: <span id="total-koin">0</span></p>
            <div id="lanjut-level" style="display: none; margin-top: 20px;">
                <p style="color: green; font-weight: bold;">Selamat! Anda lulus dan dapat melanjutkan ke level berikutnya.
                </p>
                <button onclick="lanjutKeLevel()">Lanjut ke Level 2</button>
            </div>
            <div id="ulangi-kuis" style="display: none; margin-top: 20px;">
                <p style="color: red; font-weight: bold;">Maaf, nilai Anda di bawah 70. Silakan ulangi kuis ini.</p>
                <button onclick="resetKuis()">Ulangi Kuis</button>
            </div>
            <button onclick="resetKuis()">Ulangi Kuis</button> <br><br>
            <button onclick="tampilkanLeaderboard()">Lihat Leaderboard</button>
        </div>
    </div>

    <script>
        let count = 3;
        let nomorSoalSaatIni = 1;
        const totalSoal = 10;
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
        const totalKoinEl = document.getElementById('total-koin');
        const soalTeksEl = document.getElementById('soal-teks');
        const lanjutLevelEl = document.getElementById('lanjut-level');
        const ulangiKuisEl = document.getElementById('ulangi-kuis');
        let skorBenar = 0;
        let skorSalah = 0;
        let soalSudahDijawab = false;
        let percobaanKuis = 1; // Menyimpan informasi percobaan kuis

        const daftarSoal = [{
                teks: "Elemen terkuat di bumi, yang tidak dapat dihancurkan kecuali oleh elemen itu sendiri adalah",
                jawaban: "Berlian",
                pilihan: ["Titanium", "Berlian", "Emas", "Perak"]
            }, {
                teks: "Apa nama planet terbesar di tata surya kita?",
                jawaban: "Jupiter",
                pilihan: ["Mars", "Bumi", "Jupiter", "Saturnus"]
            }, {
                teks: "Siapa penemu lampu pijar?",
                jawaban: "Thomas Edison",
                pilihan: ["Nikola Tesla", "Thomas Edison", "Alexander Graham Bell", "Albert Einstein"]
            }, {
                teks: "Apa nama ibu kota Prancis?",
                jawaban: "Paris",
                pilihan: ["Berlin", "Madrid", "Paris", "Roma"]
            }, {
                teks: "Siapa penulis novel '1984'?",
                jawaban: "George Orwell",
                pilihan: ["Aldous Huxley", "George Orwell", "Ray Bradbury", "J.K. Rowling"]
            }, {
                teks: "Apa nama hewan tercepat di darat?",
                jawaban: "Cheetah",
                pilihan: ["Cheetah", "Kuda", "Harimau", "Serigala"]
            }, {
                teks: "Apa nama unsur kimia dengan simbol Au?",
                jawaban: "Emas",
                pilihan: ["Perak", "Emas", "Tembaga", "Besi"]
            }, {
                teks: "Siapa yang menemukan teori relativitas?",
                jawaban: "Albert Einstein",
                pilihan: ["Isaac Newton", "Albert Einstein", "Galileo Galilei", "Niels Bohr"]
            }, {
                teks: "Apa nama samudera terbesar di dunia?",
                jawaban: "Samudera Pasifik",
                pilihan: ["Samudera Atlantik", "Samudera Hindia", "Samudera Arktik", "Samudera Pasifik"]
            }, {
                teks: "Apa nama gunung tertinggi di dunia?",
                jawaban: "Gunung Everest",
                pilihan: ["Gunung Kilimanjaro", "Gunung Fuji", "Gunung Everest", "Gunung K2"]
            },
            // Tambahkan soal lainnya di sini
        ];
        totalSoalEl.textContent = daftarSoal.length;
        nomorSoalEl.textContent = nomorSoalSaatIni;
        soalTeksEl.textContent = daftarSoal[nomorSoalSaatIni - 1].teks;

        function tampilkanPilihanJawaban() {
            answersEl.innerHTML = '';
            daftarSoal[nomorSoalSaatIni - 1].pilihan.forEach(pilihan => {
                const tombol = document.createElement('button');
                tombol.classList.add('answer');
                tombol.textContent = pilihan;
                tombol.dataset.jawaban = (pilihan === daftarSoal[nomorSoalSaatIni - 1].jawaban) ? 'benar' : 'salah';
                answersEl.appendChild(tombol);
            });
        }
        tampilkanPilihanJawaban();

        const timer = setInterval(() => {
            if (count === 0) {
                clearInterval(timer);
                countdownBox.style.display = 'none';
                quizBox.style.display = 'block';
                quizBox.classList.add('show');
            } else {
                countdownEl.textContent = count;
                count--;
            }
        }, 1000);

        answersEl.addEventListener('click', function(event) {
            if (!soalSudahDijawab && event.target.classList.contains('answer')) {
                soalSudahDijawab = true;
                const target = event.target;
                const jawabanDipilih = target.textContent;
                const jawabanBenarSoalIni = daftarSoal[nomorSoalSaatIni - 1].jawaban;
                const semuaTombolJawaban = document.querySelectorAll('#tombol-jawaban .answer');

                let poinDidapatkan = 0;
                if (jawabanDipilih === jawabanBenarSoalIni) {
                    notifikasiPesanEl.textContent = "Benar!";
                    if (percobaanKuis === 1) {
                        poinDidapatkan = 10;
                        notifikasiPoinEl.textContent = "+10 Koin";
                    } else if (percobaanKuis === 2) {
                        poinDidapatkan = 5;
                        notifikasiPoinEl.textContent = "+5 Koin";
                    } else {
                        poinDidapatkan = 0; // Tidak ada poin untuk percobaan ketiga dan seterusnya
                        notifikasiPoinEl.textContent = "+0 Koin";
                    }
                    notifikasiContainerEl.style.backgroundColor = 'rgba(0, 128, 0, 0.8)';
                    skorBenar++;
                } else {
                    notifikasiPesanEl.textContent = "Salah!";
                    notifikasiPoinEl.textContent = "";
                    notifikasiContainerEl.style.backgroundColor = 'rgba(255, 99, 71, 0.8)';
                    semuaTombolJawaban.forEach(tombol => {
                        if (tombol.textContent === jawabanBenarSoalIni) {
                            tombol.style.backgroundColor = 'lightgreen';
                        }
                    });
                    target.style.backgroundColor = 'salmon';
                    skorSalah++;
                }

                notifikasiContainerEl.style.display = 'block';
                semuaTombolJawaban.forEach(tombol => {
                    tombol.disabled = true;
                });

                setTimeout(() => {
                    notifikasiContainerEl.style.display = 'none';
                    nomorSoalSaatIni++;
                    soalSudahDijawab = false;

                    if (nomorSoalSaatIni <= daftarSoal.length) {
                        nomorSoalEl.textContent = nomorSoalSaatIni;
                        soalTeksEl.textContent = daftarSoal[nomorSoalSaatIni - 1].teks;
                        tampilkanPilihanJawaban();
                    } else {
                        quizBox.style.display = 'none';
                        hasilKuisEl.style.display = 'block';
                        totalDijawabEl.textContent = skorBenar + skorSalah;
                        jumlahBenarEl.textContent = skorBenar;
                        jumlahSalahEl.textContent = skorSalah;
                        let totalPoinKuis = 0;
                        if (percobaanKuis === 1) {
                            totalPoinKuis = skorBenar * 10;
                        } else if (percobaanKuis === 2) {
                            totalPoinKuis = skorBenar * 5;
                        }
                        totalKoinEl.textContent = totalPoinKuis;

                        const nilaiAkhir = (skorBenar / totalSoal) * 100;
                        if (nilaiAkhir >= 70) {
                            lanjutLevelEl.style.display = 'block';
                            ulangiKuisEl.style.display = 'none';
                        } else {
                            lanjutLevelEl.style.display = 'none';
                            ulangiKuisEl.style.display = 'block';
                        }
                    }
                }, 2000);
            }
        });

        function resetKuis() {
            nomorSoalSaatIni = 1;
            skorBenar = 0;
            skorSalah = 0;
            soalSudahDijawab = false;
            nomorSoalEl.textContent = nomorSoalSaatIni;
            soalTeksEl.textContent = daftarSoal[nomorSoalSaatIni - 1].teks;
            tampilkanPilihanJawaban();
            hasilKuisEl.style.display = 'none';
            lanjutLevelEl.style.display = 'none';
            ulangiKuisEl.style.display = 'none';
            quizBox.style.display = 'block';
            percobaanKuis++; // Increment percobaan setiap kali kuis diulang
            // Reset percobaan jika ini adalah kuis pertama kali untuk level ini (perlu logika tambahan di luar kuis)
            if (percobaanKuis > 3) {
                percobaanKuis = 3; // Batasi percobaan mendapatkan koin
            }
        }

        function lanjutKeLevel() {
            // Logika untuk mengarahkan siswa ke level berikutnya (Level 2)
            alert("Melanjutkan ke Level 2!");
            // Anda bisa menggunakan window.location.href = '/level2'; atau cara navigasi lainnya
        }

        function tampilkanLeaderboard() {
            // Logika untuk menampilkan leaderboard
            alert("Menampilkan Leaderboard!");
            // Anda bisa mengarahkan siswa ke halaman leaderboard atau menampilkan modal
        }
    </script>
@endsection
