{{-- resources/views/admin/kuis/edit.blade.php --}}

@extends('admin.dashboard') {{-- Sesuaikan dengan layout admin Anda --}}
@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">Edit Soal Kuis</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-6 text-center">Materi: "{{ $materi->judul_materi }}"</h2>

        @if ($errors->any())
            <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kuis.update', ['materi' => $materi->id, 'kuis' => $kuis->id]) }}" method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="pertanyaan" class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan Kuis:</label>
                <textarea name="pertanyaan" id="pertanyaan"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    rows="4" required>{{ old('pertanyaan', $kuis->pertanyaan) }}</textarea>
            </div>

            <div class="form-group">
                <label for="opsi_a" class="block text-gray-700 text-sm font-bold mb-2">Opsi A:</label>
                <input type="text" name="opsi_a" id="opsi_a"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('opsi_a', $kuis->opsi_a) }}" required>
            </div>

            <div class="form-group">
                <label for="opsi_b" class="block text-gray-700 text-sm font-bold mb-2">Opsi B:</label>
                <input type="text" name="opsi_b" id="opsi_b"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('opsi_b', $kuis->opsi_b) }}" required>
            </div>

            <div class="form-group">
                <label for="opsi_c" class="block text-gray-700 text-sm font-bold mb-2">Opsi C:</label>
                <input type="text" name="opsi_c" id="opsi_c"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('opsi_c', $kuis->opsi_c) }}" required>
            </div>

            <div class="form-group">
                <label for="opsi_d" class="block text-gray-700 text-sm font-bold mb-2">Opsi D:</label>
                <input type="text" name="opsi_d" id="opsi_d"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('opsi_d', $kuis->opsi_d) }}" required>
            </div>

            <div class="form-group">
                <label for="jawaban" class="block text-gray-700 text-sm font-bold mb-2">Jawaban Benar:</label>
                <select name="jawaban" id="jawaban"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    required>
                    <option value="">Pilih Jawaban Benar</option>
                    <option value="A" {{ old('jawaban', $kuis->jawaban) == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('jawaban', $kuis->jawaban) == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('jawaban', $kuis->jawaban) == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ old('jawaban', $kuis->jawaban) == 'D' ? 'selected' : '' }}>D</option>
                </select>
            </div>

            <div class="flex items-center justify-start space-x-4 mt-6">
                <button type="submit"
                    class="btn btn-primary bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Update Soal Kuis
                </button>
                <a href="{{ route('kuis.index', $materi->id) }}"
                    class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
