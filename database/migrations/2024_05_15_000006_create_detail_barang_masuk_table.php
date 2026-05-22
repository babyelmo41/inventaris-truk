<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->default(0); // Harga satuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_masuk');
    }
};
