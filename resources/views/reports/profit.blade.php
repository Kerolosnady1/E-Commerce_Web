@extends('layouts.app')

@section('title', 'تقرير الأرباح - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">تقرير الأرباح</h1>
        <p class="text-slate-400 mt-1">تقرير الربحية والهوامش</p>
    </div>

    <!-- Date Filter -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-6 mb-6">
        <form action="{{ route('reports.profit') }}" method="GET" class="flex items-end gap-4">
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي الإيرادات</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_revenue'], 2) }} ر.س</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي التكلفة</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_cost'], 2) }} ر.س</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">صافي الربح</p>
            <h3 class="text-3xl font-bold text-green-500 mt-2">{{ number_format($stats['profit'], 2) }} ر.س</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">هامش الربح</p>
            <h3 class="text-3xl font-bold text-primary mt-2">{{ number_format($stats['profit_margin'], 1) }}%</h3>
        </div>
    </div>
@endsection