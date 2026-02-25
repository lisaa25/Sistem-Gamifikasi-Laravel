@extends('layout.master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <section id="about">
        <div class="about-section">
            <div class="about-content">
                <div class="description">
                    <p>
                        <span style="font-size: 1.5em; font-weight: bold;">Selamat datang di GamifyIT!</span></br>
                        Perkenalkan aku Koko, robot sahabat kalian yang akan
                        menemani belajar dan berkembang di dunia Algoritma. </br>
                        Yuk mulai petualangan seru belajar Algoritma bersama aku di GamifyIT!
                    </p>
                    @guest
                        <a href="{{ route('login') }}" class="btn-trailer">Mulai belajar</a>
                    @else
                        <a href="{{ route('pembelajaran.index') }}" class="btn-trailer">Mulai belajar</a>
                    @endguest
                </div>
                <div class="image">
                    <!--<img src="{{ asset('img/game/koko_bg.png') }}" alt="Karakter Ara">-->
                    <img src="{{ asset('img/game/video-koko-unscreen.gif') }}" alt="Karakter Koko">
                    <!--<img src="{{ asset('img/game/video_koko_lambai.gif') }}" alt="Karakter Koko">-->
                </div>
            </div>
        </div>
        <!-- filepath: d:\xampp\htdocs\gamifyIT\resources\views\kerangka\about.blade.php -->
        <!--<div class="wave-divider">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="#241478" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,160C384,139,480,117,576,122.7C672,128,768,160,864,181.3C960,203,1056,213,1152,213.3C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                    </svg>
                </div>

                    <div id="kelebihan">
                        <h1>Mengapa Memainkan Game Ini?</h1>
                        <p>Gabungkan belajar dan bermain dengan cara paling menyenangkan!</p>
                        <div class="satu">
                            <h3>Petualangan Interaktif</h3>
                            <p>Misi edukatif penuh aksi seru dan menarik</p>
                        </div>
                        <div class="satu">
                            <h3>Karakter Lucu</h3>
                            <p>Visual kartun yang ramah anak dan menggemaskan</p>
                        </div>
                        <div class="satu">
                            <h3>Warna Cerah</h3>
                            <p>Desain penuh warna untuk pengalaman menyenangkan</p>
                        </div>
                        <div class="satu">
                            <h3>Responsif</h3>
                            <p>Optimal di semua perangkat: desktop, tablet, dan mobile</p>
                        </div>
                    </div>
                </section>-->
    @endsection
