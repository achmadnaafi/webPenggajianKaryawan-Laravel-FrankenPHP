<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Gunakan Auth::attempt untuk cek otomatis password hashed
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Handle "ingat saya" custom cookie
            if ($request->has('remember')) {
                Cookie::queue('remember_email', $request->email, 60 * 24 * 7); // 7 hari
            } else {
                Cookie::queue(Cookie::forget('remember_email'));
            }

            return redirect()->intended('/'); // redirect ke dashboard/index
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Cookie tetap dipertahankan jika mau "ingat saya"
        // Cookie::queue(Cookie::forget('remember_email')); // opsional

        return redirect('/login');
    }
}
