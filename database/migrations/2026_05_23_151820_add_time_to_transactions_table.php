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
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->time('time')->default('08:00:00')->after('date');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->time('time')->default('08:00:00')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('time');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }
};
