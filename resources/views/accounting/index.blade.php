@extends('layouts.app')

@section('title', 'المحاسبة المالية - نظام ERP')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">المحاسبة المالية</h1>
            <p class="text-slate-400 mt-1">إدارة الحسابات والقيود المحاسبية</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('accounting.coa') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">account_tree</span>
                دليل الحسابات
            </a>
            <a href="{{ route('accounting.journal') }}"
                class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">book</span>
                دفتر القيود
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500">payments</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">إجمالي الأصول</p>
                    <p class="text-lg font-bold text-white">{{ number_format($stats['total_assets'], 2) }} ر.س</p>
                </div>
            </div>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500">account_balance_wallet</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">إجمالي الالتزامات</p>
                    <p class="text-lg font-bold text-white">{{ number_format($stats['total_liabilities'], 2) }} ر.س</p>
                </div>
            </div>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500">groups</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">حقوق الملكية</p>
                    <p class="text-lg font-bold text-white">{{ number_format($stats['total_equity'], 2) }} ر.س</p>
                </div>
            </div>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-500">receipt_long</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">صافي الدخل (تقريبي)</p>
                    <p class="text-lg font-bold text-green-400">
                        {{ number_format($stats['total_revenue'] - $stats['total_expenses'], 2) }} ر.س</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Trend -->
        <div class="bg-card-dark border border-border-dark rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-6">نمو الإيرادات الشهري</h3>
            <div class="space-y-4">
                @foreach($revenueByMonth as $revenue)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-400">{{ $revenue->month }}</span>
                            <span class="text-white font-bold">{{ number_format($revenue->revenue, 2) }} ر.س</span>
                        </div>
                        <div class="w-full bg-slate-700 h-2 rounded-full overflow-hidden">
                            @php
                                $maxRevenue = $revenueByMonth->max('revenue') ?: 1;
                                $percentage = ($revenue->revenue / $maxRevenue) * 100;
                            @endphp
                            <div class="bg-primary h-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- System Alerts -->
        <div class="bg-card-dark border border-border-dark rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-6">تنبيهات محاسبية</h3>
            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-amber-500/5 border border-amber-500/20 rounded-xl">
                    <span class="material-symbols-outlined text-amber-500 mt-1">warning</span>
                    <div>
                        <p class="text-white font-bold text-sm">فواتير معلقة</p>
                        <p class="text-slate-400 text-xs mt-1">يوجد مبالغ بقيمة
                            {{ number_format($stats['accounts_receivable'], 2) }} ر.س لم يتم تحصيلها بعد.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-primary/5 border border-primary/20 rounded-xl">
                    <span class="material-symbols-outlined text-primary mt-1">info</span>
                    <div>
                        <p class="text-white font-bold text-sm">جاهزية التقارير</p>
                        <p class="text-slate-400 text-xs mt-1">بإمكانك الآن تصدير ميزان المراجعة والقوائم المالية الأساسية.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection