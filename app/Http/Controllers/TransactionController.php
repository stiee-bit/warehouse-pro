<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Gunakan Database Transaction untuk menjaga keamanan data agar tidak korup
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);

            // Validasi jika stok keluar melebihi stok yang ada
            if ($request->type === 'out' && $product->stock < $request->quantity) {
                return response()->json([
                    'message' => 'Gagal! Stok barang tidak mencukupi untuk dikeluarkan.'
                ], 422);
            }

            // Catat transaksi
            $transaction = StockTransaction::create([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ]);

            // Update stok otomatis di tabel produk
            if ($request->type === 'in') {
                $product->stock += $request->quantity;
            } else {
                $product->stock -= $request->quantity;
            }
            $product->save();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil dicatat dan stok diperbarui!',
                'data' => $transaction
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
