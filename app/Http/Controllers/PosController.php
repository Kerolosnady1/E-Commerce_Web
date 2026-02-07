<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleInvoice;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * POS system index page
     */
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();

        return view('pos.index', compact('products', 'customers'));
    }

    /**
     * Search products for POS
     */
    public function searchProducts(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->take(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Cancel order (clear cart)
     */
    public function cancelOrder(Request $request)
    {
        // In a real scenario, this would clear session cart data
        session()->forget('pos_cart');

        return response()->json(['success' => true, 'message' => 'تم إلغاء الطلب بنجاح']);
    }

    /**
     * Submit POS order
     */
    public function submitOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number
            $lastInvoice = SaleInvoice::latest()->first();
            $nextNumber = $lastInvoice ? intval(substr($lastInvoice->number, 4)) + 1 : 1;
            $invoiceNumber = 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            $total = 0;
            $invoice = SaleInvoice::create([
                'number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'],
                'issued_date' => now(),
                'status' => 'paid',
                'total' => 0,
            ]);

            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                SaleItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $invoice->update(['total' => $total]);

            DB::commit();
            session()->forget('pos_cart');

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id,
                'message' => 'تمت العملية بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()], 500);
        }
    }
}
