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
        if (!Schema::hasColumn('users', 'password_plain')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('password_plain')->nullable();
            });
        }

        if (!Schema::hasColumn('personel', 'password')) {
            Schema::table('personel', function (Blueprint $table) {
                $table->string('password')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'password_plain')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('password_plain');
            });
        }

        if (Schema::hasColumn('personel', 'password')) {
            Schema::table('personel', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
    }
};
