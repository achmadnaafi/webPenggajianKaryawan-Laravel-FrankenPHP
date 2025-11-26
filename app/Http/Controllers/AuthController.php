<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Login manual
            Auth::login($user);
            $request->session()->regenerate();

            // Handle "ingat saya"
            if ($request->has('remember')) {
                Cookie::queue('remember_email', $user->email, 60 * 24 * 7); // 7 hari
            } else {
                // Jika tidak dicentang, hapus cookie
                Cookie::queue(Cookie::forget('remember_email'));
            }

            return redirect()->intended('/'); // redirect ke dashboard/index
        }

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

        // Jangan hapus cookie email supaya "ingat saya" tetap bekerja
        // Cookie::queue(Cookie::forget('remember_email'));

        return redirect('/login');
    }
}
