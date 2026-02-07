@extends('layouts.app')

@section('title', 'إنشاء إشعار - نظام ERP')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('notifications.index') }}" class="text-primary hover:text-blue-300 flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined">arrow_back</span>
                العودة إلى الإشعارات
            </a>
            <h1 class="text-3xl font-bold text-white">{{ isset($notification) ? 'تعديل' : 'إنشاء' }} إشعار</h1>
            <p class="text-slate-400 mt-2">إرسال إشعار للمستخدمين</p>
        </div>

        <!-- Form -->
        <div class="bg-card-dark rounded-2xl shadow-lg p-8 border border-border-dark">
            <form method="post" action="{{ isset($notification) ? route('notifications.update', $notification) : route('notifications.store') }}" class="space-y-6">
                @csrf
                @if(isset($notification))
                    @method('PUT')
                @endif
                
                <!-- Title -->
                <div>
                    <label class="block text-sm font-bold text-slate-200 mb-2">
                        <span class="material-symbols-outlined text-sm ml-1 align-middle">title</span>
                        عنوان الإشعار *
                    </label>
                    <input type="text" name="title" value="{{ old('title', $notification->title ?? '') }}" 
                           class="w-full bg-surface-dark border border-border-dark text-white px-4 py-3 rounded-xl focus:border-primary outline-none"
                           placeholder="مثال: تحديث النظام" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-slate-200 mb-2">
                        <span class="material-symbols-outlined text-sm ml-1 align-middle">category</span>
                        نوع الإشعار *
                    </label>
                    <select name="type" class="w-full bg-surface-dark border border-border-dark text-white px-4 py-3 rounded-xl focus:border-primary outline-none" required>
                        <option value="info" {{ old('type', $notification->type ?? '') == 'info' ? 'selected' : '' }}>معلومات</option>
                        <option value="success" {{ old('type', $notification->type ?? '') == 'success' ? 'selected' : '' }}>نجاح</option>
                        <option value="warning" {{ old('type', $notification->type ?? '') == 'warning' ? 'selected' : '' }}>تحذير</option>
                        <option value="error" {{ old('type', $notification->type ?? '') == 'error' ? 'selected' : '' }}>خطأ</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-bold text-slate-200 mb-2">
                        <span class="material-symbols-outlined text-sm ml-1 align-middle">message</span>
                        نص الإشعار *
                    </label>
                    <textarea name="message" rows="5" 
                              class="w-full bg-surface-dark border border-border-dark text-white px-4 py-3 rounded-xl focus:border-primary outline-none resize-none"
                              placeholder="اكتب رسالة الإشعار هنا..." required>{{ old('message', $notification->message ?? '') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Users -->
                <div>
                    <label class="block text-sm font-bold text-slate-200 mb-2">
                        <span class="material-symbols-outlined text-sm ml-1 align-middle">group</span>
                        إرسال إلى
                    </label>
                    <select name="target" class="w-full bg-surface-dark border border-border-dark text-white px-4 py-3 rounded-xl focus:border-primary outline-none">
                        <option value="all">جميع المستخدمين</option>
                        <option value="admins">المديرين فقط</option>
                        <option value="users">المستخدمين فقط</option>
                    </select>
                </div>

                <!-- Priority -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_urgent" id="is_urgent" value="1" 
                           class="accent-primary w-5 h-5"
                           {{ old('is_urgent', $notification->is_urgent ?? false) ? 'checked' : '' }}>
                    <label for="is_urgent" class="text-sm font-medium text-slate-200">
                        إشعار عاجل (سيظهر بشكل مميز)
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-border-dark">
                    <button type="submit" class="flex-1 bg-primary text-white py-3 px-6 rounded-xl font-bold hover:bg-blue-600 transition-colors shadow-lg shadow-blue-900/40">
                        <span class="material-symbols-outlined text-sm ml-2 align-middle">send</span>
                        إرسال الإشعار
                    </button>
                    <a href="{{ route('notifications.index') }}" class="px-6 py-3 border border-slate-600 text-slate-200 rounded-xl font-medium hover:bg-slate-800 transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
