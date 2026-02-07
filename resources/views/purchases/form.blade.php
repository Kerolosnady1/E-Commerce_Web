@extends('layouts.app')

@section('title', '{{ isset($purchaseOrder) ? "تعديل" : "إنشاء" }} أمر شراء - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-card-dark border border-border-dark rounded-xl shadow-2xl p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ isset($purchaseOrder) ? 'تعديل' : 'إنشاء' }} أمر شراء
                    </h1>
                    <p class="text-slate-400">أكمل نموذج أمر الشراء وأضف الملاحظات المطلوبة</p>
                </div>
                <span class="text-5xl">🛒</span>
            </div>

            <form method="post"
                action="{{ isset($purchaseOrder) ? route('purchases.update', $purchaseOrder) : route('purchases.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($purchaseOrder))
                    @method('PUT')
                @endif

                <!-- Row 1: Supplier and Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                            <span>🏭</span>
                            المورد
                        </label>
                        <select name="supplier_id"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none"
                            required>
                            <option value="">اختر المورد</option>
                            @foreach(App\Models\Supplier::all() as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                            <span>📅</span>
                            تاريخ الطلب
                        </label>
                        <input type="date" name="issued_date"
                            value="{{ old('issued_date', isset($purchaseOrder) ? $purchaseOrder->issued_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none"
                            required>
                        @error('issued_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Number and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                            <span>🏷️</span>
                            رقم الطلب
                        </label>
                        <input type="text" name="number"
                            value="{{ old('number', $purchaseOrder->number ?? 'PO-' . date('Ymd') . '-' . rand(100, 999)) }}"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none"
                            required>
                        @error('number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                            <span>ℹ️</span>
                            حالة الطلب
                        </label>
                        <select name="status"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none"
                            required>
                            <option value="pending" {{ old('status', $purchaseOrder->status ?? '') == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="approved" {{ old('status', $purchaseOrder->status ?? '') == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                            <option value="received" {{ old('status', $purchaseOrder->status ?? '') == 'received' ? 'selected' : '' }}>مستلم</option>
                            <option value="cancelled" {{ old('status', $purchaseOrder->status ?? '') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Total -->
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                        <span>💰</span>
                        المبلغ الإجمالي
                    </label>
                    <input type="number" step="0.01" name="total" value="{{ old('total', $purchaseOrder->total ?? '') }}"
                        class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none"
                        required>
                    @error('total')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 4: Print Template -->
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                        <span>🖨️</span>
                        نموذج الطباعة
                    </label>
                    <select name="print_template_id"
                        class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none">
                        <option value="">بدون نموذج</option>
                        @foreach(App\Models\PrintTemplate::all() as $template)
                            <option value="{{ $template->id }}" {{ old('print_template_id', $purchaseOrder->print_template_id ?? '') == $template->id ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-slate-400 text-xs mt-2">اختر نموذج الطباعة المناسب لأمر الشراء</p>
                </div>

                <!-- Row 5: Notes -->
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                        <span>📝</span>
                        ملاحظات الطلب
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none resize-none">{{ old('notes', $purchaseOrder->notes ?? '') }}</textarea>
                    <p class="text-slate-400 text-xs mt-2">أضف أي ملاحظات أو شروط إضافية</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t border-border-dark">
                    <button type="submit"
                        class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-lg">
                        <span class="material-symbols-outlined">save</span>
                        حفظ الطلب
                    </button>
                    <a href="{{ route('purchases.bulk') }}"
                        class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 text-slate-100 font-semibold px-6 py-3 rounded-lg transition-all duration-200">
                        <span class="material-symbols-outlined">close</span>
                        إغلاق
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection