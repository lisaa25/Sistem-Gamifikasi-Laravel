{{-- resources/views/admin/kuis/create.blade.php --}}
@extends('admin.dashboard') {{-- Sesuaikan dengan layout admin Anda --}}
@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">Tambah Soal Kuis</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-6 text-center">Untuk Materi: "{{ $materi->judul_materi }}"</h2>

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

        {{-- Form untuk memilih jumlah soal --}}
        <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
            <label for="jumlah_soal" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Soal Kuis yang Ingin
                Ditambahkan:</label>
            <div class="flex items-center space-x-3">
                <input type="number" id="jumlah_soal"
                    class="form-control shadow-sm appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent w-32"
                    value="1" min="1">
                <button type="button" id="generate_forms_btn"
                    class="btn bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Buat Form Soal
                </button>
            </div>
        </div>

        {{-- Form utama untuk menyimpan soal kuis --}}
        <form action="{{ route('kuis.store', $materi->id) }}" method="POST" id="quiz_form">
            @csrf

            {{-- Container untuk form soal kuis yang akan digenerate secara dinamis --}}
            <div id="quiz_forms_container" class="space-y-8">
                {{-- Form soal kuis akan di-inject di sini oleh JavaScript --}}
            </div>

            <div class="flex items-center justify-start space-x-4 mt-8">
                <button type="submit"
                    class="btn btn-primary bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Simpan Soal Kuis
                </button>
                <a href="{{ route('kuis.index', $materi->id) }}"
                    class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jumlahSoalInput = document.getElementById('jumlah_soal');
            const generateFormsBtn = document.getElementById('generate_forms_btn');
            const quizFormsContainer = document.getElementById('quiz_forms_container');

            // Template untuk satu form soal kuis
            function getQuizFormTemplate(index) {
                return `
                    <div class="quiz-form-item p-6 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Soal Kuis #${index + 1}</h3>
                        <div class="form-group mb-4">
                            <label for="pertanyaan_${index}" class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan Kuis:</label>
                            <textarea name="pertanyaan[${index}]" id="pertanyaan_${index}" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent" rows="4" required>${{ old('pertanyaan.${index}') ?? '' }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label for="opsi_a_${index}" class="block text-gray-700 text-sm font-bold mb-2">Opsi A:</label>
                            <input type="text" name="opsi_a[${index}]" id="opsi_a_${index}" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                value="{{ old('opsi_a.${index}') ?? '' }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="opsi_b_${index}" class="block text-gray-700 text-sm font-bold mb-2">Opsi B:</label>
                            <input type="text" name="opsi_b[${index}]" id="opsi_b_${index}" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                value="{{ old('opsi_b.${index}') ?? '' }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="opsi_c_${index}" class="block text-gray-700 text-sm font-bold mb-2">Opsi C:</label>
                            <input type="text" name="opsi_c[${index}]" id="opsi_c_${index}" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                value="{{ old('opsi_c.${index}') ?? '' }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="opsi_d_${index}" class="block text-gray-700 text-sm font-bold mb-2">Opsi D:</label>
                            <input type="text" name="opsi_d[${index}]" id="opsi_d_${index}" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                value="{{ old('opsi_d.${index}') ?? '' }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="jawaban_${index}" class="block text-gray-700 text-sm font-bold mb-2">Jawaban Benar:</label>
                            <select name="jawaban[${index}]" id="jawaban_${index}" class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Jawaban Benar</option>
                                <option value="A" ${{ old('jawaban.${index}') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" ${{ old('jawaban.${index}') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" ${{ old('jawaban.${index}') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" ${{ old('jawaban.${index}') == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                        </div>
                    </div>
                `;
            }

            // Fungsi untuk menghasilkan form
            function generateQuizForms() {
                const jumlahSoal = parseInt(jumlahSoalInput.value);
                quizFormsContainer.innerHTML = ''; // Hapus form yang ada sebelumnya

                if (jumlahSoal > 0) {
                    for (let i = 0; i < jumlahSoal; i++) {
                        quizFormsContainer.insertAdjacentHTML('beforeend', getQuizFormTemplate(i));
                    }
                }
            }

            // Event listener untuk tombol "Buat Form Soal"
            generateFormsBtn.addEventListener('click', generateQuizForms);

            // Generate 1 form secara default saat halaman dimuat
            generateQuizForms();
        });
    </script>
@endsection
