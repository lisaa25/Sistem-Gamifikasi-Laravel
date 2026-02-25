@extends('admin.dashboard')
@section('content')
    <div class="container">
        <h1>Daftar Materi</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Judul Materi</th>
                    <th>Jumlah Slide</th>
                    <th>Deskripsi</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id_produk }}</td>
                        <td>
                            @if ($product->gambar_produk)
                                <img src="{{ asset('img/produk/' . $product->gambar_produk) }}"
                                    alt="{{ $product->nama_produk }}" width="100">
                            @else
                                Tidak ada gambar
                            @endif
                        </td>
                        <td>{{ $product->nama_produk }}</td>
                        <td>{{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->deskripsi }}</td>
                        <td>{{ $product->kategori }}</td>
                        <td>
                            <div class="button-container">
                                @if ($product->is_active)
                                    <!-- Deactivate Button -->
                                    <form action="{{ route('admin.products.deactivate', $product->id_produk) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-deactivate" title="Deactivate">
                                            <i class="fas fa-toggle-off"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Activate Button -->
                                    <form action="{{ route('admin.products.activate', $product->id_produk) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-success" title="Activate">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>
                                @endif
                                <!-- Edit and Delete Buttons -->
                                <a href="{{ route('admin.products.edit', $product->id_produk) }}" class="btn btn-edit"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.delete', $product->id_produk) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <link rel="stylesheet" href="{{ asset('css/admin/tabelProduk.css') }}">
@endsection
