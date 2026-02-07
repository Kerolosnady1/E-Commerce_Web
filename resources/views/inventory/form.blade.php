@extends('layouts.app')

@section('title', 'تحديث المخزون - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-2">
                <a class="hover:text-primary transition-colors" href="{{ route('inventory') }}">المخزون</a>
                <span class="material-symbols-outlined text-[14px]">chevron_left</span>
                <span class="text-white">تحديث الكمية</span>
            </nav>
            <h1 class="text-3xl font-black text-white tracking-tight">تحديث المخزون</h1>
            <p class="text-slate-400 mt-1">تعديل كميات المنتج: <span
                    class="text-primary font-bold">{{ $inventory->product->name }}</span></p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ route('inventory.update', $inventory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الكمية الحالية في المخزن</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $inventory->quantity) }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                            required min="0">
                        @error('quantity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">حد إعادة الطلب (Reorder Level)</label>
                        <input type="number" name="reorder_level"
                            value="{{ old('reorder_level', $inventory->reorder_level) }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                            required min="0">
                        @error('reorder_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-slate-500 text-xs mt-2">سيظهر تنبيه عندما تصل الكمية إلى هذا الحد أو أقل.</p>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit"
                            class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            تحديث البيانات
                        </button>
                        <a href="{{ route('inventory') }}"
                            class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection