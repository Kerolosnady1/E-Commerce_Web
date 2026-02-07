@extends('layouts.app')

@section('title', $warehouse->name . ' - نظام ERP')

@section('content')
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-4">
            <a href="{{ route('warehouses.index') }}" class="hover:text-primary transition-colors">المستودعات</a>
            <span class="material-symbols-outlined text-[14px]">chevron_left</span>
            <span class="text-white">{{ $warehouse->name }}</span>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $warehouse->name }}</h1>
                <p class="text-sm text-slate-400 mt-1">{{ $warehouse->location ?: 'لا يوجد موقع محدد' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('warehouses.edit', $warehouse) }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    تعديل
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي الأصناف</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $warehouse->inventoryItems->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي الكميات</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ number_format($warehouse->inventoryItems->sum('quantity')) }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">مخزون منخفض</p>
            <p class="text-2xl font-bold text-amber-400 mt-1">{{ $warehouse->inventoryItems->filter(fn($i) => $i->quantity <= $i->reorder_level)->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">الحالة</p>
            <p class="text-lg font-bold mt-1 {{ $warehouse->is_active ? 'text-green-400' : 'text-slate-500' }}">
                {{ $warehouse->is_active ? 'نشط' : 'غير نشط' }}
            </p>
        </div>
    </div>

    <!-- Inventory in Warehouse -->
    <div class="bg-card-dark rounded-xl border border-border-dark overflow-hidden">
        <div class="p-4 border-b border-border-dark">
            <h2 class="text-lg font-bold text-white">المخزون في هذا المستودع</h2>
        </div>
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-sm text-slate-400">المنتج</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الكمية</th>
                    <th class="px-4 py-3 text-sm text-slate-400">حد إعادة الطلب</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouse->inventoryItems as $item)
                <tr class="border-t border-border-dark hover:bg-primary/5 transition-colors">
                    <td class="px-4 py-3 font-semibold text-white">{{ $item->product->name ?? 'منتج محذوف' }}</td>
                    <td class="px-4 py-3 text-sm text-slate-300">{{ $item->quantity }} وحدة</td>
                    <td class="px-4 py-3 text-sm text-slate-400">{{ $item->reorder_level }}</td>
                    <td class="px-4 py-3">
                        @if($item->quantity == 0)
                            <span class="text-xs bg-red-500/10 text-red-400 px-2 py-1 rounded border border-red-500/20">نفدت</span>
                        @elseif($item->quantity <= $item->reorder_level)
                            <span class="text-xs bg-amber-500/10 text-amber-400 px-2 py-1 rounded border border-amber-500/20">منخفض</span>
                        @else
                            <span class="text-xs bg-green-500/10 text-green-400 px-2 py-1 rounded border border-green-500/20">متوفر</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">لا يوجد مخزون في هذا المستودع</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
