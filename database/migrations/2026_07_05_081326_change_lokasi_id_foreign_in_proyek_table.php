<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropForeign(['lokasi_id']);
        });

        Schema::table('proyek', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_id')->nullable()->change();
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropForeign(['lokasi_id']);
        });

        Schema::table('proyek', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_id')->nullable(false)->change();
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('restrict');
        });
    }
};
