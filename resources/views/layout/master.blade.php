<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamifyIT | Belajar Algoritma</title>

    {{-- CSS GLOBAL --}}
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- CSS Eksternal --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #182952;
            /* warna dasar dashboard */
        }
    </style>
    @include('kerangka.navbar')
    @include('partials._badge_modal')
    <div id="main-content-wrapper">
        @yield('content')
        @yield('register')
        @yield('login')
        @yield('materi')
        @yield('kuis')
        @yield('cubacuba')
    </div>
    {{-- Footer --}}
    @include('kerangka.footer')

    {{-- JAVASCRIPT GLOBAL --}}
    {{-- Feather Icons Script (hanya panggil sekali dan aktifkan sekali) --}}
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace(); // Aktifkan feather icons
    </script>

    @stack('scripts')
</body>

</html>
