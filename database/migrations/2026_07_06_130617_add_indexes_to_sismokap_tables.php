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
        Schema::table('proyek', function (Blueprint $table) {
            $table->index('lokasi_id');
            $table->index('kontraktor_id');
            $table->index('status');
        });

        Schema::table('progress_harian', function (Blueprint $table) {
            $table->index('tanggal_pelaksanaan');
            $table->index(['proyek_id', 'tanggal_pelaksanaan']);
        });

        Schema::table('progress_mingguan', function (Blueprint $table) {
            $table->index(['proyek_id', 'tahun', 'minggu_ke']);
            $table->index(['tahun', 'minggu_ke']);
        });

        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->index('tanggal_upload');
            $table->index(['proyek_id', 'tanggal_upload']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropIndex(['lokasi_id']);
            $table->dropIndex(['kontraktor_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('progress_harian', function (Blueprint $table) {
            $table->dropIndex(['tanggal_pelaksanaan']);
            $table->dropIndex(['proyek_id', 'tanggal_pelaksanaan']);
        });

        Schema::table('progress_mingguan', function (Blueprint $table) {
            $table->dropIndex(['proyek_id', 'tahun', 'minggu_ke']);
            $table->dropIndex(['tahun', 'minggu_ke']);
        });

        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->dropIndex(['tanggal_upload']);
            $table->dropIndex(['proyek_id', 'tanggal_upload']);
        });
    }
};
