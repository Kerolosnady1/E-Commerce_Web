<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Inventory management with stock tracking and advanced filtering
     */
    public function index(Request $request)
    {
        $query = InventoryItem::with('product.category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereRaw('quantity <= reorder_level');
                    break;
                case 'out':
                    $query->where('quantity', 0);
                    break;
                case 'available':
                    $query->whereRaw('quantity > reorder_level');
                    break;
            }
        }

        if ($request->filled('category')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        $items = $query->paginate(20);

        // Stats
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => InventoryItem::sum('quantity'),
            'low_stock' => InventoryItem::whereRaw('quantity <= reorder_level')->count(),
            'out_of_stock' => InventoryItem::where('quantity', 0)->count(),
        ];

        return view('inventory.index', compact('items', 'stats'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $inventory->update($validated);

        return redirect()->back()
            ->with('success', 'تم تحديث المخزون بنجاح');
    }

    public function form(InventoryItem $inventory)
    {
        $inventory->load('product');

        return view('inventory.form', compact('inventory'));
    }

    /**
     * Export inventory to CSV
     */
    public function exportCsv()
    {
        $items = InventoryItem::with('product')->get();

        $filename = 'inventory_export_' . date('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product Name', 'SKU', 'Quantity', 'Reorder Level', 'Last Restocked']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->product->name,
                    $item->product->sku,
                    $item->quantity,
                    $item->reorder_level,
                    $item->last_restocked_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import inventory from CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $sku = $row[1] ?? null;
            if ($sku) {
                $product = Product::where('sku', $sku)->first();
                if ($product) {
                    InventoryItem::updateOrCreate(
                        ['product_id' => $product->id],
                        [
                            'quantity' => $row[2] ?? 0,
                            'reorder_level' => $row[3] ?? 0,
                        ]
                    );
                    $imported++;
                }
            }
        }

        fclose($handle);

        return redirect()->route('inventory')
            ->with('success', "تم استيراد {$imported} عنصر بنجاح");
    }
}
