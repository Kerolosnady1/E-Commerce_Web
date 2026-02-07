@extends('layouts.app')

@section('title', 'الدعم الفني - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-white">الدعم الفني</h1>
            <p class="text-slate-400 mt-2">كيف يمكننا مساعدتك؟</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-card-dark border border-border-dark rounded-xl p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-primary/20 rounded-full flex items-center justify-center">
                    <span class="material-icons text-primary text-3xl">email</span>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">البريد الإلكتروني</h3>
                <p class="text-slate-400 mb-4">تواصل معنا عبر البريد الإلكتروني</p>
                <a href="mailto:support@erp-system.com" class="text-primary hover:underline">support@erp-system.com</a>
            </div>

            <div class="bg-card-dark border border-border-dark rounded-xl p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-500/20 rounded-full flex items-center justify-center">
                    <span class="material-icons text-green-400 text-3xl">phone</span>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">الهاتف</h3>
                <p class="text-slate-400 mb-4">متاحين من 9 صباحاً - 6 مساءً</p>
                <a href="tel:+966123456789" class="text-primary hover:underline">+966 12 345 6789</a>
            </div>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">إرسال رسالة</h3>
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">الاسم</label>
                        <input type="text"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">البريد الإلكتروني</label>
                        <input type="email"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-2">الموضوع</label>
                    <input type="text"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-2">الرسالة</label>
                    <textarea rows="5"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"></textarea>
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition-colors">
                    إرسال الرسالة
                </button>
            </form>
        </div>
    </div>
@endsection