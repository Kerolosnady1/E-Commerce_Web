@extends('layouts.app')

@section('title', isset($product) ? 'تعديل المنتج - نظام ERP' : 'إضافة منتج - نظام ERP')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">{{ isset($product) ? 'تعديل المنتج' : 'إضافة منتج جديد' }}</h1>
            <p class="text-slate-400 mt-1">{{ isset($product) ? 'تحديث بيانات المنتج' : 'أدخل بيانات المنتج الجديد' }}</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">اسم المنتج</label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="اسم المنتج" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">رمز المنتج (SKU)</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="PRD-001" required>
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">التصنيف</label>
                            <select name="category_id" 
                                    class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                <option value="">— اختر التصنيف —</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">الوحدة</label>
                            <select name="unit" 
                                    class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                <option value="piece" {{ old('unit', $product->unit ?? '') == 'piece' ? 'selected' : '' }}>قطعة</option>
                                <option value="kg" {{ old('unit', $product->unit ?? '') == 'kg' ? 'selected' : '' }}>كيلوجرام</option>
                                <option value="meter" {{ old('unit', $product->unit ?? '') == 'meter' ? 'selected' : '' }}>متر</option>
                                <option value="box" {{ old('unit', $product->unit ?? '') == 'box' ? 'selected' : '' }}>صندوق</option>
                            </select>
                            @error('unit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">سعر الشراء</label>
                            <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="0.00">
                            @error('cost_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">سعر البيع</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="0.00" required>
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">نسبة الضريبة (%)</label>
                            <input type="number" step="0.01" name="tax_rate" value="{{ old('tax_rate', $product->tax_rate ?? '15') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="15">
                            @error('tax_rate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الوصف</label>
                        <textarea name="description_ar" rows="3"
                                  class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all resize-none"
                                  placeholder="وصف المنتج">{{ old('description_ar', $product->description_ar ?? '') }}</textarea>
                        @error('description_ar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-primary cursor-pointer">
                        <label for="is_active" class="text-slate-300 cursor-pointer">المنتج نشط ومتاح للبيع</label>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            {{ isset($product) ? 'تحديث المنتج' : 'إضافة المنتج' }}
                        </button>
                        <a href="{{ route('products.index') }}" class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
