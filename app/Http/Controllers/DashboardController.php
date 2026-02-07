<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SaleInvoice;
use App\Models\Product;
use App\Models\Notification;
use App\Models\CompanySettings;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard with real-time statistics and analytics
     */
    public function index()
    {
        // Get current month stats
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Sales statistics
        $totalSales = SaleInvoice::sum('total');
        $monthlySales = SaleInvoice::where('created_at', '>=', $currentMonth)->sum('total');
        $lastMonthSales = SaleInvoice::whereBetween('created_at', [$lastMonth, $currentMonth])->sum('total');

        // Calculate change percentage
        $salesChange = $lastMonthSales > 0
            ? round((($monthlySales - $lastMonthSales) / $lastMonthSales) * 100, 1)
            : 0;

        // Customer stats
        $totalCustomers = Customer::count();
        $newCustomers = Customer::where('created_at', '>=', $currentMonth)->count();

        // Product and inventory stats
        $totalProducts = Product::count();
        $lowStockItems = InventoryItem::whereRaw('quantity <= reorder_level')->count();

        // Invoice stats by status
        $invoiceStats = SaleInvoice::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $pendingInvoices = $invoiceStats['pending'] ?? 0;
        $paidInvoices = $invoiceStats['paid'] ?? 0;
        $overdueInvoices = $invoiceStats['overdue'] ?? 0;

        // Recent invoices
        $recentInvoices = SaleInvoice::with('customer')
            ->latest()
            ->take(10)
            ->get();

        // Recent notifications
        $notifications = Notification::where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        // Alerts
        $alerts = [];
        if ($lowStockItems > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "يوجد {$lowStockItems} منتج بمخزون منخفض",
                'icon' => 'inventory_2'
            ];
        }
        if ($overdueInvoices > 0) {
            $alerts[] = [
                'type' => 'error',
                'message' => "يوجد {$overdueInvoices} فاتورة متأخرة",
                'icon' => 'receipt_long'
            ];
        }

        return view('dashboard', compact(
            'totalSales',
            'monthlySales',
            'salesChange',
            'totalCustomers',
            'newCustomers',
            'totalProducts',
            'lowStockItems',
            'pendingInvoices',
            'paidInvoices',
            'overdueInvoices',
            'recentInvoices',
            'notifications',
            'alerts'
        ));
    }

    /**
     * API summary for AJAX calls
     */
    public function apiSummary()
    {
        $totalSales = SaleInvoice::sum('total');
        $totalCustomers = Customer::count();
        $pendingInvoices = SaleInvoice::where('status', 'pending')->count();

        return response()->json([
            'total_sales' => $totalSales,
            'total_customers' => $totalCustomers,
            'pending_invoices' => $pendingInvoices,
        ]);
    }

    /**
     * API alerts for dashboard
     */
    public function apiAlerts()
    {
        $lowStockItems = InventoryItem::whereRaw('quantity <= reorder_level')
            ->with('product')
            ->get();

        $overdueInvoices = SaleInvoice::where('status', 'overdue')
            ->with('customer')
            ->get();

        $alerts = [];

        foreach ($lowStockItems as $item) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "مخزون منخفض: {$item->product->name}",
                'quantity' => $item->quantity
            ];
        }

        foreach ($overdueInvoices as $invoice) {
            $alerts[] = [
                'type' => 'error',
                'message' => "فاتورة متأخرة: {$invoice->number}",
                'customer' => $invoice->customer->name
            ];
        }

        return response()->json(['alerts' => $alerts]);
    }

    /**
     * API dashboard data
     */
    public function apiDashboardData()
    {
        // Weekly sales data
        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = SaleInvoice::whereDate('created_at', $date)->sum('total');
            $weeklySales[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'sales' => $sales
            ];
        }

        // Top products
        $topProducts = DB::table('sale_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return response()->json([
            'weekly_sales' => $weeklySales,
            'top_products' => $topProducts
        ]);
    }

    /**
     * Global search functionality
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('dashboard');
        }

        $results = [
            'products' => Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('sku', 'LIKE', "%{$query}%")
                ->take(5)->get(),
            'customers' => Customer::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->take(5)->get(),
            'invoices' => SaleInvoice::where('number', 'LIKE', "%{$query}%")
                ->take(5)->get(),
            'suppliers' => Supplier::where('name', 'LIKE', "%{$query}%")
                ->take(5)->get(),
        ];

        return view('search-results', compact('query', 'results'));
    }
}
