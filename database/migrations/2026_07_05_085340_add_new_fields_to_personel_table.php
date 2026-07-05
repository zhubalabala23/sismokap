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
        Schema::table('personel', function (Blueprint $table) {
            $table->string('nrp_nip')->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('hak_akses')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personel', function (Blueprint $table) {
            $table->dropColumn([
                'nrp_nip',
                'pangkat_golongan',
                'no_hp',
                'email',
                'unit_kerja',
                'hak_akses'
            ]);
        });
    }
};
