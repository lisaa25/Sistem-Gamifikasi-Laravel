@extends('admin.dashboard')

@section('content')
    <div class="dashboard-guru p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Statistik Dashboard</h1>

        <section class="statistik grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Statistik Card: Jumlah Siswa Per Kelas --}}
            <div class="statistik-card bg-white p-6 rounded-lg shadow-md flex flex-col justify-between">
                <div class="card-header text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-users text-purple-600 mr-3"></i>
                    <span>Jumlah Siswa Per Kelas</span>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @foreach ($jumlahSiswaPerKelas as $kelas => $jumlah)
                            <div
                                class="flex justify-between items-center text-lg text-gray-700 border-b border-gray-200 pb-2">
                                <span class="font-medium">Kelas {{ $kelas }}:</span>
                                <div>
                                    <span class="font-bold text-purple-700">{{ $jumlah }}</span>
                                    <span class="text-sm text-gray-500">Siswa</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="flex justify-between items-center text-xl font-bold text-gray-800 pt-3">
                            <span>Total:</span>
                            <div>
                                <span class="text-purple-800">{{ $totalSiswa }}</span>
                                <span class="text-base text-gray-600">Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Card: Rata-rata Nilai Kelas --}}
            <div class="statistik-card bg-white p-6 rounded-lg shadow-md flex flex-col justify-between">
                <div class="card-header text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-purple-600 mr-3"></i>
                    <span>Rata-rata Nilai Kelas</span>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @foreach ($rataRataNilaiPerKelas as $kelas => $rataRata)
                            <div
                                class="flex justify-between items-center text-lg text-gray-700 border-b border-gray-200 pb-2">
                                <span class="font-medium">Kelas {{ $kelas }}:</span>
                                <span class="font-bold text-blue-600">{{ $rataRata }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Statistik Card: Top 5 Siswa Berdasarkan Poin (Leaderboard) --}}
            <div class="statistik-card bg-white p-6 rounded-lg shadow-md flex flex-col justify-between lg:col-span-1">
                <div class="card-header text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-trophy text-purple-600 mr-3"></i>
                    <span>Top 5 Siswa (Poin & Nilai)</span>
                </div>
                <div class="card-body">
                    @if ($leaderboard->isEmpty())
                        <p class="text-gray-600 text-center">Belum ada data leaderboard.</p>
                    @else
                        <ol class="space-y-3">
                            @foreach ($leaderboard as $index => $student)
                                <li
                                    class="flex justify-between items-center text-lg text-gray-700 border-b border-gray-200 pb-2">
                                    <div class="flex items-center">
                                        <span class="font-bold text-purple-700 mr-2">{{ $index + 1 }}.</span>
                                        <span class="font-medium">{{ $student['nama'] }} ({{ $student['kelas'] }})</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-green-600">{{ $student['total_koin'] }} Koin</span>
                                        <br>
                                        <span class="text-sm text-gray-500">Nilai:
                                            {{ $student['rata_rata_nilai_kuis'] }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection
