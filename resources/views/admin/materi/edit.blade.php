{{-- resources/views/admin/materi/edit.blade.php --}}
@extends('admin.dashboard')

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Materi: {{ $materi->judul_materi }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="level_id" class="block text-gray-700 text-sm font-bold mb-2">Level:</label>
                <select name="level_id" id="level_id" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                    <option value="">Pilih Level</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}"
                            {{ old('level_id', $materi->level_id) == $level->id ? 'selected' : '' }}>
                            {{ $level->nama_level }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="judul_materi" class="block text-gray-700 text-sm font-bold mb-2">Judul Materi:</label>
                <input type="text" name="judul_materi" id="judul_materi" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('judul_materi', $materi->judul_materi) }}" required>
            </div>

            <div class="form-group">
                <label for="deskripsi_materi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Materi:</label>
                <textarea name="deskripsi_materi" id="deskripsi_materi" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent" rows="5">{{ old('deskripsi_materi', $materi->deskripsi_materi) }}</textarea>
            </div>

            <div class="form-group">
                <label for="urutan" class="block text-gray-700 text-sm font-bold mb-2">Urutan Sub-Materi:</label>
                <input type="number" name="urutan" id="urutan" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('urutan', $materi->urutan) }}" required min="1">
            </div>

            <div class="form-group">
                <label for="file_pdf" class="block text-gray-700 text-sm font-bold mb-2">File PDF (Biarkan kosong jika tidak ingin mengubah):</label>
                <input type="file" name="file_pdf" id="file_pdf" class="form-control-file block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-purple-50 file:text-purple-700
                    hover:file:bg-purple-100">
                @if ($materi->file_pdf)
                    <small class="form-text text-gray-500 text-sm mt-2 block">File saat ini: <a href="{{ asset('storage/' . $materi->file_pdf) }}" target="_blank" class="text-purple-600 hover:underline">{{ basename($materi->file_pdf) }}</a></small>
                @endif
            </div>

            <div class="flex items-center justify-start space-x-4 mt-6">
                <button type="submit" class="btn btn-primary bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Update Materi
                </button>
                <a href="{{ route('admin.materi.index') }}" class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
