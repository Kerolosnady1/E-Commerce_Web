@extends('layouts.app')

@section('title', 'كشف الحساب - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">كشف الحساب</h1>
            <p class="text-slate-400 mt-1">تحميل كشف حساب كامل للفواتير</p>
        </div>
        <button onclick="window.print()"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">download</span>
            تحميل PDF
        </button>
    </div>

    <!-- Filter & Summary -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-6 mb-6 shadow-sm shadow-blue-900/10">
        <form action="{{ route('invoices.statement') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
            method="GET">
            <div>
                <label class="text-xs text-slate-400 mb-2 block font-semibold">من تاريخ</label>
                <input type="date" name="from" value="{{ $fromDate }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-white focus:border-primary outline-none transition-all">
            </div>
            <div>
                <label class="text-xs text-slate-400 mb-2 block font-semibold">إلى تاريخ</label>
                <input type="date" name="to" value="{{ $toDate }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-white focus:border-primary outline-none transition-all">
            </div>
            <div>
                <label class="text-xs text-slate-400 mb-2 block font-semibold">العميل</label>
                <select name="customer_id"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-white focus:border-primary outline-none transition-all">
                    <option value="">جميع العملاء</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ ($customerId ?? '') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg transition-all font-black text-sm uppercase tracking-wider h-11">
                تطبيق الفلتر
            </button>
        </form>
    </div>

    <!-- Statement Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div
            class="bg-card-dark border border-border-dark rounded-xl p-5 border-r-4 border-r-blue-500 shadow-sm shadow-blue-500/5">
            <p class="text-xs text-slate-500 font-bold mb-1">إجمالي المبيعات</p>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_amount'], 2) }} <span
                    class="text-xs text-slate-400 font-normal">ر.س</span></p>
        </div>
        <div
            class="bg-card-dark border border-border-dark rounded-xl p-5 border-r-4 border-r-green-500 shadow-sm shadow-green-500/5">
            <p class="text-xs text-slate-500 font-bold mb-1">المبالغ المحصلة</p>
            <p class="text-2xl font-black text-green-500">{{ number_format($stats['collected_amount'], 2) }} <span
                    class="text-xs text-slate-400 font-normal">ر.س</span></p>
        </div>
        <div
            class="bg-card-dark border border-border-dark rounded-xl p-5 border-r-4 border-r-amber-500 shadow-sm shadow-amber-500/5">
            <p class="text-xs text-slate-500 font-bold mb-1">تحت التحصيل</p>
            <p class="text-2xl font-black text-amber-500">{{ number_format($stats['outstanding_amount'], 2) }} <span
                    class="text-xs text-slate-400 font-normal">ر.س</span></p>
        </div>
        <div
            class="bg-card-dark border border-border-dark rounded-xl p-5 border-r-4 border-r-red-500 shadow-sm shadow-red-500/5">
            <p class="text-xs text-slate-500 font-bold mb-1">ديون متأخرة</p>
            <p class="text-2xl font-black text-red-500">{{ number_format($stats['overdue_amount'], 2) }} <span
                    class="text-xs text-slate-400 font-normal">ر.س</span></p>
        </div>
        <div
            class="bg-card-dark border border-border-dark rounded-xl p-5 border-r-4 border-r-purple-500 shadow-sm shadow-purple-500/5">
            <p class="text-xs text-slate-500 font-bold mb-1">رصيد العميل</p>
            <p class="text-2xl font-black text-purple-500">{{ number_format($openingBalance ?? 0, 2) }} <span
                    class="text-xs text-slate-400 font-normal">ر.س</span></p>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-xs text-slate-400">رقم الفاتورة</th>
                    <th class="px-4 py-3 text-xs text-slate-400">التاريخ</th>
                    <th class="px-4 py-3 text-xs text-slate-400">العميل</th>
                    <th class="px-4 py-3 text-xs text-slate-400">الحالة</th>
                    <th class="px-4 py-3 text-xs text-slate-400">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr class="border-t border-border-dark hover:bg-primary/5">
                        <td class="px-4 py-3 text-white font-medium">
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="text-primary hover:underline">{{ $invoice->number }}</a>
                        </td>
                        <td class="px-4 py-3 text-slate-400">{{ $invoice->issued_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-white">{{ $invoice->customer->name ?? 'عميل نقدي' }}</td>
                        <td class="px-4 py-3">
                            @if($invoice->status == 'paid')
                                <span
                                    class="text-xs bg-green-500/10 text-green-400 px-2 py-1 rounded border border-green-500/20">مدفوعة</span>
                            @elseif($invoice->status == 'pending')
                                <span
                                    class="text-xs bg-amber-500/10 text-amber-400 px-2 py-1 rounded border border-amber-500/20">معلقة</span>
                            @else
                                <span
                                    class="text-xs bg-slate-500/10 text-slate-400 px-2 py-1 rounded border border-slate-500/20">{{ $invoice->status }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-white font-bold">{{ number_format($invoice->total, 2) }} ر.س</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                            لا توجد فواتير في هذه الفترة
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection