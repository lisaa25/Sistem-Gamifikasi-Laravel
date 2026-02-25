{{-- resources/views/admin/materi/index.blade.php --}}
@extends('admin.dashboard')

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <div class="header-flex flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Materi</h1>
            <a href="{{ route('admin.materi.create') }}"
                class="btn-tambah bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-full text-lg font-semibold transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Materi Baru</span>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($materis->isEmpty())
            <p class="text-gray-600 text-lg text-center py-8">Belum ada materi yang diunggah.</p>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-purple-700 text-white text-left">
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tl-lg">ID</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Level</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Judul Materi</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Urutan</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">File PDF</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($materis as $materi)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $materi->id }}</td>
                                <td class="py-3 px-4">{{ $materi->level->nama_level ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $materi->judul_materi }}</td>
                                <td class="py-3 px-4">{{ $materi->urutan }}</td>
                                <td class="py-3 px-4">
                                    {{-- Pastikan URL PDF benar, gunakan asset('storage/' . $materi->file_pdf) jika disimpan di public/storage --}}
                                    <a href="{{ asset('storage/' . $materi->file_pdf) }}" target="_blank"
                                        class="text-purple-600 hover:text-purple-800 font-medium flex items-center space-x-1">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>Lihat PDF</span>
                                    </a>
                                </td>
                                <td class="py-3 px-4 flex items-center space-x-2">
                                    {{-- Tombol Edit Materi (dengan ikon) --}}
                                    <a href="{{ route('admin.materi.edit', $materi->id) }}"
                                        class="btn-action bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                        title="Edit Materi">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- TOMBOL KELOLA KUIS BARU (dengan ikon) --}}
                                    <a href="{{ route('kuis.index', $materi->id) }}"
                                        class="btn-action bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                        title="Kelola Kuis">
                                        <i class="fas fa-question-circle"></i>
                                    </a>

                                    {{-- Formulir Hapus Materi (dengan ikon) --}}
                                    <form action="{{ route('admin.materi.destroy', $materi->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn-action bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini dan file PDF-nya?')"
                                            title="Hapus Materi">
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
