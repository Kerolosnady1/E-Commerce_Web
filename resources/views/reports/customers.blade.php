@extends('layouts.app')

@section('title', 'تقرير العملاء - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">تقرير العملاء</h1>
        <p class="text-slate-400 mt-1">تقرير شامل عن أداء العملاء والمبيعات</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي العملاء</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['total_customers'] }}</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">عملاء نشطون</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['active_customers'] }}</h3>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <p class="text-slate-400 text-sm">إجمالي الإيرادات</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_revenue'], 2) }} ر.س</h3>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-6 py-4 text-slate-400 font-bold">اسم العميل</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">عدد الفواتير</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">إجمالي المشتريات</th>
                    <th class="px-6 py-4 text-slate-400 font-bold">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark">
                @forelse($customers as $customer)
                    <tr class="hover:bg-primary/5">
                        <td class="px-6 py-4 text-white font-medium">{{ $customer->name }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $customer->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-white">{{ $customer->invoices_count }}</td>
                        <td class="px-6 py-4 text-white font-bold">{{ number_format($customer->invoices->sum('total'), 2) }} ر.س
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold {{ $customer->is_active ? 'bg-green-500/10 text-green-500' : 'bg-slate-500/10 text-slate-500' }}">
                                {{ $customer->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">لا يوجد عملاء</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection