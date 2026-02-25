{{-- resources/views/kerangka/lencanadua.blade.php --}}

@extends('layout.master')

@section('title', 'Koleksi Lencana Anda')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lencanadua.css') }}">
@endpush

@section('content')
    <div class="container-dua">
        <h2>Koleksi lencanamu!</h2>
        <div class="content">
            <div class="karakter-info">
                <div class="karakter-container">
                    <div class="glow"></div>
                    <img src="{{ asset('img/game/koko_lencana.png') }}" alt="Karakter Koko"
                        class="karakter unlocked koko-glow robot-right">
                </div>
                <div class="info-text">
                    <p>Ada banyak lencana keren yang bisa <br>kamu dapatkan loh!
                        <a href="#">Cari tahu
                            caranya</a><br>
                        dan mulai kumpulkan ✨
                    </p>
                </div>
            </div>

            <div class="grid-lencana">
                @foreach ($badgesDisplayData as $badge)
                    <div class="lencana {{ $badge['is_unlocked'] ? 'unlocked' : 'locked' }}">
                        <img src="{{ $badge['is_unlocked'] ? $badge['gambar'] : $badge['locked_image'] }}"
                            alt="{{ $badge['is_unlocked'] ? $badge['nama_lencana'] : 'Lencana Terkunci' }}">

                        {{-- Nama Lencana --}}
                        <p class="badge-name">{{ $badge['is_unlocked'] ? $badge['nama_lencana'] : '???' }}</p>

                        {{-- Deskripsi Lencana --}}
                        <p class="badge-description">
                            {{ $badge['is_unlocked'] ? $badge['deskripsi'] : 'Kumpulkan koin atau selesaikan misi untuk membuka lencana ini!' }}
                        </p>

                        {{-- Tanggal Dicapai (hanya jika unlocked) --}}
                        @if ($badge['is_unlocked'])
                            <small class="badge-date">Dicapai: {{ $badge['tanggal_dicapai'] }}</small>
                        @endif

                        {{-- Menghapus bagian badge-tooltip karena akan ditampilkan langsung --}}
                        {{-- <div class="badge-tooltip">
                            <h3>{{ $badge['is_unlocked'] ? $badge['nama_lencana'] : 'Lencana Terkunci' }}</h3>
                            <p>{{ $badge['is_unlocked'] ? $badge['deskripsi'] : 'Kumpulkan koin atau selesaikan misi untuk membuka lencana ini!' }}</p>
                        </div> --}}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
