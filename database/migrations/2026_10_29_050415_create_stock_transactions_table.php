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
        Schema::create('stock_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Produk apa yang keluar/masuk
        $table->enum('type', ['in', 'out']); // Jenis transaksi: 'in' (masuk), 'out' (keluar)
        $table->integer('quantity'); // Jumlah barang
        $table->text('notes')->nullable(); // Keterangan transaksi
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
