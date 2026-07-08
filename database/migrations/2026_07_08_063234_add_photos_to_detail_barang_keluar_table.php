<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_barang_keluar', function (Blueprint $table) {
            $table->string('before_photo')->nullable()->after('quantity');
            $table->string('after_photo')->nullable()->after('before_photo');
            $table->enum('item_status', ['pending', 'processed', 'completed'])->default('pending')->after('after_photo');
        });
    }

    public function down(): void
    {
        Schema::table('detail_barang_keluar', function (Blueprint $table) {
            $table->dropColumn(['before_photo', 'after_photo', 'item_status']);
        });
    }
};
