<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - GamifyIT</title>
    {{-- Tambahkan baris ini untuk CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts: Inter (untuk font yang rapi) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS Anda -->
    <link rel="stylesheet" href="{{ asset('css/admin/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/navbar.css') }}">
    <!-- CSS khusus halaman yang di-yield -->
    @stack('styles')
</head>

<body class="font-inter bg-gray-100">
    <div class="admin-dashboard flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar-wrapper">
            @include('admin.sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="main-content flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <div class="navbar-wrapper">
                @include('admin.navbar')
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
