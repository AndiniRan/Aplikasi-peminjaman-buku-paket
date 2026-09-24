<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BukuImport;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')
            ->latest()
            ->get();

        return view(
            'admin.buku.index',
            compact('buku')
        );
    }

    public function create()
    {
        $kategori = Kategori::orderBy(
            'nama_kategori'
        )->get();

        return view(
            'admin.buku.create',
            compact('kategori')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'judul' => 'required|string|max:255',
            'penerbit' => 'required|string|max:150',
            'pengarang' => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'total_stok' => 'required|integer|min:0|max:100000',
            'sampul' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('sampul')) {
            $validated['sampul'] = $request
                ->file('sampul')
                ->store('sampul-buku', 'public');
        }

        $validated['stok_tersedia'] =
            $validated['total_stok'];

        Buku::create($validated);

        return redirect()
            ->route('admin.buku.index')
            ->with(
                'success',
                'Buku berhasil ditambahkan.'
            );
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');

        return view(
            'admin.buku.show',
            compact('buku')
        );
    }

    public function edit(Buku $buku)
    {
        $kategori = Kategori::orderBy(
            'nama_kategori'
        )->get();

        return view(
            'admin.buku.edit',
            compact('buku', 'kategori')
        );
    }

    public function update(
        Request $request,
        Buku $buku
    ) {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'judul' => 'required|string|max:255',
            'penerbit' => 'required|string|max:150',
            'pengarang' => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'total_stok' => 'required|integer|min:0|max:100000',
            'sampul' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $sedangDipinjam =
            $buku->total_stok -
            $buku->stok_tersedia;

        if (
            $validated['total_stok'] <
            $sedangDipinjam
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'total_stok' =>
                        'Total stok tidak boleh lebih kecil dari jumlah buku yang sedang dipinjam.',
                ]);
        }

        $validated['stok_tersedia'] =
            $validated['total_stok'] -
            $sedangDipinjam;

        if ($request->hasFile('sampul')) {

            if (
                $buku->sampul &&
                Storage::disk('public')
                    ->exists($buku->sampul)
            ) {
                Storage::disk('public')
                    ->delete($buku->sampul);
            }

            $validated['sampul'] = $request
                ->file('sampul')
                ->store('sampul-buku', 'public');
        }

        $buku->update($validated);

        return redirect()
            ->route('admin.buku.index')
            ->with(
                'success',
                'Buku berhasil diperbarui.'
            );
    }

    public function destroy(Buku $buku)
    {
        $sedangDipinjam =
            $buku->total_stok -
            $buku->stok_tersedia;

        if ($sedangDipinjam > 0) {
            return redirect()
                ->route('admin.buku.index')
                ->with(
                    'error',
                    'Buku tidak dapat dihapus karena masih sedang dipinjam.'
                );
        }

        if (
            $buku->sampul &&
            Storage::disk('public')
                ->exists($buku->sampul)
        ) {
            Storage::disk('public')
                ->delete($buku->sampul);
        }

        $buku->delete();

        return redirect()
            ->route('admin.buku.index')
            ->with(
                'success',
                'Buku berhasil dihapus.'
            );
    }

    public function katalog()
    {
        $buku = Buku::with('kategori')
            ->orderBy('judul')
            ->get();

        $kategori = Kategori::orderBy(
            'nama_kategori'
        )->get();

        return view(
            'admin.buku.katalog',
            compact('buku', 'kategori')
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(
                new BukuImport(),
                $request->file('file_excel')
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()->with(
                'error',
                'Import buku gagal. Periksa kembali format file Excel.'
            );
        }

        return redirect()
            ->route('admin.buku.index')
            ->with(
                'success',
                'Data buku berhasil diimport.'
            );
    }
}