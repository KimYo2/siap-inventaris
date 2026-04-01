<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('user.profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto' => 'nullable|boolean',
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto')) {
            if ($user->foto_path) {
                Storage::disk('public')->delete($user->foto_path);
            }
            $user->foto_path = $request->file('foto')
                ->store('users/avatar', 'public');
        }

        if ($request->boolean('hapus_foto') && $user->foto_path) {
            Storage::disk('public')->delete($user->foto_path);
            $user->foto_path = null;
        }

        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
