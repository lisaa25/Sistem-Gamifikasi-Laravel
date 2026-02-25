{{-- resources/views/admin/profile.blade.php --}}

@extends('admin.dashboard')

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Profil Admin</h1>

        @if (session('success'))
            <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col items-center space-y-6">
            <div class="relative">
                {{-- Tampilkan foto admin jika ada, jika tidak gunakan placeholder --}}
                <img src="{{ Auth::guard('admin')->user()->foto ? asset('storage/img/profil/' . Auth::guard('admin')->user()->foto) : 'https://placehold.co/120x120/E0BBE4/FFFFFF?text=AD' }}"
                    alt="Admin Avatar" class="w-32 h-32 rounded-full border-4 border-purple-500 shadow-lg object-cover">
            </div>

            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900">{{ Auth::guard('admin')->user()->nama ?? 'Nama Admin' }}
                    {{-- PERBAIKAN DI SINI --}}
                </h2>
                <p class="text-gray-600">{{ Auth::guard('admin')->user()->email ?? 'email@admin.com' }}</p>
            </div>

            <div class="w-full max-w-md space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 font-medium">Nama Lengkap:</p>
                    <p class="text-gray-900 text-lg">{{ Auth::guard('admin')->user()->nama ?? '-' }}</p>
                    {{-- PERBAIKAN DI SINI --}}
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 font-medium">Email:</p>
                    <p class="text-gray-900 text-lg">{{ Auth::guard('admin')->user()->email ?? '-' }}</p>
                </div>
                {{-- Tambahkan detail lain jika ada di model admin Anda, contoh: --}}
                {{-- <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 font-medium">Role:</p>
                    <p class="text-gray-900 text-lg">{{ Auth::guard('admin')->user()->role ?? 'Admin' }}</p>
                </div> --}}
            </div>

            {{-- Tombol untuk kembali atau edit --}}
            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('statistik') }}"
                    class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
                {{-- Tombol Edit Profil Admin --}}
                <a href="{{ route('admin.profile.edit') }}"
                    class="btn btn-primary bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    <i class="fas fa-edit mr-2"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
@endsection
