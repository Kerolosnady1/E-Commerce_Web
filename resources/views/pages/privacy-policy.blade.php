@extends('layouts.app')

@section('title', 'سياسة الخصوصية - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">سياسة الخصوصية</h1>
            <p class="text-slate-400 mt-2">آخر تحديث: 1 يناير 2026</p>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-xl p-8 prose prose-invert max-w-none">
            <h2 class="text-xl font-semibold text-white mb-4">1. مقدمة</h2>
            <p class="text-slate-300 mb-6">نحن في نظام ERP نلتزم بحماية خصوصيتك وبياناتك الشخصية. توضح هذه السياسة كيفية جمع
                واستخدام وحماية معلوماتك.</p>

            <h2 class="text-xl font-semibold text-white mb-4">2. البيانات التي نجمعها</h2>
            <ul class="text-slate-300 mb-6 list-disc list-inside space-y-2">
                <li>معلومات الحساب: الاسم، البريد الإلكتروني، رقم الهاتف</li>
                <li>بيانات الاستخدام: سجلات الدخول، النشاط داخل النظام</li>
                <li>معلومات الجهاز: نوع المتصفح، عنوان IP</li>
            </ul>

            <h2 class="text-xl font-semibold text-white mb-4">3. كيف نستخدم بياناتك</h2>
            <p class="text-slate-300 mb-6">نستخدم بياناتك لتقديم خدماتنا وتحسينها، وضمان أمان حسابك، والتواصل معك بخصوص
                تحديثات النظام.</p>

            <h2 class="text-xl font-semibold text-white mb-4">4. حماية البيانات</h2>
            <p class="text-slate-300 mb-6">نتخذ إجراءات أمنية مشددة لحماية بياناتك من الوصول غير المصرح به أو التسريب.</p>

            <h2 class="text-xl font-semibold text-white mb-4">5. حقوقك</h2>
            <p class="text-slate-300">لديك الحق في الوصول إلى بياناتك وتعديلها أو حذفها. تواصل معنا عبر الدعم الفني.</p>
        </div>
    </div>
@endsection