@extends('layouts.app')

@section('title', isset($warehouse) ? 'تعديل المستودع - نظام ERP' : 'إضافة مستودع - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">{{ isset($warehouse) ? 'تعديل المستودع' : 'إضافة مستودع جديد' }}</h1>
            <p class="text-slate-400 mt-1">{{ isset($warehouse) ? 'تحديث بيانات المستودع' : 'أدخل بيانات المستودع الجديد' }}</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ isset($warehouse) ? route('warehouses.update', $warehouse) : route('warehouses.store') }}" method="POST">
                @csrf
                @if(isset($warehouse))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">اسم المستودع</label>
                        <input type="text" name="name" value="{{ old('name', $warehouse->name ?? '') }}" 
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                               placeholder="مثال: المستودع الرئيسي" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الموقع</label>
                        <input type="text" name="location" value="{{ old('location', $warehouse->location ?? '') }}" 
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                               placeholder="عنوان المستودع">
                        @error('location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الوصف</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all resize-none"
                                  placeholder="وصف المستودع">{{ old('description', $warehouse->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $warehouse->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-primary cursor-pointer">
                        <label for="is_active" class="text-slate-300 cursor-pointer">المستودع نشط</label>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            {{ isset($warehouse) ? 'تحديث المستودع' : 'إضافة المستودع' }}
                        </button>
                        <a href="{{ route('warehouses.index') }}" class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
