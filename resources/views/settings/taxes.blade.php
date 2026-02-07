@extends('layouts.app')

@section('title', 'إعدادات الضرائب - نظام ERP')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">إعدادات الضرائب</h1>
            <p class="text-slate-400 mt-1">إدارة إعدادات ضريبة القيمة المضافة</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ route('settings.taxes.save') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Enable VAT -->
                    <div class="p-6 bg-surface-dark rounded-xl">
                        <label class="flex items-center gap-4 cursor-pointer">
                            <input type="checkbox" name="tax_enabled" value="1"
                                   {{ old('tax_enabled', $settings->tax_enabled ?? true) ? 'checked' : '' }}
                                   class="w-6 h-6 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-bold text-lg">تفعيل ضريبة القيمة المضافة</span>
                                <p class="text-slate-400 text-sm mt-1">عند التفعيل، ستُضاف الضريبة تلقائياً للفواتير</p>
                            </div>
                        </label>
                    </div>

                    <!-- VAT Rate -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">نسبة الضريبة (%)</label>
                        <div class="relative">
                            <input type="number" name="default_tax_rate" step="0.01" value="{{ old('default_tax_rate', $settings->default_tax_rate ?? 15) }}" 
                                   class="w-full px-4 py-3 pl-12 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none text-xl font-bold">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">%</span>
                        </div>
                        <p class="text-slate-500 text-xs mt-1">النسبة القياسية في السعودية: 15%</p>
                    </div>

                    <!-- VAT Number -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الرقم الضريبي للمنشأة</label>
                        <input type="text" name="vat_number" value="{{ old('vat_number', $settings->vat_number ?? '') }}" 
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none font-mono"
                               placeholder="300000000000003">
                        <p class="text-slate-500 text-xs mt-1">رقم التسجيل في ضريبة القيمة المضافة (15 رقم)</p>
                    </div>

                    <!-- Invoice Settings -->
                    <div class="space-y-4 pt-4 border-t border-border-dark">
                        <h3 class="text-white font-bold">إعدادات الفواتير</h3>
                        
                        <label class="flex items-center gap-3 p-4 bg-surface-dark rounded-xl cursor-pointer">
                            <input type="checkbox" name="show_vat_on_invoice" value="1"
                                   {{ old('show_vat_on_invoice', $settings->show_vat_on_invoice ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-medium">عرض تفصيل الضريبة</span>
                                <p class="text-slate-500 text-xs">إظهار قيمة الضريبة بشكل منفصل في الفاتورة</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-surface-dark rounded-xl cursor-pointer">
                            <input type="checkbox" name="prices_include_tax" value="1"
                                   {{ old('prices_include_tax', $settings->prices_include_tax ?? false) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-medium">الأسعار شاملة الضريبة</span>
                                <p class="text-slate-500 text-xs">أسعار المنتجات الافتراضية تشمل الضريبة</p>
                            </div>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-border-dark flex gap-4">
                        <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            حفظ الإعدادات
                        </button>
                        <a href="{{ route('settings.general') }}" class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
