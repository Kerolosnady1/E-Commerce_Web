@extends('layouts.app')

@section('title', 'سجلات الأمان - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">سجلات الأمان</h1>
        <p class="text-slate-400 mt-1">مراجعة جميع أحداث تسجيل الدخول والنشاط</p>
    </div>

    <!-- Filters -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-4 mb-6">
        <form class="flex flex-wrap items-end gap-4" method="GET">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs text-slate-400 mb-2 block">نوع الحدث</label>
                <select name="type"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-sm text-white focus:border-primary outline-none">
                    <option value="">الكل</option>
                    <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>تسجيل دخول</option>
                    <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>تسجيل خروج</option>
                    <option value="failed" {{ request('type') == 'failed' ? 'selected' : '' }}>محاولة فاشلة</option>
                    <option value="password_change" {{ request('type') == 'password_change' ? 'selected' : '' }}>تغيير كلمة
                        المرور</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="text-xs text-slate-400 mb-2 block">من تاريخ</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-sm text-white focus:border-primary outline-none">
            </div>
            <div class="min-w-[150px]">
                <label class="text-xs text-slate-400 mb-2 block">إلى تاريخ</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-3 py-2 text-sm text-white focus:border-primary outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm transition-colors">تطبيق</button>
                <a href="{{ route('security.logs') }}"
                    class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">إعادة
                    تعيين</a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-card-dark rounded-xl border border-border-dark overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-sm text-slate-400">التاريخ والوقت</th>
                    <th class="px-4 py-3 text-sm text-slate-400">المستخدم</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الحدث</th>
                    <th class="px-4 py-3 text-sm text-slate-400">عنوان IP</th>
                    <th class="px-4 py-3 text-sm text-slate-400">المتصفح</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs ?? [] as $log)
                    <tr class="border-t border-border-dark hover:bg-primary/5 transition-colors">
                        <td class="px-4 py-3 text-sm text-slate-300">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-3 text-white font-medium">{{ $log->user->name ?? 'غير معروف' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400">
                            {{ $log->getActionTypeLabel() }}
                            <div class="text-xs text-slate-500">{{ $log->description }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-400 font-mono">{{ $log->ip_address }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ Str::limit($log->user_agent, 30) }}</td>
                        <td class="px-4 py-3">
                            @if($log->status == 'success')
                                <span
                                    class="text-xs bg-green-500/10 text-green-400 px-2 py-1 rounded border border-green-500/20">نجح</span>
                            @else
                                <span
                                    class="text-xs bg-red-500/10 text-red-400 px-2 py-1 rounded border border-red-500/20">فشل</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                            <span class="material-symbols-outlined text-4xl mb-2 block">history</span>
                            لا توجد سجلات أمان
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($logs) && $logs->hasPages())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif

    <div class="mt-6 text-center">
        <a href="{{ route('security.index') }}" class="text-primary hover:underline">العودة إلى إعدادات الأمان</a>
    </div>
@endsection