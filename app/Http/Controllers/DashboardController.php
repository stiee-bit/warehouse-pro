<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        
        // Ambil data riwayat transaksi, urutkan dari yang paling baru, batasi 10 terakhir
        $transactions = StockTransaction::with('product')->latest()->take(10)->get();

        return view('dashboard', compact('products', 'transactions'));
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);

            if ($request->type === 'out' && $product->stock < $request->quantity) {
                return back()->with('error', 'Gagal! Stok barang tidak mencukupi.');
            }

            StockTransaction::create([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ]);

            if ($request->type === 'in') {
                $product->stock += $request->quantity;
            } else {
                $product->stock -= $request->quantity;
            }
            $product->save();

            DB::commit();

            return back()->with('success', 'Transaksi berhasil dicatat dan stok diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan pada server.');
        }
    }
}