
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengajuan', function (Blueprint $table) {
            $table->id('detail_pengajuan_id');

            $table->foreignId('pengajuan_id')
                ->constrained(
                    'pengajuan_peminjaman',
                    'pengajuan_id'
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('buku_id')
                ->constrained('buku', 'id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('jumlah');

            $table->unique(
                ['pengajuan_id', 'buku_id'],
                'detail_pengajuan_unique_buku'
            );
        });

        // Migrasi data dari pengajuan lama.
        // Setiap pengajuan lama memiliki satu buku.
        // Jumlah diasumsikan satu eksemplar.

        DB::table('pengajuan_peminjaman')
            ->whereNotNull('buku_id')
            ->chunkById(
                100,
                function ($pengajuan) {
                    $data = [];

                    foreach ($pengajuan as $item) {
                        $data[] = [
                            'pengajuan_id' => $item->pengajuan_id,
                            'buku_id' => $item->buku_id,
                            'jumlah' => 1,
                        ];
                    }

                    if (!empty($data)) {
                        DB::table('detail_pengajuan')
                            ->insert($data);
                    }
                },
                'pengajuan_id'
            );
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan');
    }
};
