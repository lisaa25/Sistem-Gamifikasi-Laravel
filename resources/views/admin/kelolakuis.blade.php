@extends('admin.dashboard')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/kelola_kuis.css') }}">

    <div class="kelola-kuis-container">
        <h1>Kelola Kuis</h1>

        <div class="action-buttons">
            <a href="#" class="btn btn-primary">Tambah Kuis Baru</a>
        </div>

        <div class="kuis-list">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Soal</th>
                        <th>Pilihan</th>
                        <th>Jawaban</th>
                        <th>Level</th>
                        <th>Materi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $daftarSoal = [
                            [
                                'teks' => 'Apa pengertian sederhana dari Algoritma?',
                                'jawaban' => 'Urutan langkah-langkah logis untuk menyelesaikan masalah',
                                'pilihan' => [
                                    'Kumpulan angka-angka rumit',
                                    'Urutan langkah-langkah logis untuk menyelesaikan masalah',
                                    'Cara menghitung dengan cepat ',
                                    'Program komputer yang canggih',
                                ],
                            ],
                            [
                                'teks' =>
                                    'Manakah di bawah ini yang BUKAN merupakan contoh algoritma dalam kehidupan sehari-hari?',
                                'jawaban' => 'Daftar belanja di supermarket',
                                'pilihan' => [
                                    'Resep membuat kue',
                                    'Langkah-langkah menginstal aplikasi',
                                    'Cara kerja mesin cuci',
                                    'Daftar belanja di supermarket',
                                ],
                            ],
                            [
                                'teks' => 'Mengapa urutan langkah dalam algoritma itu penting?',
                                'jawaban' => 'Agar hasilnya selalu benar',
                                'pilihan' => [
                                    'Agar terlihat lebih panjang',
                                    'Agar hasilnya selalu benar',
                                    'Agar lebih mudah dihafal',
                                    'Agar bisa dikerjakan oleh banyak orang',
                                ],
                            ],
                            [
                                'teks' =>
                                    'Contoh algoritma sederhana adalah cara membuat teh. Langkah pertama yang benar adalah...',
                                'jawaban' => 'Memanaskan air',
                                'pilihan' => [
                                    'Memasukkan teh ke dalam gelas',
                                    'Menuangkan air panas ke dalam gelas',
                                    'Memanaskan air',
                                    'Menambahkan gula sesuai selera',
                                ],
                            ],
                            [
                                'teks' =>
                                    'Jika urutan langkah dalam algoritma membuat nasi goreng terbalik (misalnya, nasi dimasukkan terakhir), apa yang mungkin terjadi?',
                                'jawaban' => 'Nasi goreng tidak akan jadi dengan benar',
                                'pilihan' => [
                                    'Rasanya akan lebih enak',
                                    'Nasi goreng tidak akan jadi dengan benar',
                                    'Proses memasak jadi lebih cepat',
                                    'Tidak ada perbedaan hasil',
                                ],
                            ],
                            [
                                'teks' =>
                                    'Kegiatan sehari-hari seperti memakai sepatu mengikuti urutan langkah. Urutan yang benar biasanya adalah...',
                                'jawaban' => 'Memakai sepatu lalu mengikat tali',
                                'pilihan' => [
                                    'Mengikat tali lalu memakai sepatu',
                                    'Memakai sepatu lalu mengikat tali',
                                    'Memakai sepatu salah satu saja',
                                    'Mengikat tali saja',
                                ],
                            ],
                            [
                                'teks' => 'Algoritma membantu kita untuk...',
                                'jawaban' => 'Menyelesaikan masalah secara sistematis',
                                'pilihan' => [
                                    'Membuat hidup lebih rumit',
                                    'Menyelesaikan masalah secara sistematis',
                                    'Menghafal banyak informasi',
                                    'Berkomunikasi dengan orang lain',
                                ],
                            ],
                            [
                                'teks' => 'Dalam sebuah algoritma, setiap langkah harus...',
                                'jawaban' => 'Logis dan terdefinisi dengan baik',
                                'pilihan' => [
                                    'Panjang dan detail',
                                    'Singkat dan tidak jelas',
                                    'Logis dan terdefinisi dengan baik',
                                    'Membingungkan',
                                ],
                            ],
                            [
                                'teks' => 'Ketika kita mengikuti resep masakan, kita sedang menerapkan konsep...',
                                'jawaban' => 'Algoritma',
                                'pilihan' => ['Matematika', 'Fisika', 'Algoritma', 'Kimia'],
                            ],
                            [
                                'teks' => 'Tujuan utama dari algoritma adalah...',
                                'jawaban' => 'Menyelesaikan suatu masalah atau tugas',
                                'pilihan' => [
                                    'Membuat program komputer',
                                    'Menyajikan informasi yang kompleks',
                                    'Menyelesaikan suatu masalah atau tugas',
                                    'Menghibur pengguna komputer',
                                ],
                            ],
                        ];
                        $nomor = 1;
                    @endphp
                    @foreach ($daftarSoal as $soal)
                        <tr>
                            <td>{{ $nomor++ }}</td>
                            <td>{{ $soal['teks'] }}</td>
                            <td>
                                A. {{ $soal['pilihan'][0] }}<br>
                                B. {{ $soal['pilihan'][1] }}<br>
                                C. {{ $soal['pilihan'][2] }}<br>
                                D. {{ $soal['pilihan'][3] }}
                            </td>
                            <td>{{ $soal['jawaban'] }}</td>
                            <td>1</td>
                            <td>1</td> {{-- Ganti dengan data level sebenarnya jika ada --}}
                            <td class="aksi">
                                <a href="#" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
