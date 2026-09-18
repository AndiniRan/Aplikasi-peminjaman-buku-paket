<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';
    protected $fillable = [
        'user_id',
        'buku_id',
        'pengajuan_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
    ];
    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo( Pengajuan::class, 'pengajuan_id', 'pengajuan_id');
    }
}
