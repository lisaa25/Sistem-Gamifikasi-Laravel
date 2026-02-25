@extends('layout.master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/pembelajaran.css') }}">

    {{-- Pesan error atau sukses dari session (misalnya dari redirect controller) --}}
    @if (session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- Loop untuk setiap Level --}}
    @foreach ($materisByLevel as $levelName => $materis)
        <div class="activity-section-satu">
            {{-- Menampilkan Nama Level dan Deskripsi Level --}}
            @if ($materis->isNotEmpty() && $materis->first()->level)
                {{-- Menggunakan $materis->first()->level->deskripsi yang sudah benar --}}
                <h2>⭐ {{ $levelName }} : <small>{{ $materis->first()->level->deskripsi }}</small></h2>
            @else
                <h2>⭐ {{ $levelName }}</h2> {{-- Fallback jika deskripsi tidak ada --}}
            @endif

            <div class="activity-cards">
                {{-- Loop untuk setiap materi di dalam level ini --}}
                @foreach ($materis as $materi)
                    {{-- Kelas 'locked' ditambahkan berdasarkan properti 'is_locked' yang akan dikirim dari controller --}}
                    <a href="{{ $materi->is_locked ? '#' : route('materi.show', $materi->id) }}"
                        class="activity-card-link {{ $materi->is_locked ? 'locked' : '' }}">
                        <div class="activity-card">
                            @if ($materi->is_locked)
                                <div class="lock-icon">🔒</div> {{-- Ikon gembok untuk materi terkunci --}}
                            @endif

                            {{-- LOGIKA GAMBAR MATERI BERDASARKAN URUTAN --}}
                            @php
                                $baseImagePath = 'img/materi/';
                                $imageFileName = 'urutan_tambahan.png'; // Default/fallback image

                                // Cek urutan materi dan sesuaikan nama file gambar
                                if ($materi->urutan >= 1 && $materi->urutan <= 4) {
                                    $imageFileName = 'urutan' . $materi->urutan . '.png'; // Contoh: urutan1.png, urutan2.png
                                }
                                // Anda bisa menambahkan kondisi 'else if' di sini untuk urutan > 4 jika ada gambar spesifik lainnya

                                $fullImagePath = $baseImagePath . $imageFileName;
                            @endphp
                            <img src="{{ asset($fullImagePath) }}" alt="Materi {{ $materi->judul_materi }}">
                            {{-- AKHIR LOGIKA GAMBAR MATERI --}}

                            <div class="card-content">
                                <h4>{{ $materi->judul_materi }}</h4>
                                <p>💡 {{ $materi->deskripsi_materi ?? 'Deskripsi materi belum tersedia.' }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
