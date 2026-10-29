<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

// Endpoint untuk melihat daftar produk & menambah produk baru
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);

// Endpoint untuk mencatat transaksi barang masuk/keluar
Route::post('/transactions', [TransactionController::class, 'store']);

// Route uji coba transaksi cepat via browser
Route::get('/test-out', function () {
    DB::beginTransaction();
    try {
        $product = Product::findOrFail(1); // Ambil produk ID 1 (Laptop Asus)
        
        if ($product->stock < 2) {
            return "Stok tidak cukup!";
        }

        // Catat transaksi keluar 2 unit
        StockTransaction::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 2,
            'notes' => 'Uji coba keluar barang via browser'
        ]);

        // Kurangi stok
        $product->stock -= 2;
        $product->save();

        DB::commit();
        return "Berhasil! Stok Laptop Asus sekarang sisa: " . $product->stock;
    } catch (\Exception $e) {
        DB::rollBack();
        return "Error: " . $e->getMessage();
    }

    });