<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Email tidak ditemukan
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.'
            ])->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.'
            ])->withInput();
        }

        // Login manual
        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->role == 1) {
            return redirect()->intended('/')
                ->with('login_success', 'Selamat datang Super Admin!');
        } elseif ($user->role == 2) {
            return redirect()->intended('/')
                ->with('login_success', 'Selamat datang Admin!');
        } else {
            return redirect()->intended('/')
                ->with('login_success', 'Selamat datang!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('status', 'Berhasil logout!');
    }
}
