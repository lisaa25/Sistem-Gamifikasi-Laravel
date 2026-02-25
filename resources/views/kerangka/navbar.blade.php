<!-- Navbar -->
<nav id="navbar">
    <div id="nama-toko">
        <a href="{{ url('home') }}"><b>GamifyIT</b></a>
    </div>

    <div id="navbar-nav">
        <a href="{{ route('dashboard.siswa') }}" class="nav-link">Beranda</a>
        <a href="{{ route('pembelajaran.index') }}" class="nav-link">Pembelajaran</a>
        <a href="{{ route('leaderboard.siswa') }}" class="nav-link">Leaderboard</a>
    </div>

    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div class="dropdown">
            @guest
                <button class="dropbtn">Login <i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="{{ route('login') }}">Siswa</a>
                    <a href="{{ route('loginAdmin') }}">Guru</a>
                </div>
            @endguest

            @auth
                <button class="dropbtn">{{ Auth::user()->nama }} <i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="{{ route('user.show') }}">Profil Siswa</a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

<!-- Feather Icons (optional) -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace()
</script>
