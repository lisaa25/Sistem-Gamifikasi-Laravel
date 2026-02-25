@extends('layout.master')

@push('styles')
    {{-- Kita akan menggunakan satu CSS file untuk profil dan lencana --}}
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')
    {{-- Mengubah .profil-container menjadi .main-container sesuai desain baru --}}
    <div class="main-container">
        {{-- Notifikasi Sukses/Error - Dipindahkan ke atas profil-card untuk penempatan yang lebih baik --}}
        <div class="notification-area">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Ini adalah bagian KIRI: Profil Pengguna --}}
        <div class="profil-section">
            <div class="profil-card">
                <form id="profileForm" action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profil-header">
                        <label for="foto_input" class="profil-foto-wrapper">
                            {{-- Tampilkan foto profil user atau default --}}
                            <img id="profile_photo_preview"
                                src="{{ asset('storage/img/profil/' . ($user->foto ?? 'default_profile.png')) }}"
                                alt="Foto Profil Siswa" class="profil-foto">
                            <input type="file" id="foto_input" name="foto" style="display: none;" accept="image/*">
                            <div class="change-photo-overlay" id="change_photo_overlay">📸 Ganti Foto</div>
                        </label>
                        <div class="profil-nama-level">
                            {{-- Nama User (Mode Tampilan) --}}
                            <h2 id="display_nama">{{ $user->nama ?? 'Nama Siswa' }}</h2>
                            {{-- Nama User (Mode Edit) --}}
                            <input type="text" id="edit_nama" name="nama" value="{{ old('nama', $user->nama ?? '') }}"
                                class="input-edit" style="display: none;">

                            {{-- Menampilkan Level Siswa --}}
                            <!-- <span class="badge-level">
                                                            ⭐ {{ $user->level->nama_level ?? 'Level Tidak Diketahui' }}<p class="text-sm text-gray-600">
                                                                {{ $user->level->deskripsi }}</p>
                                                        </span>-->
                        </div>
                    </div>

                    <div class="profil-body">
                        {{-- Email --}}
                        <div class="profil-item">
                            <strong>📧 Email:</strong>
                            <span id="display_email">{{ $user->email ?? 'email@example.com' }}</span>
                            <input type="email" id="edit_email" name="email"
                                value="{{ old('email', $user->email ?? '') }}" class="input-edit" style="display: none;">
                        </div>
                        {{-- Kelas --}}
                        <div class="profil-item">
                            <strong>🏫 Kelas:</strong>
                            <span id="display_kelas">{{ $user->kelas ?? 'Belum ditentukan' }}</span>
                            <input type="text" id="edit_kelas" name="kelas"
                                value="{{ old('kelas', $user->kelas ?? '') }}" class="input-edit" style="display: none;">
                        </div>
                        {{-- Poin (ambil dari user object, asumsi ada kolom total_koin) --}}
                        <div class="profil-item"><strong>⭐ Poin:</strong> {{ $user->total_koin ?? '0' }}</div>
                    </div>

                    <div class="profil-actions">
                        <button type="button" id="edit_button" class="btn-edit">✏️ Edit Profil</button>
                        <button type="submit" id="save_button" class="btn-save" style="display: none;">💾 Simpan</button>
                        <button type="button" id="cancel_button" class="btn-cancel" style="display: none;">❌ Batal</button>
                    </div>
                </form>
                {{-- Tombol untuk Membuka Modal Password --}}
                <button type="button" id="open_password_modal_button" class="btn-edit mt-3">Ubah Password</button>
            </div> {{-- End profil-card --}}
        </div> {{-- End profil-section --}}

        {{-- Ini adalah bagian KANAN: Koleksi Lencana --}}
        <div class="badges-section">
            <div class="badges-header">
                <h3>Koleksi Lencanamu!</h3>
                {{-- Karakter info versi kecil, dipindah dari lencanadua --}}
                <div class="karakter-info-small">
                    <div class="karakter-container-small">
                        <div class="glow-small"></div>
                        <img src="{{ asset('img/game/koko_lencana.png') }}" alt="Karakter Koko"
                            class="karakter-small unlocked koko-glow robot-right" style="transform">
                    </div>
                    <div class="info-text-small">
                        <p>Ada banyak lencana keren yang bisa kamu dapatkan loh!<br>

                            <a href="{{ route('badges.all') }}">Cari tahu caranya</a>
                            dan mulai kumpulkan ✨
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid-lencana">
                {{-- Pastikan $badgesDisplayData dikirim dari UserInformationController --}}
                @foreach ($badgesDisplayData as $badge)
                    <div class="lencana {{ $badge['is_unlocked'] ? 'unlocked' : 'locked' }}">
                        <img src="{{ $badge['is_unlocked'] ? $badge['gambar'] : $badge['locked_image'] }}"
                            alt="{{ $badge['is_unlocked'] ? $badge['nama_lencana'] : 'Lencana Terkunci' }}">
                        <p class="badge-name">{{ $badge['is_unlocked'] ? $badge['nama_lencana'] : '???' }}</p>
                        <p class="badge-description">
                            {{ $badge['is_unlocked'] ? $badge['deskripsi'] : 'Kumpulkan koin atau selesaikan misi untuk membuka lencana ini!' }}
                        </p>
                        @if ($badge['is_unlocked'])
                            <small class="badge-date">Dicapai: {{ $badge['tanggal_dicapai'] }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL BOX UNTUK UBAH PASSWORD --}}
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h4>Ubah Password</h4>
            <form id="passwordForm" action="{{ route('user.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="profil-item">
                    <strong>🔒 Password Lama:</strong>
                    <input type="password" name="current_password" class="input-edit" required>
                    @error('current_password')
                        <div class="text-danger-small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="profil-item">
                    <strong>🔑 Password Baru:</strong>
                    <input type="password" name="new_password" class="input-edit" required>
                    @error('new_password')
                        <div class="text-danger-small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="profil-item">
                    <strong>↩️ Konfirmasi Password:</strong>
                    <input type="password" name="new_password_confirmation" class="input-edit" required>
                </div>
                <div class="profil-actions">
                    <button type="submit" class="btn-save">Simpan Password</button>
                    <button type="button" id="cancel_password_modal" class="btn-cancel">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButton = document.getElementById('edit_button');
            const saveButton = document.getElementById('save_button');
            const cancelButton = document.getElementById('cancel_button');
            const profileForm = document.getElementById('profileForm');

            const displayNama = document.getElementById('display_nama');
            const editNama = document.getElementById('edit_nama');
            const displayEmail = document.getElementById('display_email');
            const editEmail = document.getElementById('edit_email');
            const displayKelas = document.getElementById('display_kelas');
            const editKelas = document.getElementById('edit_kelas');

            const fotoInput = document.getElementById('foto_input');
            const profilePhotoPreview = document.getElementById('profile_photo_preview');
            const changePhotoOverlay = document.getElementById('change_photo_overlay');

            let originalNama = displayNama.textContent;
            let originalEmail = displayEmail.textContent;
            let originalKelas = displayKelas.textContent;
            let originalPhotoSrc = profilePhotoPreview.src;

            function toggleEditMode(isEditMode) {
                displayNama.style.display = isEditMode ? 'none' : 'block';
                editNama.style.display = isEditMode ? 'block' : 'none';
                displayEmail.style.display = isEditMode ? 'none' : 'block';
                editEmail.style.display = isEditMode ? 'block' : 'none';
                displayKelas.style.display = isEditMode ? 'none' : 'block';
                editKelas.style.display = isEditMode ? 'block' : 'none';

                editButton.style.display = isEditMode ? 'none' : 'inline-block';
                saveButton.style.display = isEditMode ? 'inline-block' : 'none';
                cancelButton.style.display = isEditMode ? 'inline-block' : 'none';

                fotoInput.disabled = !isEditMode;
                changePhotoOverlay.style.display = isEditMode ? 'flex' : 'none';
            }

            function updateDisplayValues() {
                originalNama = displayNama.textContent;
                originalEmail = displayEmail.textContent;
                originalKelas = displayKelas.textContent;
                originalPhotoSrc = profilePhotoPreview.src;
            }

            editButton.addEventListener('click', function() {
                toggleEditMode(true);
            });

            cancelButton.addEventListener('click', function() {
                editNama.value = originalNama;
                editEmail.value = originalEmail;
                editKelas.value = originalKelas;

                profilePhotoPreview.src = originalPhotoSrc;
                fotoInput.value = '';

                toggleEditMode(false);
            });

            fotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            if (img.width !== img.height) {
                                alert(
                                    "Disarankan mengunggah gambar profil berbentuk persegi untuk tampilan terbaik!"
                                );
                            }
                            profilePhotoPreview.src = e.target.result;
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Inisialisasi mode edit profil
            @if ($errors->any() && !session('password_error'))
                toggleEditMode(true);
            @else
                toggleEditMode(false);
            @endif

            @if (session('success'))
                updateDisplayValues();
                toggleEditMode(false);
            @endif


            // --- MODAL PASSWORD LOGIC ---
            const passwordModal = document.getElementById('passwordModal');
            const openPasswordModalButton = document.getElementById('open_password_modal_button');
            const closeButton = document.querySelector('.modal .close-button');
            const cancelPasswordModalButton = document.getElementById('cancel_password_modal');
            const passwordForm = document.getElementById('passwordForm');

            // Function to open the modal
            function openPasswordModal() {
                passwordModal.style.display = 'block';
                // Optional: clear form fields when opening
                passwordForm.reset();
                // Clear previous error messages if any
                document.querySelectorAll('.text-danger-small').forEach(el => el.remove());
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            // Function to close the modal
            function closePasswordModal() {
                passwordModal.style.display = 'none';
                passwordForm.reset(); // Clear form fields when closing
                // Clear previous error messages when closing
                document.querySelectorAll('.text-danger-small').forEach(el => el.remove());
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            // Event listener for opening the modal
            openPasswordModalButton.addEventListener('click', openPasswordModal);

            // Event listeners for closing the modal
            closeButton.addEventListener('click', closePasswordModal);
            cancelPasswordModalButton.addEventListener('click', closePasswordModal);

            // Close modal if user clicks outside of it
            window.addEventListener('click', function(event) {
                if (event.target == passwordModal) {
                    closePasswordModal();
                }
            });

            // If there are password validation errors, open the modal
            @if ($errors->has('current_password') || $errors->has('new_password'))
                openPasswordModal();
                // Optionally, re-populate fields if old input is available (Laravel handles this for text inputs)
                // For password fields, you generally don't re-populate old values for security reasons.
            @endif
        });
    </script>
@endpush
