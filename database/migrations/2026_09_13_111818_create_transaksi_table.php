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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->foreignId('user_id') ->constrained('users') ->cascadeOnUpdate() ->cascadeOnDelete();
            $table->foreignId('buku_id') ->constrained('buku') ->cascadeOnUpdate() ->restrictOnDelete();
            $table->unsignedBigInteger('pengajuan_id')->nullable();
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_jatuh_tempo');
            $table->dateTime('tanggal_kembali')->nullable();
            $table->enum('status', [
                'dipinjam',
                'dikembalikan',
                'terlambat',
            ])->default('dipinjam');

            $table->timestamps();

            $table->foreign('pengajuan_id')
                ->references('pengajuan_id')
                ->on('pengajuan_peminjaman')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
