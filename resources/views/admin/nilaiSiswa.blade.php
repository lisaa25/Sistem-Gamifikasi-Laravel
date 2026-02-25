{{-- resources/views/admin/student_scores/index.blade.php --}}

@extends('admin.dashboard')
@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Nilai Siswa</h1>

        {{-- Bagian Filter Kelas --}}
        <div class="filter-kelas flex gap-3 mb-6 flex-wrap">
            <a href="{{ route('admin.student_scores.index', ['kelas' => 'all']) }}"
                class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                {{ !isset($selectedKelas) || $selectedKelas == 'all' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua Kelas
            </a>
            @foreach ($kelasList as $kelas)
                <a href="{{ route('admin.student_scores.index', ['kelas' => $kelas]) }}"
                    class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                    {{ isset($selectedKelas) && $selectedKelas == $kelas ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Kelas {{ $kelas }}
                </a>
            @endforeach
        </div>

        @if (empty($studentScores))
            <p class="text-gray-600 text-lg text-center py-8">Tidak ada data nilai siswa yang tersedia.</p>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-purple-700 text-white text-left">
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tl-lg">ID</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Nama Siswa</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Kelas</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Jumlah Koin</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Rata-rata Nilai Kuis</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tr-lg">Kategori Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($studentScores as $score)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $score['id'] }}</td>
                                <td class="py-3 px-4">
                                    {{-- Nama Siswa sekarang menjadi link ke halaman detail nilai --}}
                                    <a href="{{ route('admin.student_scores.details', $score['id']) }}"
                                        class="text-purple-600 hover:underline font-medium">
                                        {{ $score['nama'] }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">{{ $score['kelas'] }}</td>
                                <td class="py-3 px-4">{{ $score['total_koin'] }}</td>
                                <td class="py-3 px-4">{{ $score['rata_rata_nilai_kuis'] }}</td>
                                <td
                                    class="py-3 px-4 font-semibold
                                    @if ($score['kategori_nilai'] == 'A') text-green-600
                                    @elseif ($score['kategori_nilai'] == 'B') text-blue-600
                                    @elseif ($score['kategori_nilai'] == 'C') text-yellow-600
                                    @else text-gray-500 @endif">
                                    {{ $score['kategori_nilai'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
