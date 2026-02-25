
@section('login')
<section id="login">
    <div class="container">
        <form action="{{ route('adminPost') }}" method="POST">
            @csrf
            <h1>Login Admin</h1>
            <hr>
            <label for="email"><b>Email:</b></label>
            <input type="email" placeholder="Enter Email" name="email" id="email" required>
            <label for="password"><b>Password:</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>
            <hr>
            <button type="submit" class="registerbtn">Login</button>
        </form>
    </div>
</section>

<!-- memanggil css navbar -->
<link rel="stylesheet" href="{{ asset('css/admin/loginAdmin.css') }}">
