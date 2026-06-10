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

    public function forgot()
    {
        return view('auth.forgot_password');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();  
        return redirect('login');
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
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('login')->with('success', 'Registrasi Berhasil');
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

    public function processForgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Cek apakah user ada di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Alamat email tersebut tidak terdaftar di sistem kami.'
            ])->withInput();
        }

        // Jika email terdaftar, simpan email di session flash dan oper ke halaman password baru
        return redirect()->route('password.reset')->with('reset_email', $request->email);
    }

    // Menampilkan form ganti password baru
    public function resetPasswordForm()
    {
        // Cegah user iseng masuk ke halaman ini langsung tanpa input email dulu
        if (!session('reset_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }

        // Pertahankan email di session untuk request submit berikutnya
        session()->keep(['reset_email']);

        return view('auth.reset_password');
    }

    // Eksekusi update password baru ke database
    public function updatePassword(Request $request)
    {
        // Ambil email tersembunyi dari session
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi habis, silakan ulangi proses.']);
        }

        // Validasi kecocokan password baru (Pakai confirmed seperti register gess)
        $request->validate([
            'password' => 'required|confirmed'
        ]);

        // Update password di database
        User::where('email', $email)->update([
            'password' => bcrypt($request->password)
        ]);

        // Selesai! Lempar ke halaman login
        return redirect()->route('login')->with('status', 'Kata sandi berhasil diperbarui! Silakan masuk.');
    }

}
