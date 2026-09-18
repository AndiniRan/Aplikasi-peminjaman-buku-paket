<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_peminjaman', function (Blueprint $table) {
            $table->id('pengajuan_id');
            $table->foreignId('user_id') ->constrained('users') ->cascadeOnUpdate() ->cascadeOnDelete();
            $table->foreignId('buku_id') ->constrained('buku') ->cascadeOnUpdate() ->restrictOnDelete();
            $table->dateTime('tanggal_pengajuan');
            $table->dateTime('tanggal_disiapkan')->nullable();
            $table->dateTime('batas_pengambilan')->nullable();
            $table->dateTime('tanggal_diambil')->nullable();
            $table->enum('status', [
                'menunggu',
                'siap',
                'disetujui',
                'ditolak'
            ])->default('menunggu');
            $table->text('alasan_ditolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_peminjaman');
    }
};
