<?php

namespace App\Http\Controllers;

use App\Models\ModelUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //untuk authentikasi
use Illuminate\Support\Facades\Hash; //untuk hash password

class AuthController extends Controller
{
    // login user
    public function login()
    {
        return view('kerangka.login');
    }

    // login user post dari form
    public function loginPost(Request $request)
    {
        //validasi input
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        //kredensial
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard-siswa');
        } else {
            return redirect()->route('login')->withErrors(['email' => 'Email atau password salah']);
        }
    }

    // logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); //ke halaman utama
    }
}
