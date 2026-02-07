@extends('layouts.app')

@section('title', 'أمر شراء مجمع - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">أمر شراء مجمع</h1>
            <p class="text-slate-400 mt-1">إنشاء أمر شراء للمنتجات منخفضة المخزون</p>
        </div>
    </div>

    @php
        $lowStockItems = collect([]);
        if (class_exists('App\Models\InventoryItem')) {
            $lowStockItems = App\Models\InventoryItem::whereRaw('quantity <= reorder_level')->with('product')->get();
        }
    @endphp

    <!-- Low Stock Items -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden mb-6">
        <div class="p-4 border-b border-border-dark">
            <h3 class="font-bold text-white">المنتجات المطلوب شراؤها</h3>
            <p class="text-sm text-slate-400">المنتجات التي وصلت إلى حد إعادة الطلب</p>
        </div>

        <form action="#" method="POST">
            @csrf
            <table class="w-full text-right">
                <thead class="bg-surface-dark">
                    <tr>
                        <th class="px-4 py-3 text-xs text-slate-400 w-12">
                            <input type="checkbox" id="select-all" class="accent-primary">
                        </th>
                        <th class="px-4 py-3 text-xs text-slate-400">المنتج</th>
                        <th class="px-4 py-3 text-xs text-slate-400">الكمية الحالية</th>
                        <th class="px-4 py-3 text-xs text-slate-400">حد إعادة الطلب</th>
                        <th class="px-4 py-3 text-xs text-slate-400">الكمية المطلوبة</th>
                        <th class="px-4 py-3 text-xs text-slate-400">المورد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockItems as $item)
                        <tr class="border-t border-border-dark hover:bg-primary/5">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="items[]" value="{{ $item->id }}"
                                    class="accent-primary item-checkbox">
                            </td>
                            <td class="px-4 py-3 text-white font-medium">{{ $item->product->name ?? 'منتج' }}</td>
                            <td class="px-4 py-3 text-red-400 font-bold">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $item->reorder_level }}</td>
                            <td class="px-4 py-3">
                                <input type="number" name="quantities[{{ $item->id }}]"
                                    value="{{ max(0, ($item->reorder_level * 2) - $item->quantity) }}"
                                    class="w-20 bg-surface-dark border border-border-dark rounded px-2 py-1 text-white text-center">
                            </td>
                            <td class="px-4 py-3">
                                <select name="suppliers[{{ $item->id }}]"
                                    class="bg-surface-dark border border-border-dark rounded px-2 py-1 text-white text-sm">
                                    <option value="">اختر مورد</option>
                                    @foreach(App\Models\Supplier::all() as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-2">inventory</span>
                                <p>لا توجد منتجات منخفضة المخزون</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($lowStockItems->count() > 0)
                <div class="p-4 border-t border-border-dark flex justify-between items-center">
                    <span class="text-slate-400 text-sm">تم تحديد <span id="selected-count">0</span> منتج</span>
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-bold">
                        إنشاء أمر الشراء
                    </button>
                </div>
            @endif
        </form>
    </div>

    <script>
        document.getElementById('select-all')?.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
            updateCount();
        });
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', updateCount);
        });
        function updateCount() {
            const count = document.querySelectorAll('.item-checkbox:checked').length;
            document.getElementById('selected-count').textContent = count;
        }
    </script>
@endsection