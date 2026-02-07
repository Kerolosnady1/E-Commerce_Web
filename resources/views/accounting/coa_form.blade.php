@extends('layouts.app')

@section('title', 'حساب جديد - نظام ERP')

@section('content')
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('accounting.coa') }}" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-white">إضافة حساب جديد</h1>
        </div>
        <p class="text-slate-400">أضف حساباً جديداً إلى دليل الحسابات الخاص بنظامك</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <div>
                <p class="font-bold">يرجى تصحيح الأخطاء التالية:</p>
                <ul class="text-xs list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="max-w-2xl">
        <form action="{{ route('accounting.coa.store') }}" method="POST"
            class="bg-card-dark border border-border-dark rounded-2xl p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">رمز الحساب</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="مثال: 1020"
                        class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none font-mono">
                    <p class="text-[10px] text-slate-500 mt-1">يجب أن يكون الرمز فريداً وغير متكرر</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">نوع الحساب</label>
                    <select name="type" required
                        class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none appearance-none">
                        <option value="">اختر النوع...</option>
                        <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>أصل (Asset)</option>
                        <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>التزام (Liability)
                        </option>
                        <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>حقوق ملكية (Equity)</option>
                        <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>إيراد (Revenue)</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>مصروف (Expense)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">اسم الحساب (بالعربية أو الإنجليزية)</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="مثال: البنك الأهلي - فرع الرياض"
                    class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">الرصيد الافتتاحي</label>
                <div class="relative">
                    <input type="number" step="0.01" name="initial_balance" value="{{ old('initial_balance', 0) }}" required
                        class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none pl-12">
                    <span class="absolute left-4 top-2 text-slate-500 text-sm">ر.س</span>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit"
                    class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white rounded-xl font-bold transition-all">
                    حفظ الحساب
                </button>
                <a href="{{ route('accounting.coa') }}"
                    class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-all">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection