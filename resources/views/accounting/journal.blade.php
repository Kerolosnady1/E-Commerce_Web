@extends('layouts.app')

@section('title', 'دفتر القيود - نظام ERP')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('accounting') }}" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-2xl font-bold text-white">دفتر القيود</h1>
            </div>
            <p class="text-slate-400">سجل كافة العمليات المالية بنظام القيد المزدوج</p>
        </div>
        <a href="{{ route('accounting.journal.create') }}"
            class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            قيد جديد
        </a>
    </div>

    <div class="space-y-6">
        @forelse($entries as $entry)
            <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
                <div class="bg-surface-dark px-6 py-3 flex justify-between items-center border-b border-border-dark">
                    <div class="flex items-center gap-4">
                        <span
                            class="px-2 py-1 bg-primary/20 text-primary text-xs font-mono rounded">#{{ str_pad($entry->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-white font-medium text-sm">{{ $entry->entry_date->format('Y-m-d') }}</span>
                    </div>
                    @if($entry->reference)
                        <span class="text-slate-500 text-xs">المرجع: {{ $entry->reference }}</span>
                    @endif
                </div>
                <div class="p-0">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="text-slate-500 text-xs border-b border-border-dark bg-slate-800/30">
                                <th class="py-2 px-6">الحساب</th>
                                <th class="py-2 px-6 w-32 border-r border-border-dark">مدين</th>
                                <th class="py-2 px-6 w-32 border-r border-border-dark">دائن</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entry->items as $item)
                                <tr class="text-sm border-b border-border-dark/50 last:border-0">
                                    <td class="py-3 px-6">
                                        <div class="flex flex-col">
                                            <span class="text-white">{{ $item->account->name }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono">{{ $item->account->code }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 border-r border-border-dark/50 text-green-400 font-bold overflow-hidden">
                                        {{ $item->debit > 0 ? number_format($item->debit, 2) : '' }}
                                    </td>
                                    <td class="py-3 px-6 border-r border-border-dark/50 text-red-400 font-bold overflow-hidden">
                                        {{ $item->credit > 0 ? number_format($item->credit, 2) : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($entry->description)
                    <div class="px-6 py-3 bg-slate-800/20 text-xs text-slate-400 border-t border-border-dark italic">
                        {{ $entry->description }}
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-card-dark border border-border-dark rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-slate-600 text-6xl mb-4">book_2</span>
                <p class="text-slate-400">لا توجد قيود مسجلة بعد</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $entries->links() }}
    </div>
@endsection