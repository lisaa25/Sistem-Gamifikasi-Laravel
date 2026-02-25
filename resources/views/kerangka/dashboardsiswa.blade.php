@extends('layout.master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboardsiswa.css') }}">

    <div class="dashboard-container">
        <div class="hero-section">
            <div class="hero-text">
                <h2>Halo, {{ Auth::user()->nama }}!👋</h2>
                <p class="speech">Ayo teruskan semangat belajarmu!<br> Raih lebih banyak Poin dan pertahankan Streak-mu. <br>
                    Klik Pembelajaran untuk melanjutkan petualangan✨</p>
                <div class="status-boxes">
                    <div class="status-box">
                        🪙 <strong>Poin:</strong> {{ $totalKoin }}
                    </div>
                </div>
            </div>
            <div class="hero-character">
                <img src="{{ asset('img/game/koko_duduk.png') }}" alt="Karakter" class="character-img-large">
            </div>
        </div>
        <div class="activity-section">
            <h2>Aktivitas Terbaru</h2>
            <div class="activity-cards" id="activityCardsContainer">
                {{-- Loop untuk menampilkan 10 aktivitas pertama secara individual --}}
                @forelse ($aktivitasTerbaru as $hasilKuis)
                    <a href="{{ route('kuis.detail_riwayat', ['hasilKuisId' => $hasilKuis->id]) }}" class="activity-card">
                        @php
                            $imagePath = 'img/materi/urutan' . $hasilKuis->materi->urutan . '.png';
                            if (!file_exists(public_path($imagePath))) {
                                $imagePath = 'img/materi/default.png';
                            }
                        @endphp
                        <img src="{{ asset($imagePath) }}" alt="{{ $hasilKuis->materi->judul_materi }}"
                            class="activity-img">

                        <div class="card-content">
                            <h4>{{ $hasilKuis->materi->judul_materi }}</h4>
                            <div class="progress-bar">
                                <div class="progress" style="width: {{ $hasilKuis->nilai_kuis }}%;">Nilai
                                    {{ $hasilKuis->nilai_kuis }}</div>
                            </div>
                            <p class="activity-date">Tanggal: {{ $hasilKuis->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="no-activity">Belum ada aktivitas kuis terbaru. Mulai pembelajaranmu sekarang!</p>
                @endforelse
            </div>

            {{-- Tombol "Selengkapnya" --}}
            @if ($hasMoreActivities)
                <div class="load-more-container">
                    <button id="loadMoreBtn" class="btn btn-primary">Selengkapnya</button>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtn = document.getElementById('loadMoreBtn');
                const activityCardsContainer = document.getElementById('activityCardsContainer');
                let offset = 10;
                const limit = 10;

                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        this.disabled = true;
                        this.textContent = 'Memuat...';

                        fetch(`{{ route('dashboard.load_more_activities') }}?offset=${offset}&limit=${limit}`)
                            .then(response => response.json())
                            .then(data => {
                                data.activities.forEach(activity => {
                                    const activityCard = `
                                {{-- <<=== UBAH HREF INI JUGA DI JAVASCRIPT ===>> --}}
                                    <a href="{{ url('/riwayat-kuis') }}/${activity.id}/detail" class="activity-card">
                                    <img src="${activity.image_url}" alt="${activity.judul_materi}" class="activity-img">
                                    <div class="card-content">
                                        <h4>${activity.judul_materi}</h4>
                                        <div class="progress-bar">
                                            <div class="progress" style="width: ${activity.nilai_kuis}%;">Nilai ${activity.nilai_kuis}</div>
                                        </div>
                                        <p class="activity-date">Tanggal: ${activity.created_at_formatted}</p>
                                    </div>
                                </a>
                            `;
                                    activityCardsContainer.insertAdjacentHTML('beforeend',
                                        activityCard);
                                });

                                offset += data.activities.length;

                                if (!data.has_more) {
                                    loadMoreBtn.style.display = 'none';
                                } else {
                                    loadMoreBtn.disabled = false;
                                    loadMoreBtn.textContent = 'Selengkapnya';
                                }
                            })
                            .catch(error => {
                                console.error('Error loading more activities:', error);
                                loadMoreBtn.disabled = false;
                                loadMoreBtn.textContent = 'Terjadi Kesalahan!';
                            });
                    });
                }
            });
        </script>
    @endsection
