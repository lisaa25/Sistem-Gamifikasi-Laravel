<nav class="navbar bg-white shadow-md p-6 flex items-center justify-between rounded-bl-3xl rounded-br-3xl mx-6 mt-6">
    <div class="logo">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard Guru</h1>
    </div>
    <div class="user-info flex items-center space-x-4">
        @if (Auth::guard('admin')->check())
            <span class="text-gray-700 font-medium">Welcome, {{ Auth::guard('admin')->user()->nama ?? 'Admin' }}</span>
            {{-- PERBAIKAN DI SINI --}}
            <div class="relative">
                <button id="adminDropdownButton" class="flex items-center space-x-2 focus:outline-none">
                    <img src="https://placehold.co/40x40/E0BBE4/FFFFFF?text=AD" alt="Admin Avatar"
                        class="w-10 h-10 rounded-full border-2 border-purple-500">
                    <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                {{-- Dropdown menu: Sekarang dikontrol oleh JavaScript --}}
                <div id="adminDropdownMenu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden">
                    <a href="{{ route('admin.profile') }}"
                        class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block px-4 py-2 text-red-600 hover:bg-gray-100">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        @else
            <span class="text-gray-700">Please log in</span>
            <a href="{{ route('loginAdmin') }}" class="text-purple-600 hover:underline">Login</a>
        @endif
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('adminDropdownButton');
        const dropdownMenu = document.getElementById('adminDropdownMenu');

        dropdownButton.addEventListener('click', function() {
            dropdownMenu.classList.toggle('hidden'); // Toggle class 'hidden'
        });

        // Sembunyikan dropdown jika klik di luar area dropdown
        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden'); // Tambahkan 'hidden' jika klik di luar
            }
        });
    });
</script>
