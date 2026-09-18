<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberSiswaController extends Controller
{
    public function index()
    {
        $siswa = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view('admin.member.siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('admin.member.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:50|unique:users,nis',
            'name' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'password' => 'required|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('profile', 'public');
        }

        User::create([
            'nis' => $request->nis,
            'name' => $request->name,
            'kelas' => $request->kelas,
            'email' => null,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'status' => 'aktif',
            'foto' => $foto,
        ]);

        return redirect()
            ->route('admin.member.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(User $siswa)
    {
        abort_if($siswa->role !== 'siswa', 404);

        return view('admin.member.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, User $siswa)
    {
        abort_if($siswa->role !== 'siswa', 404);

        $request->validate([
            'nis' => 'required|string|max:50|unique:users,nis,' . $siswa->id,
            'name' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'password' => 'nullable|min:6|confirmed',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $siswa->foto = $request->file('foto')->store('profile', 'public');
        }

        $siswa->nis = $request->nis;
        $siswa->name = $request->name;
        $siswa->kelas = $request->kelas;
        $siswa->status = $request->status;

        if ($request->filled('password')) {
            $siswa->password = Hash::make($request->password);
        }

        $siswa->save();

        return redirect()
            ->route('admin.member.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(User $siswa)
    {
        abort_if($siswa->role !== 'siswa', 404);

        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()
            ->route('admin.member.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}