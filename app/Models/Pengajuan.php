<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_peminjaman';
    protected $primaryKey = 'pengajuan_id';
    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pengajuan',
        'tanggal_disiapkan',
        'batas_pengambilan',
        'tanggal_diambil',
        'status',
        'alasan_ditolak',
    ];
    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_disiapkan' => 'datetime',
        'batas_pengambilan' => 'datetime',
        'tanggal_diambil' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'pengajuan_id', 'pengajuan_id');
    }
}
