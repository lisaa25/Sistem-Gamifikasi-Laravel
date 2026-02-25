@extends('layout.master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">

    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <!-- <img src="{{ asset('img/game/kokopiala.png') }}" alt="Trophy Icon" class="trophy-icon">-->
            <h1>Leaderboard Kelas {{ $kelasSiswa }}</h1>
            <p class="periode-info">Periode: {{ now()->format('d M Y') }}</p>
        </div>

        <div class="top-summary">
            @foreach ($topThree as $rank => $user)
                <div class="top-card">
                    <p class="rank-icon">#{{ $rank + 1 }}</p>
                    <img src="{{ $user->profile_picture_url }}" alt="Avatar">
                    <p class="name">{{ $user->nama }}</p>
                    <p class="score">{{ $user->total_koin }} koin</p>
                </div>
            @endforeach
        </div>

        <div class="rank-list">
            @foreach ($others as $index => $player)
                <div class="rank-item {{ auth()->user()->id == $player->id ? 'current-user' : '' }}">
                    <div class="rank-number">#{{ $index + 1 }}</div>
                    <div class="rank-info">
                        <img src="{{ $player->profile_picture_url }}" alt="Avatar">
                        <span class="rank-name">{{ $player->nama }}
                            {{ auth()->user()->id == $player->id ? '(Anda)' : '' }}</span>
                    </div>
                    <div class="rank-score">{{ $player->total_koin }} koin</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
