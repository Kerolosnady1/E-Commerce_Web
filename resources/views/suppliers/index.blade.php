@extends('layouts.app')

@section('title', 'إدارة الموردين - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة الموردين</h1>
            <p class="text-sm text-slate-400 mt-1">إدارة بيانات الموردين وربطها بطلبات الشراء</p>
        </div>
        <a href="{{ route('suppliers.create') }}"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            إضافة مورد جديد
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي الموردين</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">الموردين النشطين</p>
            <p class="text-2xl font-bold text-green-400 mt-1">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">غير نشط</p>
            <p class="text-2xl font-bold text-slate-400 mt-1">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-card-dark rounded-xl border border-border-dark p-4 mb-6">
        <form class="flex flex-wrap items-end gap-4" action="{{ route('suppliers.index') }}" method="GET">
            <div class="flex-1 min-w-[220px]">
                <label class="text-xs text-slate-400 mb-2 block">بحث</label>
                <input
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-sm text-white focus:border-primary outline-none"
                    name="search" value="{{ request('search') }}" placeholder="اسم، بريد، هاتف" type="text" />
            </div>
            <div class="min-w-[200px]">
                <label class="text-xs text-slate-400 mb-2 block">الحالة</label>
                <select
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-sm text-white focus:border-primary outline-none"
                    name="status">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm transition-colors"
                    type="submit">تطبيق</button>
                <a class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm transition-colors"
                    href="{{ route('suppliers.index') }}">إعادة تعيين</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-card-dark rounded-xl border border-border-dark overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-sm text-slate-400">الاسم</th>
                    <th class="px-4 py-3 text-sm text-slate-400">البريد</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الهاتف</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الحالة</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr class="border-t border-border-dark hover:bg-primary/5 transition-colors">
                        <td class="px-4 py-3 font-semibold text-white">{{ $supplier->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400">{{ $supplier->email ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400">{{ $supplier->phone ?: '—' }}</td>
                        <td class="px-4 py-3">
                            @if($supplier->is_active)
                                <span
                                    class="text-xs bg-green-500/10 text-green-400 px-2 py-1 rounded border border-green-500/20">نشط</span>
                            @else
                                <span
                                    class="text-xs bg-slate-500/10 text-slate-400 px-2 py-1 rounded border border-slate-500/20">غير
                                    نشط</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="text-primary hover:underline">تعديل</a>
                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                    onsubmit="return confirm('حذف المورد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">لا يوجد موردين بعد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
        <div class="mt-6">
            {{ $suppliers->links() }}
        </div>
    @endif
@endsection