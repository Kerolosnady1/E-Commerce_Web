@extends('layouts.app')

@section('title', 'دليل الحسابات - نظام ERP')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('accounting') }}" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-2xl font-bold text-white">دليل الحسابات</h1>
            </div>
            <p class="text-slate-400">هيكل الحسابات المالية للنظام</p>
        </div>
        <a href="{{ route('accounting.coa.create') }}"
            class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            حساب جديد
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        @php
            $types = [
                'asset' => ['label' => 'الأصول', 'color' => 'green'],
                'liability' => ['label' => 'الالتزامات', 'color' => 'red'],
                'equity' => ['label' => 'حقوق الملكية', 'color' => 'blue'],
                'revenue' => ['label' => 'الإيرادات', 'color' => 'indigo'],
                'expense' => ['label' => 'المصروفات', 'color' => 'amber'],
            ];
        @endphp

        @foreach($types as $type => $info)
            <div class="bg-card-dark border border-border-dark rounded-xl p-4">
                <p class="text-xs text-slate-400 mb-1">{{ $info['label'] }}</p>
                <p class="text-lg font-bold text-{{ $info['color'] }}-400">
                    {{ number_format($accounts->where('type', $type)->sum('balance'), 2) }} ر.س
                </p>
            </div>
        @endforeach
    </div>

    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead>
                <tr class="bg-surface-dark border-b border-border-dark text-slate-400 text-sm">
                    <th class="py-4 px-6">رمز الحساب</th>
                    <th class="py-4 px-6">اسم الحساب</th>
                    <th class="py-4 px-6">النوع</th>
                    <th class="py-4 px-6">الرصيد الحالي</th>
                    <th class="py-4 px-6">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark">
                @foreach($accounts as $account)
                    <tr class="hover:bg-primary/5 transition-colors">
                        <td class="py-4 px-6 font-mono text-sm text-primary">{{ $account->code }}</td>
                        <td class="py-4 px-6 text-white font-medium">{{ $account->name }}</td>
                        <td class="py-4 px-6">
                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($account->type == 'asset') bg-green-500/10 text-green-400
                                                @elseif($account->type == 'liability') bg-red-500/10 text-red-400
                                                @elseif($account->type == 'equity') bg-blue-500/10 text-blue-400
                                                @elseif($account->type == 'revenue') bg-indigo-500/10 text-indigo-400
                                                @else bg-amber-500/10 text-amber-400
                                                @endif">
                                {{ $types[$account->type]['label'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-white font-bold">{{ number_format($account->balance, 2) }} ر.س</td>
                        <td class="py-4 px-6">
                            @if($account->is_active)
                                <span class="flex items-center gap-1 text-green-400 text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                    نشط
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-slate-500 text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection