@extends('layouts.app')

@section('title', 'إدارة المخزون - نظام ERP')

@section('content')
    <!-- Breadcrumbs & Heading -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-2">
            <a class="hover:text-primary transition-colors" href="{{ route('inventory') }}">المخزون</a>
            <span class="material-symbols-outlined text-[14px]">chevron_left</span>
            <span class="text-white">قائمة المنتجات</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">قائمة المنتجات</h1>
                <p class="text-slate-400 text-sm mt-1">إدارة وتتبع مستويات المخزون والأسعار عبر جميع الفروع.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex bg-card-dark rounded-lg p-1 border border-border-dark">
                    <a class="px-4 py-2 text-sm font-bold text-slate-300 hover:bg-slate-800 rounded-md transition-colors" href="{{ route('inventory.export.csv') }}">تصدير CSV</a>
                    <button type="button" onclick="document.getElementById('importCsvModal').classList.remove('hidden')" class="px-4 py-2 text-sm font-bold text-slate-400 hover:bg-slate-800 rounded-md transition-colors">استيراد</button>
                </div>
                <a class="bg-primary text-white font-bold py-2.5 px-6 rounded-lg text-sm flex items-center gap-2 shadow-sm hover:shadow-md transition-all" href="{{ route('products.create') }}">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    إضافة منتج
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-card-dark rounded-xl p-4 border border-border-dark shadow-sm mb-8">
        <form class="flex flex-wrap items-center gap-4" action="{{ route('inventory') }}" method="GET">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">الفئة</label>
                <select class="w-full bg-surface-dark border-border-dark rounded-lg text-sm text-white focus:ring-primary/50 focus:border-primary" name="category">
                    <option value="">جميع الفئات</option>
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">حالة المخزون</label>
                <select class="w-full bg-surface-dark border-border-dark rounded-lg text-sm text-white focus:ring-primary/50 focus:border-primary" name="stock_status">
                    <option value="">كل الحالات</option>
                    <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>متوفر</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>مخزون منخفض</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>نفذت الكمية</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">البحث</label>
                <input class="w-full bg-surface-dark border-border-dark rounded-lg text-sm text-white focus:ring-primary/50 focus:border-primary" 
                       name="search" value="{{ request('search') }}" placeholder="ابحث عن اسم أو SKU..." type="text"/>
            </div>
            <div class="pt-5 flex items-center gap-2">
                <button class="h-10 px-4 bg-primary text-white rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-primary/90 transition-colors" type="submit">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    تطبيق الفلاتر
                </button>
                <a class="h-10 px-4 bg-slate-800 text-slate-300 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-slate-700 transition-colors" href="{{ route('inventory') }}">
                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table Area -->
    <div class="bg-card-dark rounded-xl border border-border-dark shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-slate-900/30 border-b border-border-dark">
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">المنتج</th>
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">SKU</th>
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">الفئة</th>
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">الكمية</th>
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">السعر</th>
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">الحالة</th>
                        <th class="px-6 py-4 text-[13px] font-bold text-slate-400">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark">
                    @forelse($items as $item)
                        <tr class="hover:bg-primary/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg bg-surface-dark flex items-center justify-center border border-border-dark">
                                        <span class="material-symbols-outlined text-slate-500">image</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $item->product->name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ Str::limit($item->product->description_ar, 40) ?: 'بدون ملاحظات' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-300">{{ $item->product->sku }}</td>
                            <td class="px-6 py-4 text-sm text-slate-400">{{ $item->product->category->name }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-white">{{ $item->quantity }} وحدة</td>
                            <td class="px-6 py-4 text-sm font-bold text-white">{{ number_format($item->product->price, 2) }} ر.س</td>
                            <td class="px-6 py-4">
                                @if($item->quantity == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/10 text-red-500 border border-red-500/20">نفدت الكمية</span>
                                @elseif($item->quantity <= $item->reorder_level)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20">مخزون منخفض</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-500/10 text-green-500 border border-green-500/20">متوفر</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a class="p-1.5 hover:bg-primary/10 text-primary rounded-md transition-colors" href="{{ route('products.edit', $item->product) }}">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <a class="p-1.5 hover:bg-slate-700 text-slate-400 rounded-md transition-colors" href="{{ route('inventory.form', $item) }}">
                                        <span class="material-symbols-outlined text-[18px]">tune</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-12 text-center text-slate-500" colspan="7">لا توجد منتجات مطابقة لنتائج البحث.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($items->hasPages())
            <div class="px-6 py-4 bg-slate-900/30 border-t border-border-dark">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Footer Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-card-dark rounded-xl p-5 border border-border-dark shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                <span class="material-symbols-outlined text-[28px]">inventory</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">إجمالي المنتجات</p>
                <h4 class="text-2xl font-black text-white">{{ $stats['total_products'] }}</h4>
            </div>
        </div>
        <div class="bg-card-dark rounded-xl p-5 border border-border-dark shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-lg bg-red-500/10 flex items-center justify-center text-red-500">
                <span class="material-symbols-outlined text-[28px]">trending_down</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">منتجات منخفضة المخزون</p>
                <h4 class="text-2xl font-black text-white">{{ $stats['low_stock'] }}</h4>
            </div>
        </div>
        <div class="bg-card-dark rounded-xl p-5 border border-border-dark shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined text-[28px]">payments</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">إجمالي المخزون</p>
                <h4 class="text-2xl font-black text-white">{{ number_format($stats['total_stock']) }}</h4>
            </div>
        </div>
    </div>

    <!-- CSV Import Modal -->
    <div id="importCsvModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-surface-dark border border-border-dark rounded-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-white mb-4">استيراد من CSV</h3>
            <form action="{{ route('inventory.import.csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-slate-400 text-sm mb-2">اختر ملف CSV</label>
                    <input type="file" name="file" accept=".csv,.txt" required
                        class="w-full bg-border-dark border border-border-dark rounded-lg px-4 py-3 text-white">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90">
                        استيراد
                    </button>
                    <button type="button" onclick="document.getElementById('importCsvModal').classList.add('hidden')"
                        class="flex-1 bg-slate-700 text-white font-bold py-3 rounded-lg hover:bg-slate-600">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
