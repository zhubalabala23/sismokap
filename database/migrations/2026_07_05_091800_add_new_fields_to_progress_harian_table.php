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
        Schema::table('progress_harian', function (Blueprint $table) {
            $table->renameColumn('tanggal', 'tanggal_pelaksanaan');
            $table->text('uraian_pekerjaan')->nullable();
            $table->string('volume_pekerjaan')->nullable();
            $table->decimal('progres_harian', 5, 2)->default(0.00);
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_harian', function (Blueprint $table) {
            $table->renameColumn('tanggal_pelaksanaan', 'tanggal');
            $table->dropColumn([
                'uraian_pekerjaan',
                'volume_pekerjaan',
                'progres_harian',
                'kendala',
                'solusi'
            ]);
        });
    }
};
