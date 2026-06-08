<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
    
class AuthController extends Controller
{
    // Menampilkan view
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();  
        return redirect('/login');
    }

    // Proses input user
    public function processRegister(Request $request)
    {
        // Validasi input user
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        User::create([
            // Insert data ke table users
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return redirect('/login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()->with('error', 'Email atau Password salah');
    }

}
