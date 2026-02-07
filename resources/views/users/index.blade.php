@extends('layouts.app')

@section('title', 'إدارة المستخدمين - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة المستخدمين</h1>
            <p class="text-sm text-slate-400 mt-1">إدارة حسابات المستخدمين والصلاحيات</p>
        </div>
        <a href="{{ route('users.create') }}"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">person_add</span>
            إضافة مستخدم جديد
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">إجمالي المستخدمين</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $users->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">المديرين</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ $users->where('role', 'admin')->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">الموظفين</p>
            <p class="text-2xl font-bold text-blue-400 mt-1">{{ $users->where('role', 'employee')->count() }}</p>
        </div>
        <div class="bg-card-dark p-4 rounded-xl border border-border-dark">
            <p class="text-sm text-slate-400">النشطين</p>
            <p class="text-2xl font-bold text-green-400 mt-1">{{ $users->where('is_active', true)->count() }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-card-dark rounded-xl border border-border-dark overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-surface-dark">
                <tr>
                    <th class="px-4 py-3 text-sm text-slate-400">المستخدم</th>
                    <th class="px-4 py-3 text-sm text-slate-400">البريد الإلكتروني</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الدور</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الحالة</th>
                    <th class="px-4 py-3 text-sm text-slate-400">آخر دخول</th>
                    <th class="px-4 py-3 text-sm text-slate-400">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t border-border-dark hover:bg-primary/5 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center text-primary font-bold">
                                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-white">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-400">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="text-xs px-2 py-1 rounded {{ $user->role == 'admin' ? 'bg-primary/10 text-primary' : 'bg-slate-700 text-slate-300' }}">
                                {{ $user->role == 'admin' ? 'مدير' : 'موظف' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->is_active ?? true)
                                <span
                                    class="text-xs bg-green-500/10 text-green-400 px-2 py-1 rounded border border-green-500/20">نشط</span>
                            @else
                                <span
                                    class="text-xs bg-slate-500/10 text-slate-400 px-2 py-1 rounded border border-slate-500/20">غير
                                    نشط</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $user->last_login_at ?? 'لم يسجل دخول' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-primary hover:underline">تعديل</a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                        onsubmit="return confirm('حذف المستخدم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline">حذف</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">لا يوجد مستخدمين</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
@endsection