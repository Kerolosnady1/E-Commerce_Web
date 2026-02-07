<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->with('parent')->paginate(20);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('categories.form', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('categories.form', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'لا يمكن حذف هذا التصنيف لوجود منتجات مرتبطة به. قم بنقل المنتجات أو حذفها أولاً.');
        }

        // Check if category has subcategories
        if ($category->children()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'لا يمكن حذف هذا التصنيف لوجود تصنيفات فرعية مرتبطة به.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح');
    }
}
