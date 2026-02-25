@extends('kerangka.layout.clean')

@section('content')
    <div class="video-background">
        <video autoplay muted loop id="bg-video">
            <source src="{{ asset('img/intro.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <!-- Overlay Gelap -->
        <div class="video-overlay"></div>

        <div class="intro-container">
            <h1 class="title">Selamat Datang di <span class="highlight">GamifyIT</span></h1>
            <p class="desc">Sebuah petualangan luar angkasa seru untuk menjelajahi dunia algoritma dan teknologi</p>
            <a href="{{ route('story') }}" class="btn-start">Mulai Petualangan</a>
            <a href="{{ route('loginAdmin') }}" class="btn-guru">Login Guru</a>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/intro.css') }}">
@endpush
