<?php

namespace App\Http\Controllers;

use App\Models\SaleInvoice;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PrintTemplate;
use App\Models\CompanySettings;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Sales management with invoice listing, search, and filtering
     */
    public function index(Request $request)
    {
        $query = SaleInvoice::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('issued_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issued_date', '<=', $request->date_to);
        }

        $invoices = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => SaleInvoice::count(),
            'total_amount' => SaleInvoice::sum('total'),
            'paid' => SaleInvoice::where('status', 'paid')->count(),
            'pending' => SaleInvoice::where('status', 'pending')->count(),
            'overdue' => SaleInvoice::where('status', 'overdue')->count(),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->with('inventory')->get();
        $templates = PrintTemplate::where('template_type', 'sales_invoice')->where('is_active', true)->get();

        return view('invoices.form', compact('customers', 'products', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'nullable|string|max:50|unique:sale_invoices,number',
            'customer_id' => 'required|exists:customers,id',
            'issued_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
            'notes' => 'nullable|string',
            'print_template_id' => 'nullable|exists:print_templates,id',
            'includes_vat' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number if not provided
            if (empty($validated['number'])) {
                $lastInvoice = SaleInvoice::orderBy('id', 'desc')->first();
                $nextNumber = $lastInvoice ? intval(substr($lastInvoice->number, 4)) + 1 : 1;
                $invoiceNumber = 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            } else {
                $invoiceNumber = $validated['number'];
            }

            $invoice = SaleInvoice::create([
                'number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'],
                'issued_date' => $validated['issued_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? '',
                'print_template_id' => $validated['print_template_id'],
                'includes_vat' => $request->has('includes_vat'),
                'total' => 0,
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $defaultTax = CompanySettings::first()->default_tax_rate ?? 15.00;
                $taxRate = $product->tax_rate ?? $defaultTax;
                $subtotal = $item['quantity'] * $item['unit_price'];

                $taxAmount = 0;
                if ($invoice->includes_vat) {
                    $taxAmount = $subtotal - ($subtotal / (1 + ($taxRate / 100)));
                } else {
                    $taxAmount = $subtotal * ($taxRate / 100);
                }

                SaleItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            // If not including VAT, the final total is subtotal + sum of taxes
            if (!$invoice->includes_vat) {
                $total = (float) $invoice->getTotal(); // This will sum subtotal + tax
            } else {
                $total = (float) $total;
            }

            $invoice->update(['total' => $total]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    public function show(SaleInvoice $invoice)
    {
        $invoice->load(['customer', 'items.product', 'printTemplate']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(SaleInvoice $invoice)
    {
        $invoice->load(['items.product']);
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->with('inventory')->get();
        $templates = PrintTemplate::where('template_type', 'sales_invoice')->where('is_active', true)->get();

        return view('invoices.form', compact('invoice', 'customers', 'products', 'templates'));
    }

    public function update(Request $request, SaleInvoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issued_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
            'notes' => 'nullable|string',
            'print_template_id' => 'nullable|exists:print_templates,id',
            'includes_vat' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice->update([
                'customer_id' => $validated['customer_id'],
                'issued_date' => $validated['issued_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? '',
                'print_template_id' => $validated['print_template_id'],
                'includes_vat' => $request->has('includes_vat'),
            ]);

            // Sync items (simple approach: delete and recreate)
            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $defaultTax = CompanySettings::first()->default_tax_rate ?? 15.00;
                $taxRate = $product->tax_rate ?? $defaultTax;
                $subtotal = $item['quantity'] * $item['unit_price'];

                $taxAmount = 0;
                if ($invoice->includes_vat) {
                    $taxAmount = $subtotal - ($subtotal / (1 + ($taxRate / 100)));
                } else {
                    $taxAmount = $subtotal * ($taxRate / 100);
                }

                SaleItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal,
                ]);
            }

            // Recalculate and save total
            $invoice->total = $invoice->getTotal();
            $invoice->save();

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم تحديث الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث الفاتورة: ' . $e->getMessage());
        }
    }

    public function destroy(SaleInvoice $invoice)
    {
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    /**
     * API submit invoice
     */
    public function apiSubmit(Request $request)
    {
        // Similar to store but returns JSON
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $lastInvoice = SaleInvoice::latest()->first();
            $nextNumber = $lastInvoice ? intval(substr($lastInvoice->number, 4)) + 1 : 1;
            $invoiceNumber = 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            $invoice = SaleInvoice::create([
                'number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'],
                'issued_date' => now(),
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;
            $defaultTax = CompanySettings::first()->default_tax_rate ?? 15.00;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $taxRate = $product->tax_rate ?? $defaultTax;
                $subtotal = $item['quantity'] * $item['unit_price'];

                $taxAmount = 0;
                if ($invoice->includes_vat) {
                    $taxAmount = $subtotal - ($subtotal / (1 + ($taxRate / 100)));
                } else {
                    $taxAmount = $subtotal * ($taxRate / 100);
                }

                SaleItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            // If not including VAT, the final total is subtotal + sum of taxes
            if (!$invoice->includes_vat) {
                $total = (float) $invoice->getTotal();
            } else {
                $total = (float) $total;
            }

            $invoice->update(['total' => $total]);

            DB::commit();

            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API sales stats
     */
    public function apiStats()
    {
        $stats = [
            'total_sales' => SaleInvoice::sum('total'),
            'invoice_count' => SaleInvoice::count(),
            'paid_count' => SaleInvoice::where('status', 'paid')->count(),
            'pending_amount' => SaleInvoice::where('status', 'pending')->sum('total'),
        ];

        return response()->json($stats);
    }

    /**
     * Account statement
     */
    public function statement(Request $request)
    {
        $fromDate = $request->input('from', now()->startOfYear()->format('Y-m-d'));
        $toDate = $request->input('to', now()->format('Y-m-d'));
        $customerId = $request->input('customer_id');

        $query = SaleInvoice::whereBetween('issued_date', [$fromDate, $toDate])
            ->with(['customer', 'items']);

        $openingBalance = 0;
        $customer = null;

        if ($customerId) {
            $query->where('customer_id', $customerId);
            $customer = Customer::find($customerId);

            // For a customer statement, the "Opening Balance" is their balance
            $openingBalance = $customer ? $customer->balance : 0;
        } else {
            // If no customer selected, show total receivables from Chart of Accounts
            $openingBalance = Account::where('code', '1200')->value('balance') ?? 0;
        }

        $invoices = $query->orderBy('issued_date', 'desc')->get();

        // Calculate detailed stats
        $stats = [
            'total_amount' => $invoices->sum('total'),
            'collected_amount' => $invoices->where('status', 'paid')->sum('total'),
            'outstanding_amount' => $invoices->whereIn('status', ['pending', 'overdue'])->sum('total'),
            'overdue_amount' => $invoices->where('status', 'overdue')->sum('total'),
            'invoice_count' => $invoices->count(),
            'avg_value' => $invoices->avg('total') ?? 0,
        ];

        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return view('invoices.statement', compact('invoices', 'stats', 'fromDate', 'toDate', 'customers', 'customerId'));
    }
}
