@extends('layouts.app')

@section('title', 'الفواتير - نظام ERP')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة الفواتير</h1>
            <p class="text-slate-400 mt-1">عرض وإدارة جميع فواتير المبيعات</p>
        </div>
        <a href="{{ route('invoices.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors">
            <span class="material-icons text-sm">add</span>
            فاتورة جديدة
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">إجمالي الفواتير</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">إجمالي المبلغ</p>
            <p class="text-xl font-bold text-primary mt-1">{{ number_format($stats['total_amount'], 2) }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">مدفوعة</p>
            <p class="text-2xl font-bold text-green-400 mt-1">{{ $stats['paid'] }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">معلقة</p>
            <p class="text-2xl font-bold text-yellow-400 mt-1">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">متأخرة</p>
            <p class="text-2xl font-bold text-red-400 mt-1">{{ $stats['overdue'] }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="بحث برقم الفاتورة أو العميل..."
                    class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
            </div>
            <select name="status"
                class="px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                <option value="">كل الحالات</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white">
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg">
                <span class="material-icons text-sm">search</span>
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-surface-dark">
                <tr class="text-slate-400 text-sm">
                    <th class="text-right py-4 px-6">رقم الفاتورة</th>
                    <th class="text-right py-4 px-6">العميل</th>
                    <th class="text-right py-4 px-6">التاريخ</th>
                    <th class="text-right py-4 px-6">المبلغ</th>
                    <th class="text-right py-4 px-6">الحالة</th>
                    <th class="text-right py-4 px-6">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr class="border-t border-border-dark hover:bg-surface-dark/50">
                        <td class="py-4 px-6">
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="text-primary hover:underline">{{ $invoice->number }}</a>
                        </td>
                        <td class="py-4 px-6 text-white">{{ $invoice->customer->name }}</td>
                        <td class="py-4 px-6 text-slate-400">{{ $invoice->issued_date->format('Y/m/d') }}</td>
                        <td class="py-4 px-6 text-white font-semibold">{{ number_format($invoice->total, 2) }} ر.س</td>
                        <td class="py-4 px-6">
                            @if($invoice->status == 'paid')
                                <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400">مدفوعة</span>
                            @elseif($invoice->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400">معلقة</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-400">متأخرة</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('invoices.show', $invoice) }}" class="p-1 text-slate-400 hover:text-primary">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="p-1 text-slate-400 hover:text-blue-400">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                                    onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-1 text-slate-400 hover:text-red-400"><span
                                            class="material-icons text-sm">delete</span></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">لا توجد فواتير</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $invoices->links() }}</div>
@endsection