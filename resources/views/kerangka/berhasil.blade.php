@extends('layout.master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/berhasil.css') }}">
    {{-- Pastikan PDF.js dimuat dengan benar. Anda mungkin perlu mengunduh dan menyimpannya secara lokal jika CDN bermasalah. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    {{-- Judul Materi dinamis --}}
    <h2 class="judul">Materi: {{ $materi->judul_materi }}</h2>

    <div class="materi-container">
        <img src="{{ asset('img/game/koko_bg.png') }}" class="character-img left" alt="Karakter">

        <canvas id="pdf-canvas"></canvas>

        <img src="{{ asset('img/game/koko_bg.png') }}" class="character-img right" alt="Karakter">
    </div>

    <div class="nav-buttons">
        <button id="prevPageBtn" onclick="prevPage()">
            <span>&larr;</span> Sebelumnya
        </button>
        <span>Slide <span id="page-num"></span> / <span id="page-count"></span></span>
        <button id="nextPageBtn" onclick="nextPage()">
            Selanjutnya <span>&rarr;</span>
        </button>
    </div>
 
    {{-- Kontainer Pesan untuk Slide Terakhir --}}
    <div id="lastSlideMessage" class="last-slide-message" style="display: none;">
        <p>Anda telah mencapai akhir materi! Silakan kerjakan kuis untuk melanjutkan.</p>
    </div>

    {{-- Kontainer Kuis: Akan ditampilkan/disembunyikan oleh JavaScript --}}
    <div id="kuis-container" class="kuis-container" style="display: none;">
        <h3>Kuis untuk Materi Ini</h3>

        {{-- Logika Tampilan Tombol Berdasarkan Variabel PHP --}}
        @if ($hasPassedQuiz)
            <div class="alert alert-success text-center">
                Selamat! Anda sudah lulus kuis materi ini.
            </div>
            {{-- Tombol untuk melihat hasil kuis sebelumnya, mengarah ke route baru --}}
            <a href="{{ route('kuis.history', $materi->id) }}" class="btn btn-info">Lihat Hasil Kuis Anda</a>
        @else
            {{-- Jika belum lulus, cek apakah sudah pernah mencoba --}}
            @if ($userHasilKuis->isNotEmpty())
                <p class="mt-3">Anda sudah pernah mengerjakan kuis ini. Nilai tertinggi Anda:
                    {{ $userHasilKuis->max('nilai_kuis') ?? 0 }}.
                    Coba lagi untuk lulus!
                </p>
                {{-- Tombol untuk melihat riwayat kuis jika sudah pernah mencoba (opsional, bisa digabung) --}}
                <a href="{{ route('kuis.history', $materi->id) }}" class="btn btn-info">Lihat Riwayat Kuis</a>
            @else
                <p class="mt-3">Belum pernah mengerjakan kuis ini.</p>
            @endif
            {{-- Tombol untuk memulai kuis --}}
            <a href="{{ route('siswa.kuis.show', $materi->id) }}" class="btn btn-success">🎯 Mulai Kuis</a>
        @endif
    </div>

    <script>
        // Set workerSrc untuk PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const url = "{{ asset('storage/' . $materi->file_pdf) }}";
        let pdfDoc = null,
            pageNum = 1;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const kuisContainer = document.getElementById('kuis-container');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const lastSlideMessage = document.getElementById('lastSlideMessage');

        // Fungsi untuk memuat dokumen PDF
        function loadPdf() {
            if (typeof pdfjsLib === 'undefined') {
                console.error("PDF.js library not loaded!");
                // Menggunakan modal custom daripada alert()
                showCustomAlert("Terjadi masalah saat memuat library PDF.js. Mohon coba refresh halaman.");
                return;
            }

            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                pdfDoc = pdf;
                document.getElementById('page-count').textContent = pdfDoc.numPages;
                renderPage(pageNum);
            }).catch(function(error) {
                // Menggunakan modal custom daripada alert()
                showCustomAlert('Gagal memuat materi PDF. Pastikan file tersedia dan URL benar. Error: ' + error
                    .message);
                console.error('Error loading PDF:', error);
            });
        }

        // Fungsi untuk merender halaman PDF
        function renderPage(num) {
            if (!pdfDoc) return;

            // Pastikan nomor halaman valid
            if (num < 1 || num > pdfDoc.numPages) {
                return;
            }

            pdfDoc.getPage(num).then(function(page) {
                const fixedWidth = 800; // Lebar tetap untuk canvas
                const viewport = page.getViewport({
                    scale: 1
                });
                const scale = fixedWidth / viewport.width;
                const scaledViewport = page.getViewport({
                    scale
                });

                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: scaledViewport
                };
                const renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    document.getElementById('page-num').textContent = pageNum;
                    updateNavigationButtons(); // Perbarui status tombol setelah render

                    // Logika tampilan kontainer kuis dan pesan: hanya di slide terakhir
                    if (pageNum === pdfDoc.numPages) {
                        kuisContainer.style.display = 'block'; // Tampilkan seluruh kontainer kuis
                        lastSlideMessage.style.display = 'block'; // Tampilkan pesan slide terakhir
                    } else {
                        kuisContainer.style.display = 'none'; // Sembunyikan seluruh kontainer kuis
                        lastSlideMessage.style.display = 'none'; // Sembunyikan pesan slide terakhir
                    }
                });
            });
        }

        // Fungsi untuk memperbarui status tombol navigasi
        function updateNavigationButtons() {
            if (!pdfDoc) return;

            // Tombol Sebelumnya
            prevPageBtn.disabled = (pageNum === 1);

            // Tombol Selanjutnya
            if (pageNum === pdfDoc.numPages) {
                nextPageBtn.disabled = true; // Nonaktifkan tombol selanjutnya
                nextPageBtn.textContent = 'Selesai Materi'; // Ubah teks tombol
                nextPageBtn.classList.add('finished-materi-btn'); // Tambahkan kelas untuk styling
            } else {
                nextPageBtn.disabled = false;
                nextPageBtn.innerHTML = 'Selanjutnya <span>&rarr;</span>'; // Kembalikan teks asli
                nextPageBtn.classList.remove('finished-materi-btn'); // Hapus kelas styling
            }
        }

        // Fungsi navigasi halaman
        function nextPage() {
            if (pdfDoc === null || pageNum >= pdfDoc.numPages) {
                // Jika sudah di halaman terakhir, jangan lakukan apa-apa atau arahkan ke kuis
                // Untuk sekarang, biarkan tombol dinonaktifkan
                return;
            }
            pageNum++;
            renderPage(pageNum);
        }

        function prevPage() {
            if (pdfDoc === null || pageNum <= 1) return;
            pageNum--;
            renderPage(pageNum);
        }

        // Jalankan fungsi saat DOM selesai dimuat
        document.addEventListener('DOMContentLoaded', loadPdf);

        // --- Custom Alert/Modal (Pengganti alert() dan confirm()) ---
        function showCustomAlert(message) {
            // Buat elemen modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            `;

            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background-color: #fff;
                padding: 25px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                text-align: center;
                max-width: 400px;
                width: 90%;
                color: #333;
                font-family: 'Inter', sans-serif;
            `;

            const messageText = document.createElement('p');
            messageText.textContent = message;
            messageText.style.marginBottom = '20px';
            messageText.style.fontSize = '1.1rem';

            const okButton = document.createElement('button');
            okButton.textContent = 'OK';
            okButton.style.cssText = `
                background-color: #6a0dad; /* Purple */
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1rem;
                transition: background-color 0.3s ease;
            `;
            okButton.onmouseover = () => okButton.style.backgroundColor = '#5a0a9d';
            okButton.onmouseout = () => okButton.style.backgroundColor = '#6a0dad';
            okButton.onclick = () => document.body.removeChild(modal);

            modalContent.appendChild(messageText);
            modalContent.appendChild(okButton);
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
        }
    </script>
@endsection
