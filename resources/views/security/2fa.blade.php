@extends('layouts.app')

@section('title', 'المصادقة الثنائية - نظام ERP')

@section('content')
    @php $enabled = $profile->two_factor_enabled ?? false; @endphp
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">المصادقة الثنائية (2FA)</h1>
            <p class="text-slate-400 mt-1">إضافة طبقة حماية إضافية لحسابك</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <!-- Status Card -->
            <div class="flex items-center gap-4 p-6 bg-surface-dark rounded-xl mb-8">
                <div
                    class="w-16 h-16 {{ $enabled ? 'bg-green-500/10' : 'bg-amber-500/10' }} rounded-2xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl {{ $enabled ? 'text-green-400' : 'text-amber-400' }}">
                        {{ $enabled ? 'verified_user' : 'shield' }}
                    </span>
                </div>
                <div class="flex-1">
                    <p class="text-lg font-bold {{ $enabled ? 'text-green-400' : 'text-amber-400' }}">
                        {{ $enabled ? 'المصادقة الثنائية مفعلة' : 'المصادقة الثنائية غير مفعلة' }}
                    </p>
                    <p class="text-slate-400 text-sm mt-1">
                        {{ $enabled ? 'حسابك محمي بطبقة أمان إضافية' : 'قم بتفعيل المصادقة الثنائية لحماية حسابك' }}
                    </p>
                </div>
            </div>

            @if(!$enabled)
                <!-- Enable 2FA Form -->
                <div class="space-y-6">
                    <div class="text-center">
                        <div class="inline-block p-6 bg-white rounded-xl mb-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=otpauth://totp/ERP:{{ auth()->user()->email ?? 'user@example.com' }}?secret={{ $profile->two_factor_secret }}"
                                alt="QR Code" class="w-44 h-44">
                        </div>
                        <p class="text-slate-400 text-sm">امسح هذا الرمز باستخدام تطبيق Google Authenticator</p>
                    </div>

                    <div class="p-4 bg-surface-dark rounded-xl">
                        <p class="text-xs text-slate-400 mb-2">أو أدخل هذا الرمز يدوياً:</p>
                        <code class="text-primary font-mono text-lg tracking-wider">{{ $profile->two_factor_secret }}</code>
                    </div>

                    <form action="{{ route('security.2fa.enable') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">رمز التحقق</label>
                                <input type="text" name="code" maxlength="6" pattern="\d{6}" required
                                    class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white text-center text-2xl tracking-widest font-mono focus:border-primary outline-none"
                                    placeholder="000000">
                                @error('code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-black rounded-xl transition-all">
                                تفعيل المصادقة الثنائية
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Disable 2FA -->
                <div class="space-y-6">
                    <div class="p-4 bg-green-500/10 border border-green-500/20 rounded-xl">
                        <div class="flex items-center gap-2 text-green-400 font-bold mb-2">
                            <span class="material-symbols-outlined">check_circle</span>
                            المصادقة الثنائية مفعلة
                        </div>
                        <p class="text-slate-400 text-sm">يتم طلب رمز التحقق في كل مرة تسجل الدخول</p>
                    </div>

                    <form action="{{ route('security.2fa.disable') }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من إلغاء المصادقة الثنائية؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-3 bg-red-500 hover:bg-red-600 text-white font-black rounded-xl transition-all">
                            إلغاء المصادقة الثنائية
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('security.index') }}" class="text-primary hover:underline">
                العودة إلى إعدادات الأمان
            </a>
        </div>
    </div>
@endsection