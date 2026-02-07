@extends('layouts.app')

@section('title', 'المنتجات - نظام ERP')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة المنتجات</h1>
            <p class="text-slate-400 mt-1">عرض وإدارة جميع المنتجات</p>
        </div>
        <a href="{{ route('products.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors">
            <span class="material-icons text-sm">add</span>
            منتج جديد
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU..."
                    class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
            </div>
            <select name="category"
                class="px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg">
                <span class="material-icons text-sm">search</span>
            </button>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div
                class="bg-card-dark border border-border-dark rounded-xl overflow-hidden hover:border-primary/50 transition-colors">
                <div class="h-40 bg-surface-dark flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                            class="h-full w-full object-cover">
                    @else
                        <span class="material-icons text-6xl text-slate-600">inventory_2</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold mb-1">{{ $product->name }}</h3>
                    <p class="text-slate-400 text-sm mb-2">{{ $product->sku }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->sale_price)
                                <span class="text-primary font-bold">{{ number_format($product->sale_price, 2) }} ر.س</span>
                                <span
                                    class="text-slate-500 text-sm line-through mr-2">{{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-primary font-bold">{{ number_format($product->price, 2) }} ر.س</span>
                            @endif
                        </div>
                        <span
                            class="text-xs {{ $product->inventory && $product->inventory->quantity > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $product->inventory ? $product->inventory->quantity : 0 }} متوفر
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-border-dark">
                        <a href="{{ route('products.edit', $product) }}"
                            class="flex-1 text-center py-2 bg-surface-dark hover:bg-primary/20 text-slate-300 rounded transition-colors">
                            <span class="material-icons text-sm">edit</span>
                        </a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="flex-1"
                            onsubmit="return confirm('حذف المنتج؟')">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full py-2 bg-surface-dark hover:bg-red-500/20 text-slate-300 rounded transition-colors">
                                <span class="material-icons text-sm">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">لا توجد منتجات</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection