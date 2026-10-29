<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'type', 'quantity', 'notes'];

    // Relasi: Transaksi milik satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
