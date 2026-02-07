@extends('layouts.app')

@section('title', 'الفاتورة ' . $invoice->number . ' - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-4">
                <a href="{{ route('invoices.index') }}" class="hover:text-primary transition-colors">الفواتير</a>
                <span class="material-symbols-outlined text-[14px]">chevron_left</span>
                <span class="text-white">{{ $invoice->number }}</span>
            </nav>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">فاتورة رقم: {{ $invoice->number }}</h1>
                    <p class="text-sm text-slate-400 mt-1">{{ $invoice->issued_date->format('Y-m-d') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('invoices.edit', $invoice) }}"
                        class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        تعديل
                    </a>
                    <button onclick="window.print()"
                        class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-sm">print</span>
                        طباعة
                    </button>
                </div>
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="bg-card-dark border border-border-dark rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-border-dark flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">نظام ERP</h2>
                    <p class="text-slate-400 text-sm">فاتورة مبيعات</p>
                </div>
                <div class="text-left">
                    <span
                        class="inline-block px-3 py-1 rounded-full text-sm font-bold
                                        {{ $invoice->status == 'paid' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                        {{ $invoice->status == 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : '' }}
                                        {{ $invoice->status == 'overdue' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}">
                        {{ $invoice->status == 'paid' ? 'مدفوعة' : ($invoice->status == 'pending' ? 'معلقة' : ($invoice->status == 'overdue' ? 'متأخرة' : 'ملغاة')) }}
                    </span>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="p-6 border-b border-border-dark grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-400 mb-2">معلومات العميل</h3>
                    <p class="text-white font-bold">{{ $invoice->customer->name ?? 'غير محدد' }}</p>
                    <p class="text-slate-400 text-sm">{{ $invoice->customer->email ?? '' }}</p>
                    <p class="text-slate-400 text-sm">{{ $invoice->customer->phone ?? '' }}</p>
                </div>
                <div class="text-left md:text-right">
                    <h3 class="text-sm font-bold text-slate-400 mb-2">تفاصيل الفاتورة</h3>
                    <p class="text-white"><span class="text-slate-400">الرقم:</span> {{ $invoice->number }}</p>
                    <p class="text-white"><span class="text-slate-400">التاريخ:</span>
                        {{ $invoice->issued_date->format('Y-m-d') }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-6 border-b border-border-dark">
                <h3 class="text-sm font-bold text-slate-400 mb-4">المنتجات</h3>
                <table class="w-full text-right">
                    <thead>
                        <tr class="border-b border-border-dark">
                            <th class="pb-3 text-sm text-slate-400">المنتج</th>
                            <th class="pb-3 text-sm text-slate-400">الكمية</th>
                            <th class="pb-3 text-sm text-slate-400">السعر</th>
                            <th class="pb-3 text-sm text-slate-400">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items ?? [] as $item)
                            <tr class="border-b border-border-dark/50">
                                <td class="py-3 text-white">{{ $item->product->name ?? 'منتج محذوف' }}</td>
                                <td class="py-3 text-slate-300">{{ $item->quantity }}</td>
                                <td class="py-3 text-slate-300">{{ number_format($item->unit_price, 2) }} ر.س</td>
                                <td class="py-3 text-white font-bold">
                                    {{ number_format($item->quantity * $item->unit_price, 2) }} ر.س
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500">لا توجد منتجات في هذه الفاتورة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="p-6 bg-surface-dark">
                <div class="max-w-xs mr-auto space-y-2">
                    <div class="flex justify-between text-slate-400">
                        <span>المجموع الفرعي:</span>
                        <span>{{ number_format($invoice->getNetAmount(), 2) }} ر.س</span>
                    </div>
                    @if($invoice->items->sum('tax_amount') > 0)
                        <div class="flex justify-between text-slate-400">
                            <span>ضريبة القيمة المضافة{{ $invoice->includes_vat ? ' (مشمولة)' : '' }}:</span>
                            <span>{{ number_format($invoice->getVatAmount(), 2) }} ر.س</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-white text-xl font-bold pt-2 border-t border-border-dark">
                        <span>الإجمالي:</span>
                        <span class="text-primary">{{ number_format($invoice->total, 2) }} ر.س</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($invoice->notes)
                <div class="p-6 border-t border-border-dark">
                    <h3 class="text-sm font-bold text-slate-400 mb-2">ملاحظات</h3>
                    <p class="text-slate-300">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection