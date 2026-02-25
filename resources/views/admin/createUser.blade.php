@extends('admin.dashboard')

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah User Baru</h1>

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

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="form-group">
                <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                <input type="text" name="nama" id="nama"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('nama') }}" required>
            </div>
            <div class="form-group">
                <label for="kelas" class="block text-gray-700 text-sm font-bold mb-2">Kelas:</label>
                <input type="text" name="kelas" id="kelas"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('kelas') }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" name="password" id="password"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi
                    Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    required>
            </div>

            <div class="flex items-center justify-start space-x-4 mt-6">
                <button type="submit"
                    class="btn btn-primary bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Simpan
                </button>
                <a href="{{ route('admin.users') }}"
                    class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
