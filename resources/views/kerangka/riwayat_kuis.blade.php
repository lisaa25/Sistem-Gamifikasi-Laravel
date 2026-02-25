@extends('layout.master')

@section('content')
    {{-- <link rel="stylesheet" href="{{ asset('css/riwayat_kuis.css') }}"> {{-- Buat file CSS baru jika diperlukan --}}

    <div class="riwayat-kuis-container">
        <h2>Riwayat Kuis untuk Materi: {{ $materi->judul_materi }}</h2>

        @if ($highestScore !== null)
            <p class="highest-score">Nilai Tertinggi Anda: <strong>{{ $highestScore }}</strong></p>
        @else
            <p>Anda belum pernah mengerjakan kuis ini.</p>
        @endif

        @if ($userHasilKuis->isNotEmpty())
            <div class="hasil-kuis-list">
                @foreach ($userHasilKuis as $hasil)
                    <div class="hasil-kuis-card">
                        <p class="nilai-info">Nilai Kuis: <strong>{{ $hasil->nilai_kuis }}</strong></p>
                        <p>Benar: {{ $hasil->skor_benar }} | Salah: {{ $hasil->skor_salah }}</p>
                        <p>Koin Didapatkan: {{ $hasil->koin_didapatkan }}</p>
                        <p class="waktu-info">Waktu: {{ $hasil->created_at->format('d M Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>Belum ada riwayat hasil kuis yang ditemukan untuk materi ini.</p>
        @endif

        <a href="{{ route('materi.show', $materi->id) }}" class="back-button">← Kembali ke Materi</a>
    </div>

    <style>
        /* Anda bisa memindahkan ini ke public/css/riwayat_kuis.css */
        .riwayat-kuis-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .riwayat-kuis-container h2 {
            color: #182952;
            margin-bottom: 20px;
        }

        .highest-score {
            font-size: 1.2em;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .hasil-kuis-list {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            margin-top: 20px;
        }

        .hasil-kuis-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            text-align: left;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .hasil-kuis-card p {
            margin-bottom: 5px;
            color: #333;
        }

        .hasil-kuis-card .nilai-info {
            font-size: 1.1em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .hasil-kuis-card .waktu-info {
            font-size: 0.9em;
            color: #6c757d;
            text-align: right;
            margin-top: 10px;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
@endsection
