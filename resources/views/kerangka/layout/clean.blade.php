<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamifyIT</title>

    <link rel="stylesheet" href="{{ asset('css/cubacuba.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intro.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body style="margin:0; padding:0; background-color:#0f2027;">
    @yield('content')

    @stack('scripts')
</body>

</html>
