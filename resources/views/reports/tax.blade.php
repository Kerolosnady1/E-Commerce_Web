@extends('layouts.app')

@section('title', 'التقرير الضريبي - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">التقرير الضريبي</h1>
            <p class="text-slate-400 mt-1">تقرير ضريبة القيمة المضافة</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()"
                class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-sm">print</span>
                طباعة التقرير
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-4 mb-6">
        <form action="{{ route('reports.tax') }}" class="flex flex-wrap items-end gap-4" method="GET">
            <div class="min-w-[150px]">
                <label class="text-xs text-slate-400 mb-2 block">من تاريخ</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-white focus:border-primary outline-none">
            </div>
            <div class="min-w-[150px]">
                <label class="text-xs text-slate-400 mb-2 block">إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-white focus:border-primary outline-none">
            </div>
            <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg">
                عرض التقرير
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">إجمالي المبيعات</p>
            <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_sales'], 2) }} ر.س</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">المبلغ الخاضع للضريبة</p>
            <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['taxable_amount'], 2) }} ر.س</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">ضريبة القيمة المضافة (15%)</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ number_format($stats['total_tax'], 2) }} ر.س</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">عدد الفواتير</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $stats['invoice_count'] }}</p>
        </div>
    </div>

    <!-- Tax Details Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border-dark">
            <h3 class="font-bold text-white">تفاصيل الفواتير الضريبية</h3>
        </div>
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-xs text-slate-400">رقم الفاتورة</th>
                    <th class="px-4 py-3 text-xs text-slate-400">التاريخ</th>
                    <th class="px-4 py-3 text-xs text-slate-400">العميل</th>
                    <th class="px-4 py-3 text-xs text-slate-400">المبلغ قبل الضريبة</th>
                    <th class="px-4 py-3 text-xs text-slate-400">الضريبة</th>
                    <th class="px-4 py-3 text-xs text-slate-400">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr class="border-t border-border-dark hover:bg-primary/5">
                        <td class="px-4 py-3 text-white font-medium">{{ $invoice->number }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $invoice->issued_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $invoice->customer->name ?? 'عميل نقدي' }}</td>
                        <td class="px-4 py-3 text-white">
                            @php
                                $invTax = $invoice->items->sum('tax_amount');
                            @endphp
                            {{ number_format($invoice->total - $invTax, 2) }}
                        </td>
                        <td class="px-4 py-3 text-primary font-bold">{{ number_format($invTax, 2) }}</td>
                        <td class="px-4 py-3 text-white font-bold">{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                            لا توجد فواتير في هذه الفترة
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($invoices->count() > 0)
                <tfoot class="bg-surface-dark font-bold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-white">الإجمالي</td>
                        <td class="px-4 py-3 text-white">{{ number_format($stats['taxable_amount'], 2) }}</td>
                        <td class="px-4 py-3 text-primary">{{ number_format($stats['total_tax'], 2) }}</td>
                        <td class="px-4 py-3 text-white">{{ number_format($stats['total_sales'], 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
@endsection