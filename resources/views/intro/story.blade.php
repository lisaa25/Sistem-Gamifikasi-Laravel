@extends('kerangka.layout.clean')

@section('content')
    <div class="story-wrapper">
        {{-- Panel 1 --}}
        <div class="story-panel active" id="panel-1" style="background-image: url('{{ asset('img/panel1.png') }}');">
            <div class="panel-content">
                <div class="bubble left">
                    <h2>Hai, aku Koko!</h2>
                    <p>Salam kenal! Aku adalah robot penjelajah luar angkasa ✨. Tugasku adalah membantu teman-teman belajar
                        teknologi dan algoritma dengan cara seru!</p>
                    <button class="btn-next" onclick="showPanel(2)">Lanjut</button>
                </div>
                <img src="{{ asset('img/game/video-koko-unscreen.gif') }}" class="koko-img right" alt="Koko">
            </div>
        </div>

        {{-- Panel 2 --}}
        <div class="story-panel" id="panel-2" style="background-image: url('{{ asset('img/panel1.png') }}');">
            <div class="panel-content">
                <img src="{{ asset('img/game/video-koko-unscreen.gif') }}" class="koko-img left flip-horizontal"
                    alt="Koko">
                <div class="bubble right">
                    <h2>Dunia sedang berubah!</h2>
                    <p>Di masa depan, semua hal dikuasai oleh teknologi: robot, AI, dan komputer. Tapi, nggak semua orang
                        siap menghadapi itu 😱</p>
                    <button class="btn-next" onclick="showPanel(3)">Lanjut</button>
                </div>
            </div>
        </div>

        {{-- Panel 3 --}}
        <div class="story-panel" id="panel-3" style="background-image: url('{{ asset('img/panel1.png') }}');">
            <div class="panel-content">
                <img src="{{ asset('img/game/koko_baca.png') }}" class="koko-img right" alt="Koko">
                <div class="bubble">
                    <h2>Aku di sini bantu kamu!</h2>
                    <p>Tenang aja, kamu gak sendirian. Kita akan belajar logika, algoritma, dan cara berpikir seperti
                        programmer — sambil bermain! 🎮🧠</p>
                    <button class="btn-next" onclick="showPanel(4)">Lanjut</button>
                </div>
            </div>
        </div>

        {{-- Panel 4 --}}
        <div class="story-panel" id="panel-4" style="background-image: url('{{ asset('img/panel1.png') }}');">
            <div class="panel-content">
                <img src="{{ asset('img/game/kokopen.png') }}" class="koko-img" alt="Koko">
                <div class="bubble">
                    <h2>Ayo berpetualang!</h2>
                    <p>Siap jadi pahlawan teknologi masa depan? Yuk mulai petualangan seru ini bersama Koko! 🚀</p>
                    <a href="{{ route('login') }}" class="btn-start">Aku siap, Koko!</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/story.css') }}">
@endpush

@push('scripts')
    <script>
        function showPanel(number) {
            document.querySelectorAll('.story-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            const nextPanel = document.getElementById(`panel-${number}`);
            nextPanel.classList.add('active');
        }
    </script>
@endpush
