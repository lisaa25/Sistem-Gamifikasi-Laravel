@extends('kerangka.layout.clean')

@section('content')
    <section id="login">
        <div class="login-container">
            <form action="{{ route('loginPost') }}" method="POST">
                @csrf
                <h1>Masuk ke Gamify<span class="highlight">IT</span></h1>
                <hr>
                <label for="email"><b>Email:</b></label>
                <input type="email" placeholder="Masukkan Email" name="email" id="email" required>

                <label for="password"><b>Password:</b></label>
                <input type="password" placeholder="Masukkan Password" name="password" id="password" required>

                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush
