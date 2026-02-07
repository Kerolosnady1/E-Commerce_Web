<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Customer management with CRUD operations
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('customer_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->latest()->paginate(20);

        $stats = [
            'total' => Customer::count(),
            'individual' => Customer::where('customer_type', 'individual')->count(),
            'company' => Customer::where('customer_type', 'company')->count(),
            'active' => Customer::where('is_active', true)->count(),
        ];

        return view('customers.index', compact('customers', 'stats'));
    }

    public function create()
    {
        return view('customers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'customer_type' => 'required|in:individual,company',
            'balance' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['balance'] = $validated['balance'] ?? 0;

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'تم إنشاء العميل بنجاح');
    }

    public function show(Customer $customer)
    {
        $invoices = $customer->invoices()->latest()->take(10)->get();

        return view('customers.show', compact('customer', 'invoices'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'customer_type' => 'required|in:individual,company',
            'balance' => 'nullable|numeric',
        ]);

        // Handle checkbox - checked = true, unchecked = false
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    public function destroy(Customer $customer)
    {
        // Check if customer has invoices
        if ($customer->invoices()->exists()) {
            return redirect()->route('customers.index')
                ->with('error', 'لا يمكن حذف العميل لوجود فواتير مرتبطة به');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }

    /**
     * API delete customer
     */
    public function apiDelete(Customer $customer)
    {
        if ($customer->invoices()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف العميل لوجود فواتير مرتبطة به'
            ], 400);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العميل بنجاح'
        ]);
    }
}
