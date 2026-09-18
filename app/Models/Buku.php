<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'kategori_id',
        'judul',
        'penerbit',
        'pengarang',
        'tahun_terbit',
        'total_stok',
        'stok_tersedia',
        'sampul'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'buku_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'buku_id');
    }
}
