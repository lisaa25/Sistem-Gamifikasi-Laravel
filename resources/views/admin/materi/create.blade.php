@extends('admin.dashboard') {{-- Pastikan ini extend layout admin yang benar --}}

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Materi Baru</h1>

        @if (session('success'))
            <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

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

        <form action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="form-group">
                <label for="level_id" class="block text-gray-700 text-sm font-bold mb-2">Level:</label>
                <div class="flex items-center space-x-2">
                    <select name="level_id" id="level_id"
                        class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        required>
                        <option value="">-- Pilih Level --</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->nama_level }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="addLevelButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline transition duration-200 ease-in-out flex items-center text-xs">
                        <i class="fas fa-plus mr-1"></i> Tambah Level
                    </button>
                </div>
                @error('level_id')
                    <div class="text-red-500 text-xs italic mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="judul_materi" class="block text-gray-700 text-sm font-bold mb-2">Judul Materi:</label>
                <input type="text" name="judul_materi" id="judul_materi"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('judul_materi') }}" required>
                @error('judul_materi')
                    <div class="text-red-500 text-xs italic mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi_materi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Materi:</label>
                <textarea name="deskripsi_materi" id="deskripsi_materi"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    rows="5">{{ old('deskripsi_materi') }}</textarea>
                @error('deskripsi_materi')
                    <div class="text-red-500 text-xs italic mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="file_pdf" class="block text-gray-700 text-sm font-bold mb-2">File PDF:</label>
                <input type="file" name="file_pdf" id="file_pdf"
                    class="form-control-file block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-purple-50 file:text-purple-700
                    hover:file:bg-purple-100"
                    required>
                @error('file_pdf')
                    <div class="text-red-500 text-xs italic mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="urutan" class="block text-gray-700 text-sm font-bold mb-2">Urutan Materi dalam Level:</label>
                <input type="number" name="urutan" id="urutan"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('urutan') }}" required min="1">
                @error('urutan')
                    <div class="text-red-500 text-xs italic mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-start space-x-4 mt-6">
                <button type="submit"
                    class="btn btn-primary bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Unggah Materi
                </button>
                <a href="{{ route('admin.materi.index') }}"
                    class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Modal untuk Tambah Level Baru --}}
    <div id="addLevelModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Tambah Level Baru</h2>
            <form id="addLevelForm" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label for="new_level_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Level:</label>
                    <input type="text" name="nama_level" id="new_level_nama"
                        class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <div id="new_level_nama_error" class="text-red-500 text-xs italic mt-1 hidden"></div>
                </div>
                <div class="form-group">
                    <label for="new_level_deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi
                        (Opsional):</label>
                    <textarea name="deskripsi" id="new_level_deskripsi"
                        class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        rows="3"></textarea>
                </div>
                <div class="flex items-center justify-end space-x-4 mt-6">
                    <button type="button" id="cancelAddLevel"
                        class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                        Batal
                    </button>
                    <button type="submit"
                        class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                        Simpan Level
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addLevelButton = document.getElementById('addLevelButton');
            const addLevelModal = document.getElementById('addLevelModal');
            const cancelAddLevel = document.getElementById('cancelAddLevel');
            const addLevelForm = document.getElementById('addLevelForm');
            const levelSelect = document.getElementById('level_id');
            const newLevelNamaInput = document.getElementById('new_level_nama');
            const newLevelNamaError = document.getElementById('new_level_nama_error');

            // Show modal
            addLevelButton.addEventListener('click', function() {
                addLevelModal.classList.remove('hidden');
            });

            // Hide modal
            cancelAddLevel.addEventListener('click', function() {
                addLevelModal.classList.add('hidden');
                addLevelForm.reset(); // Reset form when closing
                newLevelNamaError.classList.add('hidden'); // Hide error message
            });

            // Hide modal if clicked outside
            addLevelModal.addEventListener('click', function(event) {
                if (event.target === addLevelModal) {
                    addLevelModal.classList.add('hidden');
                    addLevelForm.reset(); // Reset form when closing
                    newLevelNamaError.classList.add('hidden'); // Hide error message
                }
            });

            // Handle form submission for new level via AJAX
            addLevelForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Prevent default form submission

                newLevelNamaError.classList.add('hidden'); // Hide previous error

                const formData = new FormData(addLevelForm);
                const namaLevel = formData.get('nama_level');

                if (!namaLevel.trim()) {
                    newLevelNamaError.textContent = 'Nama level wajib diisi.';
                    newLevelNamaError.classList.remove('hidden');
                    return;
                }

                try {
                    const response = await fetch('{{ route('admin.levels.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (data.success) {
                            // Add new level to the select dropdown
                            const newOption = document.createElement('option');
                            newOption.value = data.level.id;
                            newOption.textContent = data.level.nama_level;
                            levelSelect.appendChild(newOption);

                            // Select the newly added level
                            levelSelect.value = data.level.id;

                            // Show success message (you might want a more prominent one)
                            alert(
                                'Level berhasil ditambahkan!'
                            ); // Using alert for simplicity, consider a custom modal
                            addLevelModal.classList.add('hidden'); // Hide modal
                            addLevelForm.reset(); // Reset form
                            newLevelNamaError.classList.add('hidden'); // Hide error message
                        } else {
                            // Handle validation errors from server
                            if (data.errors && data.errors.nama_level) {
                                newLevelNamaError.textContent = data.errors.nama_level[0];
                                newLevelNamaError.classList.remove('hidden');
                            } else {
                                alert('Gagal menambahkan level: ' + (data.message ||
                                    'Terjadi kesalahan.'));
                            }
                        }
                    } else {
                        // Handle HTTP errors (e.g., 422 Unprocessable Entity for validation errors)
                        const errorData = await response.json();
                        if (errorData.errors && errorData.errors.nama_level) {
                            newLevelNamaError.textContent = errorData.errors.nama_level[0];
                            newLevelNamaError.classList.remove('hidden');
                        } else {
                            alert('Gagal menambahkan level: ' + (errorData.message ||
                                'Terjadi kesalahan server.'));
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan jaringan atau server. Silakan coba lagi.');
                }
            });
        });
    </script>
@endsection
