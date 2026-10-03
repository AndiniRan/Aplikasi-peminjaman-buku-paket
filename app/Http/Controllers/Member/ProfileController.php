<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view(
            'member.profile.profile',
            compact('user')
        );
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if (
            $user->foto &&
            Storage::disk('public')->exists($user->foto)
        ) {
            Storage::disk('public')->delete(
                $user->foto
            );
        }

        $user->foto = $request
            ->file('foto')
            ->store('profile', 'public');

        $user->save();

        return redirect()
            ->route('member.profile.index')
            ->with(
                'success',
                'Foto profil berhasil diperbarui.'
            );
    }
}