<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile');
    }

    public function updateUsername(Request $request)
    {
        $user = auth()->user();
        
        // Ambil nama primary key secara dinamis (mengantisipasi id vs user_id)
        $primaryKey = $user->getKeyName(); 

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->$primaryKey . ',' . $primaryKey],
        ]);

        $user->update([
            'name' => $request->username,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Username berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diganti!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        // Hapus foto lama jika ada sebelum menumpuk dengan yang baru
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar' => $path,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil diperbarui!');
    }
}