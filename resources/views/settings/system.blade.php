@extends('layouts.app')

@section('title', 'إعدادات النظام - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">إعدادات النظام</h1>
        <p class="text-slate-400 mt-1">إعدادات النظام والتخزين</p>
    </div>

    <!-- Settings Navigation -->
    <div class="flex flex-wrap gap-4 mb-6">
        <a href="{{ route('settings.general') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">عامة</a>
        <a href="{{ route('settings.system') }}" class="px-4 py-2 bg-primary text-white rounded-lg">النظام</a>
        <a href="{{ route('settings.taxes') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">الضرائب</a>
        <a href="{{ route('settings.print-templates') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">قوالب الطباعة</a>
        <a href="{{ route('settings.locale-time') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">اللغة والوقت</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">إدارة التخزين</h2>

            <form action="{{ route('settings.system.update') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Storage Quota Input -->
                <div>
                    <label class="block text-slate-400 text-sm mb-2">سعة التخزين المسموح بها (MB)</label>
                    <input type="number" name="storage_quota_mb" value="{{ $settings->storage_quota_mb ?? 10240 }}"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                    <p class="text-xs text-slate-500 mt-2">حدد الحد الأقصى لمساحة تخزين الملفات والصور للنظام.</p>
                </div>

                <!-- Storage Usage Progress -->
                <div>
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-slate-400">المساحة المستخدمة حالياً</span>
                        <span class="text-white font-bold">{{ number_format($settings->storage_used_mb ?? 0, 2) }} MB /
                            {{ number_format($settings->storage_quota_mb ?? 10240, 0) }} MB</span>
                    </div>
                    <div class="w-full bg-surface-dark rounded-full h-3">
                        @php
                            $quota = $settings->storage_quota_mb ?? 10240;
                            $used = $settings->storage_used_mb ?? 0;
                            $percentage = $quota > 0 ? ($used / $quota) * 100 : 0;
                        @endphp
                        <div class="bg-primary h-3 rounded-full transition-all" style="width: {{ min($percentage, 100) }}%">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors font-bold">
                    تحديث السعة
                </button>
            </form>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">معلومات النظام</h2>

            <!-- System Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-border-dark">
                <div class="bg-surface-dark rounded-lg p-4">
                    <p class="text-slate-400 text-sm">قاعدة البيانات</p>
                    <p class="text-white font-bold mt-1">SQLite</p>
                </div>
                <div class="bg-surface-dark rounded-lg p-4">
                    <p class="text-slate-400 text-sm">إصدار Laravel</p>
                    <p class="text-white font-bold mt-1">{{ app()->version() }}</p>
                </div>
                <div class="bg-surface-dark rounded-lg p-4">
                    <p class="text-slate-400 text-sm">إصدار PHP</p>
                    <p class="text-white font-bold mt-1">{{ PHP_VERSION }}</p>
                </div>
                <div class="bg-surface-dark rounded-lg p-4">
                    <p class="text-slate-400 text-sm">المنطقة الزمنية</p>
                    <p class="text-white font-bold mt-1">{{ $settings->timezone ?? 'Asia/Riyadh' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection