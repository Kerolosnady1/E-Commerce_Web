@extends('layouts.app')

@section('title', 'سياسة كلمة المرور - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">سياسة كلمة المرور</h1>
            <p class="text-slate-400 mt-1">تحديد متطلبات قوة كلمة المرور</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ route('security.password-policy') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Min Length -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">الحد الأدنى لطول كلمة المرور</label>
                        <input type="number" name="min_length" value="{{ old('min_length', $settings['min_length'] ?? 8) }}" min="6" max="32"
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                        <p class="text-slate-500 text-xs mt-1">الحد الأدنى الموصى به: 8 أحرف</p>
                    </div>

                    <!-- Requirements -->
                    <div class="space-y-4">
                        <h3 class="text-white font-bold">متطلبات كلمة المرور</h3>
                        
                        <label class="flex items-center gap-3 p-4 bg-surface-dark rounded-xl cursor-pointer hover:bg-surface-dark/80 transition-colors">
                            <input type="checkbox" name="require_uppercase" value="1"
                                   {{ old('require_uppercase', $settings['require_uppercase'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-medium">أحرف كبيرة</span>
                                <p class="text-slate-500 text-xs">يجب أن تحتوي على حرف كبير واحد على الأقل (A-Z)</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-surface-dark rounded-xl cursor-pointer hover:bg-surface-dark/80 transition-colors">
                            <input type="checkbox" name="require_lowercase" value="1"
                                   {{ old('require_lowercase', $settings['require_lowercase'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-medium">أحرف صغيرة</span>
                                <p class="text-slate-500 text-xs">يجب أن تحتوي على حرف صغير واحد على الأقل (a-z)</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-surface-dark rounded-xl cursor-pointer hover:bg-surface-dark/80 transition-colors">
                            <input type="checkbox" name="require_numbers" value="1"
                                   {{ old('require_numbers', $settings['require_numbers'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-medium">أرقام</span>
                                <p class="text-slate-500 text-xs">يجب أن تحتوي على رقم واحد على الأقل (0-9)</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-surface-dark rounded-xl cursor-pointer hover:bg-surface-dark/80 transition-colors">
                            <input type="checkbox" name="require_symbols" value="1"
                                   {{ old('require_symbols', $settings['require_symbols'] ?? false) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-primary cursor-pointer">
                            <div>
                                <span class="text-white font-medium">رموز خاصة</span>
                                <p class="text-slate-500 text-xs">يجب أن تحتوي على رمز خاص (!@#$%^&*)</p>
                            </div>
                        </label>
                    </div>

                    <!-- Expiration -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">انتهاء صلاحية كلمة المرور (بالأيام)</label>
                        <input type="number" name="expiry_days" value="{{ old('expiry_days', $settings['expiry_days'] ?? 90) }}" min="0" max="365"
                               class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                        <p class="text-slate-500 text-xs mt-1">اترك 0 لعدم وجود انتهاء صلاحية</p>
                    </div>

                    <div class="pt-4 border-t border-border-dark">
                        <button type="submit" class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            حفظ الإعدادات
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('security.index') }}" class="text-primary hover:underline">العودة إلى إعدادات الأمان</a>
        </div>
    </div>
@endsection
