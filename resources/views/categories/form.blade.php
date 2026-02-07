@extends('layouts.app')

@section('title', isset($category) ? 'تعديل التصنيف - نظام ERP' : 'إضافة تصنيف - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">{{ isset($category) ? 'تعديل التصنيف' : 'إضافة تصنيف جديد' }}</h1>
            <p class="text-slate-400 mt-1">{{ isset($category) ? 'تحديث بيانات التصنيف' : 'أدخل بيانات التصنيف الجديد' }}
            </p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}"
                method="POST">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">اسم التصنيف</label>
                        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                            placeholder="مثال: إلكترونيات" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">التصنيف الأب (اختياري)</label>
                        <select name="parent_id"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <option value="">— تصنيف رئيسي —</option>
                            @foreach($parentCategories ?? [] as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الوصف</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all resize-none"
                            placeholder="وصف مختصر للتصنيف">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit"
                            class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            {{ isset($category) ? 'تحديث التصنيف' : 'إضافة التصنيف' }}
                        </button>
                        <a href="{{ route('categories.index') }}"
                            class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection