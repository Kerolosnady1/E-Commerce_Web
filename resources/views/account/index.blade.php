@extends('layouts.app')

@section('title', 'إدارة الحساب - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white">إدارة الحساب</h1>
            <p class="text-slate-400 mt-2">إدارة إعدادات حسابك والبيانات الشخصية</p>
        </div>

        <!-- Account Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Profile Card -->
            <a href="{{ route('profile') }}"
                class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-2xl">person</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">الملف الشخصي</h3>
                        <p class="text-slate-400 text-sm">تعديل البيانات الشخصية</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">آخر تحديث</span>
                    <span class="text-white">{{ now()->format('d/m/Y') }}</span>
                </div>
            </a>

            <!-- Security Card -->
            <a href="{{ route('security') }}"
                class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-green-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-400 text-2xl">shield</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">الأمان</h3>
                        <p class="text-slate-400 text-sm">كلمة المرور والمصادقة الثنائية</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">الحالة</span>
                    <span class="text-green-400 font-medium">آمن</span>
                </div>
            </a>

            <!-- Subscription Card -->
            <a href="{{ route('subscription') }}"
                class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-yellow-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-yellow-400 text-2xl">workspace_premium</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">الاشتراك</h3>
                        <p class="text-slate-400 text-sm">إدارة خطة الاشتراك</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">الخطة الحالية</span>
                    <span class="text-primary font-medium">الاحترافية</span>
                </div>
            </a>
        </div>

        <!-- Account Settings -->
        <div class="bg-card-dark border border-border-dark rounded-2xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">settings</span>
                إعدادات الحساب
            </h2>

            <div class="space-y-4">
                <!-- Language -->
                <div class="flex items-center justify-between p-4 bg-surface-dark rounded-xl">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-slate-400">language</span>
                        <div>
                            <p class="text-white font-medium">اللغة</p>
                            <p class="text-slate-400 text-sm">لغة واجهة المستخدم</p>
                        </div>
                    </div>
                    <span class="text-primary font-medium">العربية</span>
                </div>

                <!-- Timezone -->
                <div class="flex items-center justify-between p-4 bg-surface-dark rounded-xl">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-slate-400">schedule</span>
                        <div>
                            <p class="text-white font-medium">المنطقة الزمنية</p>
                            <p class="text-slate-400 text-sm">التوقيت المستخدم في التواريخ</p>
                        </div>
                    </div>
                    <span class="text-primary font-medium">Africa/Cairo (UTC+2)</span>
                </div>

                <!-- Notifications -->
                <div class="flex items-center justify-between p-4 bg-surface-dark rounded-xl">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-slate-400">notifications</span>
                        <div>
                            <p class="text-white font-medium">الإشعارات</p>
                            <p class="text-slate-400 text-sm">إشعارات البريد الإلكتروني والتطبيق</p>
                        </div>
                    </div>
                    <a href="{{ route('notifications.index') }}"
                        class="text-primary hover:text-blue-300 font-medium">إدارة</a>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-card-dark border border-border-dark rounded-2xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">link</span>
                روابط سريعة
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('settings.general') }}"
                    class="p-4 bg-surface-dark rounded-xl text-center hover:bg-border-dark transition-colors">
                    <span class="material-symbols-outlined text-2xl text-slate-400 mb-2">tune</span>
                    <p class="text-white text-sm font-medium">الإعدادات العامة</p>
                </a>
                <a href="{{ route('settings.taxes') }}"
                    class="p-4 bg-surface-dark rounded-xl text-center hover:bg-border-dark transition-colors">
                    <span class="material-symbols-outlined text-2xl text-slate-400 mb-2">receipt_long</span>
                    <p class="text-white text-sm font-medium">إعدادات الضرائب</p>
                </a>
                <a href="{{ route('settings.print-templates') }}"
                    class="p-4 bg-surface-dark rounded-xl text-center hover:bg-border-dark transition-colors">
                    <span class="material-symbols-outlined text-2xl text-slate-400 mb-2">print</span>
                    <p class="text-white text-sm font-medium">نماذج الطباعة</p>
                </a>
                <a href="{{ route('settings.locale-time') }}"
                    class="p-4 bg-surface-dark rounded-xl text-center hover:bg-border-dark transition-colors">
                    <span class="material-symbols-outlined text-2xl text-slate-400 mb-2">public</span>
                    <p class="text-white text-sm font-medium">اللغة والوقت</p>
                </a>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-500/5 border border-red-500/30 rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-red-400 mb-4 flex items-center gap-3">
                <span class="material-symbols-outlined">warning</span>
                منطقة الخطر
            </h2>
            <p class="text-slate-400 mb-6">هذه الإجراءات لا يمكن التراجع عنها. يرجى التأكد قبل المتابعة.</p>

            <div class="flex gap-4">
                <form action="{{ route('account.export') }}" method="GET">
                    <button type="submit" onclick="return confirm('هل تريد تصدير جميع بياناتك؟')"
                        class="px-6 py-3 bg-slate-800 text-white rounded-xl font-medium hover:bg-slate-700 transition-colors border border-slate-600">
                        <span class="material-symbols-outlined text-sm ml-2 align-middle">download</span>
                        تصدير البيانات
                    </button>
                </form>

                <form action="{{ route('account.delete') }}" method="POST"
                    onsubmit="return confirm('⚠️ تحذير خطير!\n\nحذف الحساب سيؤدي إلى:\n- حذف جميع بياناتك\n- إلغاء اشتراكك\n- فقدان الوصول نهائياً\n\nهل أنت متأكد تماماً من رغبتك في حذف الحساب؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-6 py-3 bg-red-500/10 text-red-400 rounded-xl font-medium hover:bg-red-500/20 transition-colors border border-red-500/30">
                        <span class="material-symbols-outlined text-sm ml-2 align-middle">delete_forever</span>
                        حذف الحساب نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection