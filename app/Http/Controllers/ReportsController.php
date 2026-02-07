<?php

namespace App\Http\Controllers;

use App\Models\SaleInvoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Reports index page
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Sales report
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $sales = SaleInvoice::whereBetween('issued_date', [$startDate, $endDate])
            ->with('customer')
            ->orderBy('issued_date', 'desc')
            ->get();

        $stats = [
            'total_revenue' => $sales->sum('total'),
            'total_invoices' => $sales->count(),
            'paid_invoices' => $sales->where('status', 'paid')->count(),
            'pending_invoices' => $sales->where('status', 'pending')->count(),
        ];

        return view('reports.sales', compact('sales', 'stats', 'startDate', 'endDate'));
    }

    /**
     * Customer report
     */
    public function customers(Request $request)
    {
        $customers = Customer::withCount('invoices')
            ->with('invoices')
            ->get();

        $stats = [
            'total_customers' => $customers->count(),
            'active_customers' => $customers->where('is_active', true)->count(),
            'total_revenue' => SaleInvoice::sum('total'),
        ];

        return view('reports.customers', compact('customers', 'stats'));
    }

    /**
     * Profit report
     */
    public function profit(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $sales = SaleInvoice::whereBetween('issued_date', [$startDate, $endDate])
            ->with(['items.product'])
            ->get();

        $totalRevenue = $sales->sum('total');
        $totalCost = 0;

        foreach ($sales as $invoice) {
            foreach ($invoice->items as $item) {
                $totalCost += ($item->quantity * ($item->product->cost_price ?? 0));
            }
        }

        $profit = $totalRevenue - $totalCost;

        $stats = [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'profit' => $profit,
            'profit_margin' => $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0,
        ];

        return view('reports.profit', compact('stats', 'startDate', 'endDate'));
    }

    /**
     * Inventory report
     */
    public function inventory(Request $request)
    {
        $items = InventoryItem::with('product.category')
            ->orderBy('quantity', 'asc')
            ->get();

        $stats = [
            'total_products' => Product::count(),
            'total_stock' => $items->sum('quantity'),
            'low_stock' => $items->where('quantity', '<=', 'reorder_level')->count(),
            'out_of_stock' => $items->where('quantity', 0)->count(),
        ];

        return view('reports.inventory', compact('items', 'stats'));
    }

    /**
     * Tax report
     */
    public function tax(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $invoices = SaleInvoice::whereBetween('issued_date', [$startDate, $endDate])
            ->with(['customer', 'items'])
            ->orderBy('issued_date', 'desc')
            ->get();

        $totalSales = $invoices->sum('total');
        $totalTax = $invoices->sum(function ($invoice) {
            return $invoice->items->sum('tax_amount');
        });

        $taxableAmount = $totalSales - $totalTax;

        $stats = [
            'total_sales' => $totalSales,
            'taxable_amount' => $taxableAmount,
            'total_tax' => $totalTax,
            'invoice_count' => $invoices->count(),
        ];

        return view('reports.tax', compact('invoices', 'stats', 'startDate', 'endDate'));
    }
}
