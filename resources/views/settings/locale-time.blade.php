@extends('layouts.app')

@section('title', 'اللغة والتوقيت - نظام ERP')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">اللغة والتوقيت</h1>
            <p class="text-slate-400 mt-1">تخصيص إعدادات اللغة والمنطقة الزمنية</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ route('settings.locale-time') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Language -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">لغة النظام</label>
                        <select name="locale"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                            <option value="ar" {{ old('locale', $settings['locale'] ?? 'ar') == 'ar' ? 'selected' : '' }}>
                                العربية</option>
                            <option value="en" {{ old('locale', $settings['locale'] ?? '') == 'en' ? 'selected' : '' }}>
                                English</option>
                        </select>
                        <p class="text-slate-500 text-xs mt-1">لغة واجهة المستخدم</p>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">المنطقة الزمنية</label>
                        <select name="timezone"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                            <option value="Asia/Riyadh" {{ old('timezone', $settings['timezone'] ?? 'Asia/Riyadh') == 'Asia/Riyadh' ? 'selected' : '' }}>الرياض (GMT+3)</option>
                            <option value="Asia/Dubai" {{ old('timezone', $settings['timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>دبي (GMT+4)</option>
                            <option value="Africa/Cairo" {{ old('timezone', $settings['timezone'] ?? '') == 'Africa/Cairo' ? 'selected' : '' }}>القاهرة (GMT+2)</option>
                            <option value="Asia/Kuwait" {{ old('timezone', $settings['timezone'] ?? '') == 'Asia/Kuwait' ? 'selected' : '' }}>الكويت (GMT+3)</option>
                            <option value="UTC" {{ old('timezone', $settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>
                                UTC</option>
                        </select>
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">تنسيق التاريخ</label>
                        <select name="date_format"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                            <option value="Y-m-d" {{ old('date_format', $settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>2026-02-07</option>
                            <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>07/02/2026</option>
                            <option value="d-m-Y" {{ old('date_format', $settings['date_format'] ?? '') == 'd-m-Y' ? 'selected' : '' }}>07-02-2026</option>
                            <option value="m/d/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>02/07/2026</option>
                        </select>
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">تنسيق الوقت</label>
                        <div class="grid grid-cols-2 gap-4" id="timeFormatContainer">
                            <label
                                class="p-4 bg-surface-dark rounded-xl cursor-pointer border-2 {{ old('time_format', $settings['time_format'] ?? '24h') == '24h' ? 'border-primary' : 'border-transparent' }} hover:border-primary/50 transition-all time-format-label">
                                <input type="radio" name="time_format" value="24h" class="hidden" {{ old('time_format', $settings['time_format'] ?? '24h') == '24h' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <span class="text-2xl font-bold text-white">14:30</span>
                                    <p class="text-slate-400 text-sm mt-1">24 ساعة</p>
                                </div>
                            </label>
                            <label
                                class="p-4 bg-surface-dark rounded-xl cursor-pointer border-2 {{ old('time_format', $settings['time_format'] ?? '') == '12h' ? 'border-primary' : 'border-transparent' }} hover:border-primary/50 transition-all time-format-label">
                                <input type="radio" name="time_format" value="12h" class="hidden" {{ old('time_format', $settings['time_format'] ?? '') == '12h' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <span class="text-2xl font-bold text-white">2:30 م</span>
                                    <p class="text-slate-400 text-sm mt-1">12 ساعة</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <script>
                        document.querySelectorAll('.time-format-label').forEach(label => {
                            label.addEventListener('click', () => {
                                document.querySelectorAll('.time-format-label').forEach(l => {
                                    l.classList.remove('border-primary');
                                    l.classList.add('border-transparent');
                                });
                                label.classList.add('border-primary');
                                label.classList.remove('border-transparent');
                                label.querySelector('input').checked = true;
                            });
                        });
                    </script>

                    <!-- Currency -->
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">العملة الافتراضية</label>
                        <select name="currency"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                            <option value="SAR" {{ old('currency', $settings['currency'] ?? 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي (ر.س)</option>
                            <option value="AED" {{ old('currency', $settings['currency'] ?? '') == 'AED' ? 'selected' : '' }}>
                                درهم إماراتي (د.إ)</option>
                            <option value="EGP" {{ old('currency', $settings['currency'] ?? '') == 'EGP' ? 'selected' : '' }}>
                                جنيه مصري (ج.م)</option>
                            <option value="USD" {{ old('currency', $settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                دولار أمريكي ($)</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-border-dark">
                        <button type="submit"
                            class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                            حفظ الإعدادات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection