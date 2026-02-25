{{-- resources/views/admin/student_scores/detail.blade.php --}}

@extends('admin.dashboard')

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rincian Nilai Siswa</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-6">Nama: {{ $user->nama }} (Kelas: {{ $user->kelas }})</h2>

        <div class="mb-6">
            <p class="text-lg text-gray-700">Total Koin: <span
                    class="font-bold text-purple-700">{{ $user->total_koin ?? 0 }}</span></p>
            {{-- Anda bisa menambahkan informasi ringkasan lain di sini jika diinginkan --}}
        </div>

        @if ($quizResults->isEmpty())
            <p class="text-gray-600 text-lg text-center py-8">Siswa ini belum mengerjakan kuis apapun.</p>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-purple-700 text-white text-left">
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tl-lg">Materi (Level)</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Nilai Didapatkan</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Waktu Mengerjakan</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tr-lg">Koin Didapatkan</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($quizResults as $result)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ $result->materi->judul_materi ?? 'N/A' }}
                                    @if ($result->materi && $result->materi->level)
                                        <br><span class="text-sm text-gray-500">(
                                            {{ $result->materi->level->nama_level }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-semibold text-blue-600">{{ $result->nilai_kuis }}</td>
                                <td class="py-3 px-4">
                                    {{ $result->waktu_mengerjakan ? \Carbon\Carbon::parse($result->waktu_mengerjakan)->format('d M Y H:i') : 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-green-600 font-semibold">{{ $result->koin_didapatkan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.student_scores.index') }}"
                class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out flex items-center space-x-2 w-fit">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Nilai</span>
            </a>
        </div>
    </div>
@endsection
