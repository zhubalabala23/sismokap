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
            $table->string('kontak')->nullable()->change();
        });
        Schema::table('kontraktor', function (Blueprint $table) {
            $table->string('kontak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personel', function (Blueprint $table) {
            $table->string('kontak')->nullable(false)->change();
        });
        Schema::table('kontraktor', function (Blueprint $table) {
            $table->string('kontak')->nullable(false)->change();
        });
    }
};
