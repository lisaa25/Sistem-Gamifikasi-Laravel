{{-- resources/views/admin/kuis/index_all.blade.php --}}

@extends('admin.dashboard') {{-- Sesuaikan dengan layout admin Anda --}}

@section('content')
    <div class="container-kuis-overview">
        <h1>Daftar Semua Soal Kuis</h1>

        {{-- TOMBOL BARU UNTUK MENAMBAH SOAL KUIS --}}
        {{-- Tombol ini akan mengarahkan ke daftar materi, dari sana admin bisa memilih materi untuk menambah kuis --}}
        <a href="{{ route('admin.materi.index') }}" class="btn btn-primary mb-3">
            Tambah Soal Kuis Baru
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($kuis->isEmpty())
            <p>Belum ada soal kuis yang ditambahkan di seluruh materi.</p>
        @else
            <table class="table-kuis-overview">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Materi Terkait</th>
                        <th>Pertanyaan</th>
                        <th>Opsi A</th>
                        <th>Opsi B</th>
                        <th>Opsi C</th>
                        <th>Opsi D</th>
                        <th>Jawaban Benar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kuis as $key => $soal)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{-- Tampilkan judul materi, dan bisa juga link ke halaman kelola kuis materi tersebut --}}
                                @if ($soal->materi)
                                    <a href="{{ route('kuis.index', $soal->materi->id) }}">
                                        {{ $soal->materi->judul_materi }}
                                    </a>
                                @else
                                    <span class="text-danger">Materi Tidak Ditemukan</span>
                                @endif
                            </td>
                            <td>{{ $soal->pertanyaan }}</td>
                            <td>{{ $soal->opsi_a }}</td>
                            <td>{{ $soal->opsi_b }}</td>
                            <td>{{ $soal->opsi_c }}</td>
                            <td>{{ $soal->opsi_d }}</td>
                            <td>{{ $soal->jawaban }}</td>
                            <td>
                                <div class="action-buttons-group">
                                    {{-- Link Edit dan Hapus masih butuh materi_id karena rute kita membutuhkannya --}}
                                    <a href="{{ route('kuis.edit', ['materi' => $soal->materi_id, 'kuis' => $soal->id]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form
                                        action="{{ route('kuis.destroy', ['materi' => $soal->materi_id, 'kuis' => $soal->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus soal kuis ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <style>
        /* CSS untuk container overview */
        .container-kuis-overview {
            max-width: 1200px;
            /* Lebih lebar untuk menampung semua kolom */
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .container-kuis-overview h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
        }

        /* CSS untuk tabel */
        .table-kuis-overview {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-kuis-overview th,
        .table-kuis-overview td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
            /* Sedikit lebih kecil agar muat */
        }

        .table-kuis-overview th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* CSS untuk tombol (sesuaikan dengan gaya umum Anda) */
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            display: inline-block;
            margin-right: 5px;
            /* Jarak antar tombol */
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
        }


        .mb-3 {
            margin-bottom: 1rem;
        }

        /* Untuk group tombol aksi (agar berjejer) */
        .action-buttons-group {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .action-buttons-group form {
            margin: 0;
        }

        /* Gaya untuk alert */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        /* Gaya untuk link materi di tabel */
        .table-kuis-overview td a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .table-kuis-overview td a:hover {
            text-decoration: underline;
        }
    </style>
@endsection
