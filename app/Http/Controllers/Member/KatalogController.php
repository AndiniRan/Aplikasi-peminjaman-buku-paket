<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;

class KatalogController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')
            ->orderBy('judul', 'asc')
            ->get();

        $kategori = Kategori::orderBy(
            'nama_kategori',
            'asc'
        )->get();

        return view(
            'member.katalog.index',
            compact('buku', 'kategori')
        );
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');

        return view(
            'member.katalog.show',
            compact('buku')
        );
    }

    public function pengajuan(Buku $buku)
    {
        $buku->load('kategori');

        return view(
            'member.katalog.pengajuan',
            compact('buku')
        );
    }
}