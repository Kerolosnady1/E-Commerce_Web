@extends('layouts.app')

@section('title', isset($user) ? 'تعديل المستخدم - نظام ERP' : 'إضافة مستخدم - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">{{ isset($user) ? 'تعديل المستخدم' : 'إضافة مستخدم جديد' }}</h1>
            <p class="text-slate-400 mt-1">{{ isset($user) ? 'تحديث بيانات المستخدم' : 'أدخل بيانات المستخدم الجديد' }}</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" 
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                               placeholder="الاسم الكامل" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                               placeholder="example@domain.com" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">كلمة المرور {{ isset($user) ? '(اتركها فارغة للإبقاء)' : '' }}</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="••••••••" {{ isset($user) ? '' : 'required' }}>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                   placeholder="••••••••" {{ isset($user) ? '' : 'required' }}>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الدور</label>
                        <select name="role" 
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <option value="employee" {{ old('role', $user->role ?? '') == 'employee' ? 'selected' : '' }}>موظف</option>
                            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>مدير</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-primary cursor-pointer">
                        <label for="is_active" class="text-slate-300 cursor-pointer">المستخدم نشط</label>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            {{ isset($user) ? 'تحديث المستخدم' : 'إضافة المستخدم' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white text-center font-black rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
