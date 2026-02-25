@extends('layout.master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/detail_riwayat_kuis.css') }}"> {{-- Link ke CSS baru --}}

    <div class="detail-riwayat-container">
        <div class="header-section">
            <h2 class="title">Detail Hasil Kuis</h2>
            <h3 class="subtitle">Materi: {{ $materi->judul_materi }}</h3>
        </div>

        <div class="summary-card">
            <div class="summary-item">
                <span class="icon">💯</span>
                <p>Nilai Kuis: <strong>{{ $hasilKuis->nilai_kuis }}</strong></p>
            </div>
            <div class="summary-item">
                <span class="icon">✅</span>
                <p>Benar: <strong>{{ $hasilKuis->skor_benar }}</strong></p>
            </div>
            <div class="summary-item">
                <span class="icon">❌</span>
                <p>Salah: <strong>{{ $hasilKuis->skor_salah }}</strong></p>
            </div>
            <div class="summary-item">
                <span class="icon">💰</span>
                <p>Koin Didapatkan: <strong>{{ $hasilKuis->koin_didapatkan }}</strong></p>
            </div>
            <div class="summary-item">
                <span class="icon">⏰</span>
                <p>Waktu Pengerjaan: <strong>{{ $hasilKuis->waktu_mengerjakan->format('d M Y H:i') }}</strong></p>
            </div>
        </div>

        <h3 class="section-title">Review Jawaban Anda</h3>

        <div class="jawaban-list">
            {{-- Loop melalui setiap detail jawaban siswa yang disimpan --}}
            @forelse ($hasilKuis->jawabanSiswa as $index => $jawabanSiswa)
                <div class="jawaban-card {{ $jawabanSiswa->is_correct ? 'correct-answer' : 'incorrect-answer' }}">
                    <div class="question-header">
                        <span class="question-number">Soal #{{ $index + 1 }}</span>
                        <span class="status-icon">
                            @if ($jawabanSiswa->is_correct)
                                ✅ Benar
                            @else
                                ❌ Salah
                            @endif
                        </span>
                    </div>
                    <p class="question-text">{{ $jawabanSiswa->soal->pertanyaan }}</p>

                    <div class="options-container">
                        @php
                            // Ambil semua opsi dari model soal (Kuis)
                            $options = [
                                'A' => $jawabanSiswa->soal->opsi_a,
                                'B' => $jawabanSiswa->soal->opsi_b,
                                'C' => $jawabanSiswa->soal->opsi_c,
                                'D' => $jawabanSiswa->soal->opsi_d,
                            ];
                            // Kunci jawaban yang benar dari soal
                            $jawabanBenarKey = strtoupper($jawabanSiswa->soal->jawaban);
                            // Pilihan yang dipilih siswa untuk soal ini
                            $pilihanSiswaKey = strtoupper($jawabanSiswa->pilihan_terpilih);
                        @endphp

                        @foreach ($options as $key => $value)
                            <div
                                class="option-item
                                {{ $key === $jawabanBenarKey ? 'correct-option' : '' }} {{-- Tandai jawaban yang benar --}}
                                {{ $key === $pilihanSiswaKey ? 'selected-option' : '' }} {{-- Tandai pilihan siswa --}}
                                {{ $key === $pilihanSiswaKey && $key !== $jawabanBenarKey ? 'wrong-selected-option' : '' }}">
                                {{-- Tandai pilihan siswa yang salah --}}
                                <strong>{{ $key }}.</strong> {{ $value }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="no-answers">Tidak ada detail jawaban ditemukan untuk kuis ini.</p>
            @endforelse
        </div>

        <a href="{{ route('dashboard.siswa') }}" class="back-to-dashboard-button">← Kembali ke Dashboard</a>
    </div>
@endsection
