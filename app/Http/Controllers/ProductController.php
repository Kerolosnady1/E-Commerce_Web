<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
            'is_active' => 'boolean',
            'quantity' => 'nullable|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['tax_rate'] = $request->input('tax_rate', 15.00);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        // Create inventory item
        InventoryItem::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity ?? 0,
            'reorder_level' => $request->reorder_level ?? 10,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('inventory');

        return view('products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['tax_rate'] = $request->input('tax_rate', 15.00);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // Check if the product has associated sales
        if ($product->saleItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'لا يمكن حذف هذا المنتج لوجود عمليات بيع مرتبطة به. يمكنك إلغاء تفعيله بدلاً من ذلك.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * API search products
     */
    public function apiSearch(Request $request)
    {
        $search = $request->get('q', '');

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('inventory')
            ->take(10)
            ->get();

        return response()->json($products);
    }
}
