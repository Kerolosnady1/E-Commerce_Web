@extends('layouts.app')

@section('title', 'إدارة التصنيفات - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة التصنيفات</h1>
            <p class="text-sm text-slate-400 mt-1">تنظيم المنتجات حسب الفئات</p>
        </div>
        <a href="{{ route('categories.create') }}"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            إضافة تصنيف جديد
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي التصنيفات</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $categories->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">التصنيفات الرئيسية</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ $categories->whereNull('parent_id')->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">التصنيفات الفرعية</p>
            <p class="text-2xl font-bold text-blue-400 mt-1">{{ $categories->whereNotNull('parent_id')->count() }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-card-dark rounded-xl border border-border-dark overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-sm text-slate-400">التصنيف</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الوصف</th>
                    <th class="px-4 py-3 text-sm text-slate-400">التصنيف الأب</th>
                    <th class="px-4 py-3 text-sm text-slate-400">عدد المنتجات</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t border-border-dark hover:bg-primary/5 transition-colors">
                        <td class="px-4 py-3 font-semibold text-white">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400">{{ Str::limit($category->description, 50) ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400">{{ $category->parent->name ?? 'رئيسي' }}</td>
                        <td class="px-4 py-3 text-sm text-primary font-bold">{{ $category->products_count ?? 0 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="text-primary hover:underline">تعديل</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                    onsubmit="return confirm('حذف التصنيف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">لا يوجد تصنيفات بعد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection