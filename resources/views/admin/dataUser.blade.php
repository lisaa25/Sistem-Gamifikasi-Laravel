@extends('admin.dashboard')

@push('styles')
    <!-- Push custom CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/admin/dataUser.css') }}">
@endpush

@section('content')
    <div class="container bg-white p-8 rounded-lg shadow-md">
        <div class="header-flex flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Data Siswa</h1>
            <a href="{{ route('admin.users.create') }}"
                class="btn-tambah bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-full text-lg font-semibold transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Siswa</span>
            </a>
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

        {{-- Bagian Filter Kelas --}}
        <div class="filter-kelas flex gap-3 mb-6 flex-wrap">
            <a href="{{ route('admin.users', ['kelas' => 'all']) }}"
                class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                {{ !isset($selectedKelas) || $selectedKelas == 'all' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua Kelas
            </a>
            <a href="{{ route('admin.users', ['kelas' => '8A']) }}"
                class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                {{ isset($selectedKelas) && $selectedKelas == '8A' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Kelas A
            </a>
            <a href="{{ route('admin.users', ['kelas' => '8B']) }}"
                class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                {{ isset($selectedKelas) && $selectedKelas == '8B' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Kelas B
            </a>
            <a href="{{ route('admin.users', ['kelas' => '8C']) }}"
                class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                {{ isset($selectedKelas) && $selectedKelas == '8C' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Kelas C
            </a>
            <a href="{{ route('admin.users', ['kelas' => '8D']) }}"
                class="btn-filter px-5 py-2 rounded-full font-medium transition-all duration-200
                {{ isset($selectedKelas) && $selectedKelas == '8D' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Kelas D
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-purple-700 text-white text-left">
                        <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tl-lg">ID</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Kelas</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm rounded-tr-lg">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $user->id }}</td>
                            <td class="py-3 px-4">{{ $user->nama }}</td>
                            <td class="py-3 px-4">{{ $user->kelas }}</td>
                            <td class="py-3 px-4">{{ $user->email }}</td>
                            <td class="py-3 px-4 flex items-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn-action bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn-action bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition-colors duration-200"
                                        onclick="return confirm('Apa Anda yakin ingin menghapus user ini?');"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada data siswa.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
