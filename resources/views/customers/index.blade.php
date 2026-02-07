@extends('layouts.app')

@section('title', 'العملاء - نظام ERP')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">إدارة العملاء</h1>
            <p class="text-slate-400 mt-1">عرض وإدارة جميع العملاء</p>
        </div>
        <a href="{{ route('customers.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors">
            <span class="material-icons text-sm">add</span>
            عميل جديد
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">إجمالي العملاء</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">أفراد</p>
            <p class="text-2xl font-bold text-blue-400 mt-1">{{ $stats['individual'] }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">شركات</p>
            <p class="text-2xl font-bold text-purple-400 mt-1">{{ $stats['company'] }}</p>
        </div>
        <div class="bg-card-dark border border-border-dark rounded-lg p-4">
            <p class="text-slate-400 text-sm">نشط</p>
            <p class="text-2xl font-bold text-green-400 mt-1">{{ $stats['active'] }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-card-dark border border-border-dark rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم، البريد، الهاتف..."
                    class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
            </div>
            <select name="type"
                class="px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                <option value="">الكل</option>
                <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>أفراد</option>
                <option value="company" {{ request('type') == 'company' ? 'selected' : '' }}>شركات</option>
            </select>
            <select name="status"
                class="px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                <option value="">الحالة</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg">
                <span class="material-icons text-sm">search</span>
            </button>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-surface-dark">
                <tr class="text-slate-400 text-sm">
                    <th class="text-right py-4 px-6">العميل</th>
                    <th class="text-right py-4 px-6">النوع</th>
                    <th class="text-right py-4 px-6">البريد</th>
                    <th class="text-right py-4 px-6">الهاتف</th>
                    <th class="text-right py-4 px-6">الرصيد</th>
                    <th class="text-right py-4 px-6">الحالة</th>
                    <th class="text-right py-4 px-6">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr class="border-t border-border-dark hover:bg-surface-dark/50">
                        <td class="py-4 px-6">
                            <a href="{{ route('customers.show', $customer) }}"
                                class="text-white hover:text-primary">{{ $customer->name }}</a>
                        </td>
                        <td class="py-4 px-6">
                            <span
                                class="px-2 py-1 text-xs rounded {{ $customer->customer_type == 'individual' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                                {{ $customer->customer_type == 'individual' ? 'فرد' : 'شركة' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-400">{{ $customer->email ?: '-' }}</td>
                        <td class="py-4 px-6 text-slate-400">{{ $customer->phone ?: '-' }}</td>
                        <td class="py-4 px-6 {{ $customer->balance >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ number_format($customer->balance, 2) }} ر.س</td>
                        <td class="py-4 px-6">
                            @if($customer->is_active)
                                <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400">نشط</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-slate-500/20 text-slate-400">غير نشط</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('customers.edit', $customer) }}"
                                    class="p-1 text-slate-400 hover:text-primary">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                    onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-red-400">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">لا يوجد عملاء</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $customers->links() }}
    </div>
@endsection