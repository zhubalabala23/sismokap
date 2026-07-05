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
        Schema::table('progress_mingguan', function (Blueprint $table) {
            $table->decimal('progress_sebelumnya', 5, 2)->default(0.00);
            $table->decimal('progress_berjalan', 5, 2)->default(0.00);
            $table->decimal('target_mingguan', 5, 2)->default(0.00);
            $table->decimal('selisih_capaian', 5, 2)->default(0.00);
            $table->text('kendala')->nullable();
            $table->text('rencana_berikutnya')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_mingguan', function (Blueprint $table) {
            $table->dropColumn([
                'progress_sebelumnya',
                'progress_berjalan',
                'target_mingguan',
                'selisih_capaian',
                'kendala',
                'rencana_berikutnya'
            ]);
        });
    }
};
