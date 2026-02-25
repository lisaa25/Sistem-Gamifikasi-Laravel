{{-- resources/views/admin/kuis/index.blade.php --}}

@extends('admin.dashboard') {{-- Sesuaikan dengan layout admin Anda --}}

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <div class="header-flex flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-600">Soal Kuis untuk Materi: "{{ $materi->judul_materi }}"</h1>
            <div class="flex space-x-3">
                <a href="{{ route('kuis.create', $materi->id) }}"
                    class="bg-purple-200 hover:bg-purple-500 text-purple-800 hover:text-white px-6 py-3 rounded-full text-lg font-semibold transition-colors duration-200 flex items-center space-x-2 shadow">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Soal Kuis</span>
                </a>
                <a href="{{ route('admin.materi.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Materi</span>
                </a>
            </div>
        </div>

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

        @if ($kuis->isEmpty())
            <p class="text-gray-600 text-lg text-center py-8">Belum ada soal kuis untuk materi ini.</p>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-purple-700 text-white text-left">
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tl-lg">No.</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Pertanyaan</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Opsi A</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Opsi B</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Opsi C</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Opsi D</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Jawaban Benar</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($kuis as $key => $soal)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $key + 1 }}</td>
                                <td class="py-3 px-4">{{ $soal->pertanyaan }}</td>
                                <td class="py-3 px-4">{{ $soal->opsi_a }}</td>
                                <td class="py-3 px-4">{{ $soal->opsi_b }}</td>
                                <td class="py-3 px-4">{{ $soal->opsi_c }}</td>
                                <td class="py-3 px-4">{{ $soal->opsi_d }}</td>
                                <td class="py-3 px-4 font-semibold text-purple-700">{{ $soal->jawaban }}</td>
                                <td class="py-3 px-4 flex items-center space-x-2">
                                    <a href="{{ route('kuis.edit', ['materi' => $materi->id, 'kuis' => $soal->id]) }}"
                                        class="btn-action bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                        title="Edit Soal Kuis">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route('kuis.destroy', ['materi' => $materi->id, 'kuis' => $soal->id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn-action bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus soal kuis ini?')"
                                            title="Hapus Soal Kuis">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
