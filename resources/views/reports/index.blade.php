@extends('layouts.app')

@section('title', 'التقارير - نظام ERP')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">التقارير والإحصائيات</h1>
        <p class="text-slate-400 mt-1">استعرض تقارير المبيعات والمخزون والأداء</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('reports.sales') }}"
            class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
            <div
                class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-primary group-hover:text-white text-2xl">trending_up</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">تقرير المبيعات</h3>
            <p class="text-sm text-slate-400">تحليل المبيعات اليومية والشهرية والسنوية</p>
        </a>

        <a href="{{ route('reports.tax') }}"
            class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-blue-500 transition-all group">
            <div
                class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-blue-500 group-hover:text-white text-2xl">receipt_long</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">التقرير الضريبي</h3>
            <p class="text-sm text-slate-400">تقرير ضريبة القيمة المضافة</p>
        </a>

        <a href="{{ route('reports.inventory') }}"
            class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-green-500 transition-all group">
            <div
                class="w-14 h-14 bg-green-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-green-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-green-500 group-hover:text-white text-2xl">inventory_2</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">تقرير المخزون</h3>
            <p class="text-sm text-slate-400">حالة المخزون والمنتجات الأكثر مبيعاً</p>
        </a>

        <a href="{{ route('reports.customers') }}"
            class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-amber-500 transition-all group">
            <div
                class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-amber-500 group-hover:text-white text-2xl">groups</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">تقرير العملاء</h3>
            <p class="text-sm text-slate-400">أفضل العملاء وتحليل السلوك الشرائي</p>
        </a>

        <a href="{{ route('reports.profit') }}"
            class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-purple-500 transition-all group">
            <div
                class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-purple-500 group-hover:text-white text-2xl">monitoring</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">تقرير الأرباح</h3>
            <p class="text-sm text-slate-400">تحليل الربحية والهوامش</p>
        </a>

        <a href="{{ route('invoices.statement') }}"
            class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-slate-500 transition-all group">
            <div
                class="w-14 h-14 bg-slate-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-slate-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-slate-400 group-hover:text-white text-2xl">download</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">كشف الحساب</h3>
            <p class="text-sm text-slate-400">تحميل كشف حساب كامل</p>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">مبيعات اليوم</p>
            <p class="text-2xl font-bold text-white mt-1">
                {{ number_format(App\Models\Invoice::whereDate('created_at', today())->sum('total')) }} ر.س</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">مبيعات الأسبوع</p>
            <p class="text-2xl font-bold text-white mt-1">
                {{ number_format(App\Models\Invoice::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total')) }}
                ر.س</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">مبيعات الشهر</p>
            <p class="text-2xl font-bold text-white mt-1">
                {{ number_format(App\Models\Invoice::whereMonth('created_at', now()->month)->sum('total')) }} ر.س</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <p class="text-sm text-slate-400">عدد الفواتير</p>
            <p class="text-2xl font-bold text-white mt-1">{{ App\Models\Invoice::count() }}</p>
        </div>
    </div>
@endsection