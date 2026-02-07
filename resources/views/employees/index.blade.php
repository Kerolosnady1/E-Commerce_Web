@extends('layouts.app')

@section('title', 'إدارة الموظفين - نظام ERP')

@section('content')
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-blue-600 text-white p-6 -mx-8 -mt-8 mb-6 rounded-b-2xl">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-white">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-3xl font-bold">إدارة الموظفين</h1>
                </div>
                <p class="text-white/90 text-sm">عرض وإدارة بيانات الموظفين</p>
            </div>
            <span class="material-symbols-outlined text-6xl text-white/20">group</span>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-card-dark p-6 rounded-xl border border-border-dark mb-6">
        <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-400 mb-2">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن موظف..."
                    class="w-full px-4 py-2.5 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">الحالة</label>
                <select name="status"
                    class="w-full px-4 py-2.5 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined text-sm align-middle ml-2">search</span>
                    بحث
                </button>
                <a href="{{ route('employees') }}"
                    class="px-6 py-2.5 border border-slate-600 text-slate-300 rounded-lg font-medium hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-sm align-middle ml-2">refresh</span>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Employees Table -->
    <div class="bg-card-dark rounded-xl border border-border-dark overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-dark border-b border-border-dark">
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-300">الموظف</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-300">البريد الإلكتروني</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-300">الدور الوظيفي</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-300">تاريخ الانضمام</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-300">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-300">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $query = App\Models\User::query();
                        if (request('search')) {
                            $query->where('name', 'like', '%' . request('search') . '%')
                                ->orWhere('email', 'like', '%' . request('search') . '%');
                        }
                        if (request('status') == 'active') {
                            $query->where('is_active', true);
                        } elseif (request('status') == 'inactive') {
                            $query->where('is_active', false);
                        }
                        $users = $query->paginate(10);
                    @endphp

                    @forelse($users as $user)
                        <tr class="border-b border-border-dark hover:bg-primary/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-bold">{{ $user->name }}</p>
                                        <p class="text-slate-400 text-xs">{{ '@' . Str::slug($user->name) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 text-sm">{{ $user->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full bg-blue-900/30 text-blue-300 text-xs font-bold">
                                    {{ $user->role ?? 'مستخدم' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                @if($user->is_active ?? true)
                                    <span class="text-xs font-medium text-green-400">نشط</span>
                                @else
                                    <span class="text-xs font-medium text-red-400">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="text-primary hover:text-primary/80 text-sm font-medium">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-6xl text-slate-600 mb-3 block">inbox</span>
                                <p class="text-lg font-medium">لا يوجد موظفين</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-border-dark">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection