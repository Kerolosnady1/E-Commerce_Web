@extends('layouts.app')

@section('title', 'تقرير المبيعات - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">تقرير المبيعات</h1>
        <p class="text-slate-400 mt-1">تقرير شامل لجميع المبيعات والفواتير</p>
    </div>

    <!-- Date Filter -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-6 mb-6">
        <form action="{{ route('reports.sales') }}" method="GET" class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-slate-400 text-sm mb-2">من تاريخ</label>
                <input type="date" name="start_date" value="{{ $startDate ?? date('Y-m-01') }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white">
            </div>
            <div class="flex-1">
                <label class="block text-slate-400 text-sm mb-2">إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ $endDate ?? date('Y-m-t') }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white">
            </div>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold hover:bg-primary/90">
                تطبيق
            </button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي المبيعات</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_revenue'], 2) }} ر.س</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">عدد الفواتير</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['total_invoices'] }}</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">فواتير مدفوعة</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['paid_invoices'] }}</h3>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-6 py-4 text-slate-400 font-bold">رقم الفاتورة</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">العميل</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">التاريخ</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">المبلغ</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark">
                @forelse($sales as $sale)
                    <tr class="hover:bg-primary/5">
                        <td class="px-6 py-4 text-white font-medium">{{ $sale->invoice_number }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $sale->customer->name ?? 'عميل نقدي' }}</td>
                        <td class="px-6 py-4 text-slate-400">{{ $sale->issued_date }}</td>
                        <td class="px-6 py-4 text-white font-bold">{{ number_format($sale->total, 2) }} ر.س</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold {{ $sale->status == 'paid' ? 'bg-green-500/10 text-green-500' : 'bg-amber-500/10 text-amber-500' }}">
                                {{ $sale->status == 'paid' ? 'مدفوعة' : 'معلقة' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">لا توجد مبيعات في هذه الفترة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection