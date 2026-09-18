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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('judul', 255);
            $table->string('penerbit', 150);
            $table->string('pengarang', 150);
            $table->year('tahun_terbit');
            $table->unsignedInteger('total_stok')->default(0);
            $table->unsignedInteger('stok_tersedia')->default(0);
            $table->string('sampul')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
