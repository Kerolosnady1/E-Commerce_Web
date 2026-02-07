@extends('layouts.app')

@section('title', '{{ isset($tax) ? "تعديل" : "إضافة" }} ضريبة - نظام ERP')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('settings.taxes') }}" class="text-primary hover:text-blue-300 flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined">arrow_back</span>
                العودة إلى الضرائب
            </a>
            <h1 class="text-3xl font-bold text-white">{{ isset($tax) ? 'تعديل' : 'إضافة' }} ضريبة</h1>
            <p class="text-slate-400 mt-2">املأ البيانات أدناه {{ isset($tax) ? 'لتعديل' : 'لإضافة' }} الضريبة</p>
        </div>

        <!-- Form -->
        <div class="bg-card-dark rounded-2xl shadow-lg p-8 border border-border-dark">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <form method="post" action="{{ isset($tax) ? route('taxes.update', $tax) : route('taxes.store') }}" class="space-y-6">
                @csrf
                @if(isset($tax))
                    @method('PUT')
                @endif
                
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-slate-200 mb-2">
                        <span class="material-symbols-outlined text-sm ml-1 align-middle">label</span>
                        اسم الضريبة *
                    </label>
                    <input type="text" name="name" value="{{ old('name', $tax->name ?? '') }}" 
                           class="w-full bg-surface-dark border border-border-dark text-white px-4 py-3 rounded-xl focus:border-primary outline-none"
                           placeholder="مثال: ضريبة القيمة المضافة" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rate -->
                <div>
                    <label class="block text-sm font-bold text-slate-200 mb-2">
                        <span class="material-symbols-outlined text-sm ml-1 align-middle">percent</span>
                        النسبة المئوية *
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" name="rate" value="{{ old('rate', $tax->rate ?? '') }}" 
                               class="w-full bg-surface-dark border border-border-dark text-white px-4 py-3 rounded-xl focus:border-primary outline-none"
                               placeholder="15" required>
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">%</span>
                    </div>
                    @error('rate')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-1">مثال: 15 تعني 15%</p>
                </div>

                <!-- Is Default -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_default" id="is_default" value="1" 
                           class="accent-primary w-5 h-5"
                           {{ old('is_default', $tax->is_default ?? false) ? 'checked' : '' }}>
                    <label for="is_default" class="text-sm font-medium text-slate-200">
                        ضريبة افتراضية
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-border-dark">
                    <button type="submit" class="flex-1 bg-primary text-white py-3 px-6 rounded-xl font-bold hover:bg-blue-600 transition-colors shadow-lg shadow-blue-900/40">
                        <span class="material-symbols-outlined text-sm ml-2 align-middle">save</span>
                        حفظ الضريبة
                    </button>
                    <a href="{{ route('settings.taxes') }}" class="px-6 py-3 border border-slate-600 text-slate-200 rounded-xl font-medium hover:bg-slate-800 transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
