@extends('layouts.app')

@section('title', 'تسجيل الدخول - نظام ERP')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="bg-card-dark border border-border-dark rounded-xl p-8">
                <div class="text-center mb-8">
                    <div class="inline-flex p-4 bg-primary/20 rounded-full mb-4">
                        <span class="material-icons text-primary text-4xl">lock</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">تسجيل الدخول</h1>
                    <p class="text-slate-400 mt-2">أدخل بياناتك للوصول إلى حسابك</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        @foreach($errors->all() as $error)
                            <p class="text-red-300 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-slate-400 text-sm mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                            placeholder="example@domain.com" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="block text-slate-400 text-sm mb-2">كلمة المرور</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                            placeholder="••••••••" required>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-border-dark bg-surface-dark text-primary focus:ring-primary">
                            <span class="text-slate-400 text-sm">تذكرني</span>
                        </label>
                        <a href="#" class="text-primary text-sm hover:underline">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition-colors">
                        تسجيل الدخول
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-sm">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="text-primary hover:underline">إنشاء حساب جديد</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection