<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Pro - Dashboard Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Warehouse Pro</h1>
            <p class="text-gray-600">Sistem Manajemen Inventaris & Stok Barang Real-Time</p>
        </header>

        <!-- Notifikasi -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Form Catat Transaksi -->
            <div class="bg-white shadow-md rounded-lg p-6 md:col-span-1">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Catat Transaksi Stok</h2>
                <form action="{{ route('transaction.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                        <select name="product_id" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} (Stok: {{ $prod->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                        <select name="type" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="in">Barang Masuk (IN)</option>
                            <option value="out">Barang Keluar (OUT)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Quantity)</label>
                        <input type="number" name="quantity" min="1" value="1" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Catatan</label>
                        <input type="text" name="notes" placeholder="Contoh: Restock / Terjual" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition">Simpan Transaksi</button>
                </form>
            </div>

            <!-- Tabel Daftar Produk -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden md:col-span-2">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-700">Daftar Stok Produk</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->product_code }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->category->name ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-blue-600">{{ $product->stock }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Transaksi -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Riwayat Transaksi Terakhir</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $trx->created_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $trx->product->name ?? 'Produk Dihapus' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($trx->type == 'in')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Masuk (IN)</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar (OUT)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $trx->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $trx->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>