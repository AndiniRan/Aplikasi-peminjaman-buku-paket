<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberGuruController extends Controller
{
    public function index()
    {
        $guru = User::where('role', 'guru')
            ->orderBy('name')
            ->get();

        return view('admin.member.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.member.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('profile', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => null,
            'kelas' => null,
            'password' => Hash::make($request->password),
            'role' => 'guru',
            'status' => 'aktif',
            'foto' => $foto,
        ]);

        return redirect()
            ->route('admin.member.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(User $guru)
    {
        abort_if($guru->role !== 'guru', 404);

        return view('admin.member.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        abort_if($guru->role !== 'guru', 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guru->id,
            'password' => 'nullable|min:6|confirmed',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }

            $guru->foto = $request->file('foto')->store('profile', 'public');
        }

        $guru->name = $request->name;
        $guru->email = $request->email;
        $guru->status = $request->status;

        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return redirect()
            ->route('admin.member.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(User $guru)
    {
        abort_if($guru->role !== 'guru', 404);

        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()
            ->route('admin.member.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}