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
        Schema::table('kontraktor', function (Blueprint $table) {
            $table->foreignId('proyek_id')->nullable()->constrained('proyek')->onDelete('set null');
            $table->string('nama_penanggung_jawab')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('no_kontrak')->nullable();
            $table->date('masa_berlaku_kontrak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontraktor', function (Blueprint $table) {
            $table->dropForeign(['proyek_id']);
            $table->dropColumn([
                'proyek_id',
                'nama_penanggung_jawab',
                'no_telp',
                'email',
                'no_kontrak',
                'masa_berlaku_kontrak'
            ]);
        });
    }
};
