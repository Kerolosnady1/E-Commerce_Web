@extends('layouts.app')

@section('title', 'لوحة التحكم الاستراتيجية - نظام ERP')

@section('content')
    <!-- Dashboard Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-1">لوحة التحكم الاستراتيجية</h1>
        <p class="text-slate-400">مرحباً بك، إليك آخر تحديثات نظامك اليوم</p>
    </div>

    <!-- Quick Actions -->
    <section class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
        <a class="flex flex-col items-center justify-center p-4 bg-card-dark border border-border-dark rounded-2xl hover:border-primary hover:shadow-lg transition-all group"
            href="{{ route('invoices.create') }}">
            <div
                class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined">add_shopping_cart</span>
            </div>
            <span class="text-sm font-bold text-slate-300">بيع جديد</span>
        </a>
        <a class="flex flex-col items-center justify-center p-4 bg-card-dark border border-border-dark rounded-2xl hover:border-green-500 hover:shadow-lg transition-all group"
            href="{{ route('customers.create') }}">
            <div
                class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center mb-3 group-hover:bg-green-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined">person_add</span>
            </div>
            <span class="text-sm font-bold text-slate-300">عميل جديد</span>
        </a>
        <a class="flex flex-col items-center justify-center p-4 bg-card-dark border border-border-dark rounded-2xl hover:border-primary hover:shadow-lg transition-all group"
            href="{{ route('inventory') }}">
            <div
                class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined">inventory</span>
            </div>
            <span class="text-sm font-bold text-slate-300">إدارة المخزون</span>
        </a>
        <a class="flex flex-col items-center justify-center p-4 bg-card-dark border border-border-dark rounded-2xl hover:border-blue-500 hover:shadow-lg transition-all group"
            href="{{ route('reports.tax') }}">
            <div
                class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center mb-3 group-hover:bg-blue-500 group-hover:text-white transition-all">
                <span class="material-symbols-outlined">description</span>
            </div>
            <span class="text-sm font-bold text-slate-300">تقرير ضريبي</span>
        </a>
        <a class="hidden lg:flex flex-col items-center justify-center p-4 bg-card-dark border border-border-dark rounded-2xl hover:border-slate-400 hover:shadow-lg transition-all group"
            href="{{ route('settings.general') }}">
            <div
                class="w-12 h-12 rounded-xl bg-slate-500/10 text-slate-400 flex items-center justify-center mb-3 group-hover:bg-slate-700 group-hover:text-white transition-all">
                <span class="material-symbols-outlined">settings_suggest</span>
            </div>
            <span class="text-sm font-bold text-slate-300">تخصيص</span>
        </a>
    </section>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-primary rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/20">
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                        <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
                    </div>
                    <div class="flex items-center bg-green-400/20 text-green-300 px-3 py-1 rounded-full text-xs font-bold">
                        <span class="material-symbols-outlined text-sm ml-1">trending_up</span> {{ $salesChange }}%
                    </div>
                </div>
                <p class="text-blue-100 text-sm font-medium opacity-90">إجمالي الإيرادات (الشهر الحالي)</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h2 class="text-4xl font-bold">{{ number_format($monthlySales) }}</h2>
                    <span class="text-lg opacity-80">ر.س</span>
                </div>
                <div class="mt-8 pt-6 border-t border-white/10 flex items-center gap-4">
                    <div class="flex-1 h-1.5 bg-white/20 rounded-full overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 75%"></div>
                    </div>
                    <span class="text-xs font-medium">75% من المستهدف</span>
                </div>
            </div>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Orders Card -->
        <div class="bg-card-dark rounded-3xl p-8 border border-border-dark shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div class="p-3 bg-slate-800 text-slate-400 rounded-2xl border border-border-dark">
                    <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                </div>
                <div class="flex items-center bg-slate-800 text-slate-400 px-3 py-1 rounded-full text-xs font-bold">
                    <span class="material-symbols-outlined text-sm ml-1">schedule</span> آخر 30 يوم
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">عدد الطلبات المنفذة</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ count($recentInvoices) }}</h2>
            <div class="mt-8 flex items-center gap-2">
                <div class="flex -space-x-reverse space-x-2">
                    <div
                        class="w-8 h-8 rounded-full border-2 border-slate-800 bg-blue-100 flex items-center justify-center text-[10px] font-bold text-primary">
                        A</div>
                    <div
                        class="w-8 h-8 rounded-full border-2 border-slate-800 bg-green-100 flex items-center justify-center text-[10px] font-bold text-green-600">
                        B</div>
                    <div
                        class="w-8 h-8 rounded-full border-2 border-slate-800 bg-amber-100 flex items-center justify-center text-[10px] font-bold text-amber-600">
                        C</div>
                </div>
                <span class="text-xs text-slate-400 mr-2">+{{ $newCustomers }} عميل جديد هذا الشهر</span>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="bg-card-dark rounded-3xl p-8 border border-red-500/20 shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div class="p-3 bg-red-500/10 text-red-500 rounded-2xl border border-red-500/20">
                    <span class="material-symbols-outlined text-3xl">warning</span>
                </div>
                <span
                    class="flex items-center text-xs font-bold text-red-500 bg-red-500/10 px-3 py-1 rounded-full animate-pulse">
                    تنبيه حرج
                </span>
            </div>
            <p class="text-slate-400 text-sm font-medium">منتجات منخفضة المخزون</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ $lowStockItems }}</h2>
            <div class="mt-8">
                <a class="w-full py-2.5 bg-red-500/10 text-red-500 rounded-xl text-sm font-bold hover:bg-red-500/20 transition-colors text-center block"
                    href="{{ route('inventory') }}">
                    عرض قائمة النواقص
                </a>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-card-dark rounded-3xl p-8 border border-border-dark shadow-sm">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-xl font-bold text-white">ملخص المبيعات الأسبوعية</h3>
                    <p class="text-sm text-slate-400">تحليل المبيعات للأيام السبعة الماضية</p>
                </div>
                <div class="flex gap-2" id="chart-filters">
                    <button onclick="loadChartData('all')"
                        class="px-4 py-2 text-sm font-bold bg-primary text-white rounded-xl shadow-lg shadow-blue-500/20 filter-btn active">الكل</button>
                    <button onclick="loadChartData('online')"
                        class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-800 rounded-xl hover:bg-slate-700 transition-colors filter-btn">أونلاين</button>
                </div>
            </div>

            <div class="relative h-72 w-full flex items-end gap-4 px-2" id="chart-container">
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-10">
                    <div class="border-t border-slate-400 w-full"></div>
                    <div class="border-t border-slate-400 w-full"></div>
                    <div class="border-t border-slate-400 w-full"></div>
                    <div class="border-t border-slate-400 w-full"></div>
                </div>
                <!-- Dynamic Bars -->
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-card-dark rounded-3xl p-8 border border-border-dark shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-white">آخر العمليات</h3>
                <a class="text-primary text-xs font-bold hover:underline" href="{{ route('invoices.index') }}">عرض الكل</a>
            </div>
            <div class="flex-1 space-y-6 overflow-y-auto max-h-[400px]">
                @foreach($recentInvoices->take(10) as $invoice)
                    <a class="flex items-center justify-between group cursor-pointer"
                        href="{{ route('invoices.show', $invoice) }}">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-slate-800 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">receipt_long</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ $invoice->number }}</p>
                                <p class="text-[11px] text-slate-500">{{ $invoice->created_at->format('Y-m-d') }} •
                                    {{ $invoice->customer->name ?? 'عميل نقدي' }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm font-bold text-white">{{ number_format($invoice->total) }} ر.س</p>
                    </a>
                @endforeach
            </div>
            <a class="mt-8 py-3 w-full bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-all text-center"
                href="{{ route('reports.sales.export') }}">
                تحميل كشف المبيعات (CSV)
            </a>
        </div>
    </div>

    <!-- Inventory Alerts Table -->
    <section class="rounded-3xl border border-border-dark bg-card-dark shadow-sm overflow-hidden mb-8">
        <div class="p-8 border-b border-border-dark flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-white">تنبيهات المخزون المنخفض</h3>
                <p class="text-sm text-slate-400">المنتجات التي وصلت إلى حد إعادة الطلب</p>
            </div>
            <a class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition-all"
                href="{{ route('inventory') }}">
                تحديث المخزون
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">المنتج</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">الكمية الحالية</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-left">الإجراء
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark">
                    @forelse(App\Models\InventoryItem::whereRaw('quantity <= reorder_level')->with('product')->take(5)->get() as $item)
                        <tr>
                            <td class="px-8 py-5 text-sm font-bold text-white">{{ $item->product->name }}</td>
                            <td class="px-8 py-5 text-sm font-bold text-red-500">{{ $item->quantity }} قطعة</td>
                            <td class="px-8 py-5 text-left">
                                <a class="text-primary hover:bg-primary/10 p-2 rounded-lg transition-colors inline-flex"
                                    href="{{ route('products.edit', $item->product_id) }}">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-5 text-center text-slate-500">لا توجد نواقص مخزون</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        async function loadChartData(type = 'all') {
            // Update filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-primary', 'text-white', 'shadow-lg');
                btn.classList.add('bg-slate-800', 'text-slate-400');
            });
            event.target.classList.add('bg-primary', 'text-white', 'shadow-lg');
            event.target.classList.remove('bg-slate-800', 'text-slate-400');

            try {
                const response = await fetch('{{ route("api.dashboard") }}');
                const data = await response.json();
                renderChart(data.weekly_sales);
            } catch (error) {
                console.error('Error loading chart data:', error);
            }
        }

        function renderChart(salesData) {
            const container = document.getElementById('chart-container');
            const maxSales = Math.max(...salesData.map(d => d.sales), 100);

            // Clear existing bars except grid lines
            const existingBars = container.querySelectorAll('.bar-item');
            existingBars.forEach(b => b.remove());

            salesData.forEach((day, index) => {
                const height = (day.sales / maxSales) * 100;
                const bar = document.createElement('div');
                bar.className = 'flex-1 flex flex-col items-center group relative bar-item';
                bar.innerHTML = `
                                <div class="w-full rounded-t-xl transition-all group-hover:bg-primary/50 ${index % 2 == 0 ? 'bg-primary' : 'bg-blue-800'}"
                                    style="height: ${Math.max(height, 5)}%">
                                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-slate-900 px-2 py-1 rounded text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        ${day.sales.toLocaleString()} ر.س
                                    </div>
                                </div>
                                <span class="mt-4 text-xs font-medium text-slate-500">${day.day}</span>
                            `;
                container.appendChild(bar);
            });
        }

        // Initial load
        document.addEventListener('DOMContentLoaded', () => {
            loadChartData();
        });
    </script>
@endsection