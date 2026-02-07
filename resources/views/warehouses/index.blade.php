@extends('layouts.app')

@section('title', 'إدارة المستودعات - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة المستودعات</h1>
            <p class="text-sm text-slate-400 mt-1">إدارة مواقع تخزين البضائع</p>
        </div>
        <a href="{{ route('warehouses.create') }}"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            إضافة مستودع جديد
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي المستودعات</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $warehouses->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">المستودعات النشطة</p>
            <p class="text-2xl font-bold text-green-400 mt-1">{{ $warehouses->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي المخزون</p>
            <p class="text-2xl font-bold text-primary mt-1">0</p>
        </div>
    </div>

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($warehouses as $warehouse)
            <div class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary/50 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">warehouse</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('warehouses.edit', $warehouse) }}"
                            class="p-2 hover:bg-slate-700 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-slate-400 text-sm">edit</span>
                        </a>
                        <a href="{{ route('warehouses.show', $warehouse) }}"
                            class="p-2 hover:bg-slate-700 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-slate-400 text-sm">visibility</span>
                        </a>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">{{ $warehouse->name }}</h3>
                <p class="text-sm text-slate-400 mb-4">{{ $warehouse->location ?: 'لا يوجد موقع محدد' }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-border-dark">
                    <span class="text-xs {{ $warehouse->is_active ? 'text-green-400' : 'text-slate-500' }}">
                        {{ $warehouse->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                    <span class="text-sm font-bold text-white">0 منتج</span>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-card-dark border border-border-dark rounded-2xl p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-slate-600 mb-4">warehouse</span>
                <p class="text-slate-500">لا يوجد مستودعات بعد</p>
                <a href="{{ route('warehouses.create') }}" class="inline-block mt-4 text-primary hover:underline">إضافة مستودع
                    جديد</a>
            </div>
        @endforelse
    </div>
@endsection