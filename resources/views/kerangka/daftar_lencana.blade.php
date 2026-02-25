{{-- resources/views/user/badge_catalog.blade.php --}}

@extends('layout.master') {{-- Pastikan ini sesuai dengan layout utama Anda --}}

@push('styles')
    <!-- Tailwind CSS CDN (Pastikan ini sudah ada di layout.master Anda atau tambahkan di sini jika layout.master belum pakai Tailwind) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/user/badgeCatalog.css') }}">
@endpush

@section('content')
    <div class="min-h-screen"
        style="background: url('{{ asset('img/panel1.png') }}') no-repeat center center fixed; background-size: cover;">
        <div class="container mx-auto px-4 py-8 mt-20"> {{-- mt-20 untuk margin atas agar tidak tertutup navbar --}}
            <div class="flex items-center mb-6">
                <a href="{{ route('user.show') }}"
                    class="btn-back flex items-center text-white hover:text-orange-300 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2 text-lg"></i>
                    <span class="font-medium text-lg">Kembali ke Profil</span>
                </a>
            </div>

            <h2 class="text-4xl font-extrabold text-center text-white mb-8 drop-shadow-sm">
                <i class="fas fa-medal text-orange-400 mr-3"></i> Katalog Lencana GamifyIT
            </h2>

            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                <div
                    class="alert bg-blue-50 text-blue-800 px-6 py-4 rounded-lg mb-6 text-center text-lg font-medium border border-blue-200 flex items-center justify-center space-x-3">
                    <i class="fas fa-star text-orange-400 text-2xl"></i>
                    <span>Kumpulkan semua lencana untuk menunjukkan keahlian dan dedikasimu! Setiap lencana punya kisahnya
                        sendiri.</span>
                    <i class="fas fa-star text-orange-400 text-2xl"></i>
                </div>

                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-100">
                    <table class="min-w-full bg-white">
                        <thead class="bg-blue-700 text-white"> {{-- Warna header tabel diganti biru tua --}}
                            <tr>
                                <th scope="col"
                                    class="py-3 px-4 uppercase font-semibold text-sm text-center rounded-tl-lg">#
                                </th>
                                <th scope="col" class="py-3 px-4 uppercase font-semibold text-sm text-center">Gambar
                                    Lencana
                                </th>
                                <th scope="col" class="py-3 px-4 uppercase font-semibold text-sm">Nama Lencana</th>
                                <th scope="col" class="py-3 px-4 uppercase font-semibold text-sm rounded-tr-lg">Cara
                                    Mendapatkan (Deskripsi)</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($badgesDisplayData as $index => $badge)
                                <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors duration-150">
                                    {{-- Warna hover diganti biru muda --}}
                                    <th scope="row"
                                        class="py-4 px-4 text-center align-middle text-lg font-medium text-blue-700">
                                        {{ $index + 1 }}</th> {{-- Warna teks nomor diganti biru tua --}}
                                    <td class="py-4 px-4 text-center align-middle">
                                        <img src="{{ $badge['gambar'] }}" alt="{{ $badge['nama_lencana'] }}"
                                            class="w-20 h-20 object-contain mx-auto border-2 border-blue-300 rounded-lg shadow-sm p-1 bg-white transform hover:scale-105 transition-transform duration-200">
                                        {{-- Warna border gambar diganti biru muda --}}
                                    </td>
                                    <td class="py-4 px-4 align-middle font-bold text-orange-500 text-lg">
                                        {{ $badge['nama_lencana'] }}</td> {{-- Warna teks nama lencana diganti oranye --}}
                                    <td class="py-4 px-4 align-middle text-gray-600 leading-relaxed">
                                        {{ $badge['deskripsi'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-600 text-lg">Belum ada lencana yang
                                        tersedia dalam katalog.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
