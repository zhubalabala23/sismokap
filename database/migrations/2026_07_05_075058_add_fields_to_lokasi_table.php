<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->foreignId('proyek_id')->nullable()->constrained('proyek')->onDelete('set null');
            $table->string('kabupaten_kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('keterangan_lokasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropForeign(['proyek_id']);
            $table->dropColumn(['proyek_id', 'kabupaten_kota', 'provinsi', 'latitude', 'longitude', 'keterangan_lokasi']);
        });
    }
};
