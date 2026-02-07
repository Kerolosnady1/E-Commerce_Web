@extends('layouts.app')

@section('title', 'المساعدة والدعم - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 text-center">
            <div class="inline-flex p-4 bg-primary/10 rounded-full mb-4">
                <span class="material-symbols-outlined text-primary text-4xl">help</span>
            </div>
            <h1 class="text-3xl font-bold text-white">مركز المساعدة</h1>
            <p class="text-slate-400 mt-2">كيف يمكننا مساعدتك اليوم؟</p>
        </div>

        <!-- Search -->
        <div class="mb-8">
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" placeholder="ابحث في مركز المساعدة..."
                    class="w-full pr-12 pl-4 py-4 bg-card-dark border border-border-dark rounded-2xl text-white focus:border-primary outline-none">
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="#getting-started"
                class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
                <div
                    class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                    <span class="material-symbols-outlined text-blue-500 group-hover:text-white">rocket_launch</span>
                </div>
                <h3 class="text-white font-bold mb-2">البدء السريع</h3>
                <p class="text-slate-400 text-sm">تعرف على أساسيات النظام</p>
            </a>
            <a href="#faq"
                class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
                <div
                    class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-500 transition-colors">
                    <span class="material-symbols-outlined text-green-500 group-hover:text-white">quiz</span>
                </div>
                <h3 class="text-white font-bold mb-2">الأسئلة الشائعة</h3>
                <p class="text-slate-400 text-sm">إجابات على الأسئلة المتكررة</p>
            </a>
            <a href="{{ route('support') }}"
                class="bg-card-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all group">
                <div
                    class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-500 transition-colors">
                    <span class="material-symbols-outlined text-purple-500 group-hover:text-white">support_agent</span>
                </div>
                <h3 class="text-white font-bold mb-2">تواصل معنا</h3>
                <p class="text-slate-400 text-sm">فريق الدعم جاهز لمساعدتك</p>
            </a>
        </div>

        <!-- Getting Started Section -->
        <div id="getting-started" class="bg-card-dark border border-border-dark rounded-2xl p-8 mb-8">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">rocket_launch</span>
                البدء السريع
            </h2>
            <div class="space-y-4">
                <div class="p-4 bg-surface-dark rounded-xl">
                    <h3 class="text-white font-bold mb-2">1. إعداد الحساب</h3>
                    <p class="text-slate-400 text-sm">ابدأ بتخصيص إعدادات شركتك من صفحة الإعدادات العامة.</p>
                </div>
                <div class="p-4 bg-surface-dark rounded-xl">
                    <h3 class="text-white font-bold mb-2">2. إضافة المنتجات</h3>
                    <p class="text-slate-400 text-sm">أضف منتجاتك وصنفها حسب الفئات لسهولة الإدارة.</p>
                </div>
                <div class="p-4 bg-surface-dark rounded-xl">
                    <h3 class="text-white font-bold mb-2">3. إدارة العملاء</h3>
                    <p class="text-slate-400 text-sm">أضف بيانات عملائك لتتبع المبيعات والفواتير.</p>
                </div>
                <div class="p-4 bg-surface-dark rounded-xl">
                    <h3 class="text-white font-bold mb-2">4. إنشاء الفواتير</h3>
                    <p class="text-slate-400 text-sm">ابدأ بإنشاء فواتير المبيعات وتتبع المدفوعات.</p>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">quiz</span>
                الأسئلة الشائعة
            </h2>
            <div class="space-y-4">
                <details class="p-4 bg-surface-dark rounded-xl group">
                    <summary class="text-white font-bold cursor-pointer flex items-center justify-between">
                        كيف أضيف منتج جديد؟
                        <span
                            class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="text-slate-400 text-sm mt-3">اذهب إلى صفحة المنتجات واضغط على زر "إضافة منتج جديد"، ثم أدخل
                        بيانات المنتج واحفظ.</p>
                </details>
                <details class="p-4 bg-surface-dark rounded-xl group">
                    <summary class="text-white font-bold cursor-pointer flex items-center justify-between">
                        كيف أطبع فاتورة؟
                        <span
                            class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="text-slate-400 text-sm mt-3">افتح الفاتورة المطلوبة واضغط على زر "طباعة" في أعلى الصفحة.</p>
                </details>
                <details class="p-4 bg-surface-dark rounded-xl group">
                    <summary class="text-white font-bold cursor-pointer flex items-center justify-between">
                        كيف أغير إعدادات الضريبة؟
                        <span
                            class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="text-slate-400 text-sm mt-3">اذهب إلى الإعدادات > الضرائب، وقم بتعديل نسبة الضريبة حسب
                        احتياجاتك.</p>
                </details>
            </div>
        </div>
    </div>
@endsection