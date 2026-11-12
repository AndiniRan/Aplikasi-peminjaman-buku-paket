<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengajuan extends Model
{
    protected $table = 'detail_pengajuan';

    protected $primaryKey = 'detail_pengajuan_id';

    public $timestamps = false;

    protected $fillable = [
        'pengajuan_id',
        'buku_id',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(
            Pengajuan::class,
            'pengajuan_id',
            'pengajuan_id'
        );
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(
            Buku::class,
            'buku_id',
            'id'
        );
    }
}
