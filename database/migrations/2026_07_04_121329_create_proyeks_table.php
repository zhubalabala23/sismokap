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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->string('kode_proyek')->unique();
            $table->string('nama_proyek');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('restrict');
            $table->foreignId('kontraktor_id')->constrained('kontraktor')->onDelete('restrict');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('target_progress', 5, 2)->default(100.00);
            $table->enum('status', ['berjalan', 'selesai', 'terlambat'])->default('berjalan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
