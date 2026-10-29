<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'product_code', 'name', 'stock', 'price'];

    // Relasi: Produk milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Produk punya banyak riwayat transaksi stok
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
