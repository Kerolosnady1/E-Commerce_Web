@extends('layouts.app')

@section('title', 'تقرير المخزون - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">تقرير المخزون</h1>
        <p class="text-slate-400 mt-1">تقرير شامل لحالة المخزون</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي المنتجات</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['total_products'] }}</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي الكمية</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_stock']) }}</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">مخزون منخفض</p>
            <h3 class="text-3xl font-bold text-amber-500 mt-2">{{ $stats['low_stock'] }}</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">نفدت الكمية</p>
            <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $stats['out_of_stock'] }}</h3>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-6 py-4 text-slate-400 font-bold">المنتج</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">الفئة</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">الكمية</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">مستوى إعادة الطلب</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark">
                @forelse($items as $item)
                    <tr class="hover:bg-primary/5">
                        <td class="px-6 py-4 text-white font-medium">{{ $item->product->name }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $item->product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-white font-bold">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-slate-400">{{ $item->reorder_level }}</td>
                        <td class="px-6 py-4">
                            @if($item->quantity == 0)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500">نفدت الكمية</span>
                            @elseif($item->quantity <= $item->reorder_level)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-500">مخزون
                                    منخفض</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-500">متوفر</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">لا توجد عناصر في المخزون</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection