<div class="sidebar bg-purple-800 text-white flex flex-col h-full rounded-tr-3xl rounded-br-3xl shadow-lg">
    <div class="logo p-6 text-center border-b border-purple-700">
        <h1 id="logo-sidebar" class="text-2xl font-bold tracking-wide">GamifyIT</h1>
    </div>
    <nav class="flex-1 py-6 space-y-2">
        <a href="{{ route('statistik') }}"
            class="nav-link flex items-center px-6 py-3 text-lg font-medium hover:bg-purple-700 hover:text-white transition-colors duration-200 ease-in-out rounded-xl mx-3
            {{ Request::routeIs('statistik') ? 'bg-purple-700 text-white' : '' }}">
            <i class="fas fa-home mr-4 text-xl"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('admin.users') }}"
            class="nav-link flex items-center px-6 py-3 text-lg font-medium hover:bg-purple-700 hover:text-white transition-colors duration-200 ease-in-out rounded-xl mx-3
            {{ Request::routeIs('admin.users') || Request::routeIs('admin.users.create') || Request::routeIs('admin.users.edit') ? 'bg-purple-700 text-white' : '' }}">
            <i class="fas fa-users mr-4 text-xl"></i>
            <span>Data Siswa</span>
        </a>
        <a href="{{ route('admin.materi.index') }}"
            class="nav-link flex items-center px-6 py-3 text-lg font-medium hover:bg-purple-700 hover:text-white transition-colors duration-200 ease-in-out rounded-xl mx-3
            {{ Request::routeIs('admin.materi.index') || Request::routeIs('admin.materi.create') || Request::routeIs('admin.materi.edit') ? 'bg-purple-700 text-white' : '' }}">
            <i class="fas fa-book-open mr-4 text-xl"></i>
            <span>Materi dan Kuis</span>
        </a>
        {{-- Link baru untuk Nilai Siswa --}}
        <a href="{{ route('admin.student_scores.index') }}"
            class="nav-link flex items-center px-6 py-3 text-lg font-medium hover:bg-purple-700 hover:text-white transition-colors duration-200 ease-in-out rounded-xl mx-3
            {{ Request::routeIs('admin.student_scores.index') ? 'bg-purple-700 text-white' : '' }}">
            <i class="fas fa-chart-bar mr-4 text-xl"></i> {{-- Menggunakan ikon chart-bar --}}
            <span>Nilai Siswa</span>
        </a>
        {{-- Link baru untuk Tambah Admin --}}
        <a href="{{ route('admin.create') }}"
            class="nav-link flex items-center px-6 py-3 text-lg font-medium hover:bg-purple-700 hover:text-white transition-colors duration-200 ease-in-out rounded-xl mx-3
            {{ Request::routeIs('admin.create') ? 'bg-purple-700 text-white' : '' }}">
            <i class="fas fa-user-plus mr-4 text-xl"></i> {{-- Ikon untuk menambah user --}}
            <span>Tambah Admin</span>
        </a>
    </nav>

    {{-- Social Media Icons (Optional, seperti di referensi) --}}
    <div class="social-icons p-6 border-t border-purple-700 flex justify-center space-x-4">
        <a href="#" class="text-white hover:text-purple-300 transition-colors duration-200">
            <i class="fab fa-facebook-f text-xl"></i>
        </a>
        <a href="#" class="text-white hover:text-purple-300 transition-colors duration-200">
            <i class="fab fa-twitter text-xl"></i>
        </a>
        <a href="#" class="text-white hover:text-purple-300 transition-colors duration-200">
            <i class="fab fa-google text-xl"></i>
        </a>
    </div>
</div>
