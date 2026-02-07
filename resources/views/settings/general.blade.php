@extends('layouts.app')

@section('title', 'الإعدادات العامة - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">الإعدادات العامة</h1>
        <p class="text-slate-400 mt-1">إعدادات الشركة والنظام</p>
    </div>

    <!-- Settings Navigation -->
    <div class="flex flex-wrap gap-4 mb-6">
        <a href="{{ route('settings.general') }}" class="px-4 py-2 bg-primary text-white rounded-lg">عامة</a>
        <a href="{{ route('settings.system') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">النظام</a>
        <a href="{{ route('settings.taxes') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">الضرائب</a>
        <a href="{{ route('settings.print-templates') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">قوالب الطباعة</a>
        <a href="{{ route('settings.locale-time') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">اللغة والوقت</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Company Info -->
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">معلومات الشركة</h2>
            <form action="{{ route('settings.general.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div>
                    <label class="block text-slate-400 text-sm mb-2">اسم الشركة (عربي)</label>
                    <input type="text" name="company_name_ar" value="{{ $settings->company_name_ar ?? '' }}"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-2">اسم الشركة (إنجليزي)</label>
                    <input type="text" name="company_name_en" value="{{ $settings->company_name_en ?? '' }}"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-2">العملة</label>
                    <select name="currency"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                        <option value="SAR" {{ ($settings->currency ?? '') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)
                        </option>
                        <option value="USD" {{ ($settings->currency ?? '') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)
                        </option>
                        <option value="EUR" {{ ($settings->currency ?? '') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        <option value="EGP" {{ ($settings->currency ?? '') == 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-2">رفع شعار جديد</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors">
                    حفظ التغييرات
                </button>
            </form>
        </div>
    </div>
@endsection
