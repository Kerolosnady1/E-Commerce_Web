@extends('layouts.app')

@section('title', isset($supplier) ? 'تعديل المورد - نظام ERP' : 'إضافة مورد - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">{{ isset($supplier) ? 'تعديل بيانات المورد' : 'إضافة مورد جديد' }}</h1>
            <p class="text-slate-400 mt-1">{{ isset($supplier) ? 'تحديث معلومات المورد' : 'أدخل بيانات المورد الجديد' }}</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST">
                @csrf
                @if(isset($supplier))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">اسم المورد</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" 
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                               placeholder="أدخل اسم المورد" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="example@domain.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="+966 5X XXX XXXX">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">العنوان</label>
                        <textarea name="address" rows="3"
                                  class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all resize-none"
                                  placeholder="عنوان المورد">{{ old('address', $supplier->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-primary cursor-pointer">
                        <label for="is_active" class="text-slate-300 cursor-pointer">المورد نشط</label>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            {{ isset($supplier) ? 'تحديث البيانات' : 'إضافة المورد' }}
                        </button>
                        <a href="{{ route('suppliers.index') }}" class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
