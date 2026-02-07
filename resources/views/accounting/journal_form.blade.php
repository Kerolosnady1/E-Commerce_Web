@extends('layouts.app')

@section('title', 'قيد جديد - نظام ERP')

@section('content')
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('accounting.journal') }}" class="text-slate-400 hover:text-white">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-white">تسجيل قيد محاسبي جديد</h1>
        </div>
        <p class="text-slate-400">نظام القيد المزدوج - تأكد من تساوي المدين والدائن</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('accounting.journal.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Entry Details -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-card-dark border border-border-dark rounded-2xl p-6">
                    <h3 class="text-white font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        تفاصيل القيد
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">تاريخ القيد</label>
                            <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" required
                                class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">الوصف / البيان</label>
                            <textarea name="description" rows="3" placeholder="وصف موجز للعملية المالية..."
                                class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">المرجع (اختياري)</label>
                            <input type="text" name="reference" placeholder="رقم فاتورة، شيك، إلخ..."
                                class="w-full px-4 py-2 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                        </div>
                    </div>
                </div>

                <!-- Balance Summary -->
                <div class="bg-card-dark border border-border-dark rounded-2xl p-6">
                    <h3 class="text-white font-bold mb-4">ملخص القيد</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">إجمالي المدين:</span>
                            <span id="total-debit" class="text-green-400 font-bold">0.00 ر.س</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">إجمالي الدائن:</span>
                            <span id="total-credit" class="text-red-400 font-bold">0.00 ر.س</span>
                        </div>
                        <div class="pt-3 border-t border-border-dark flex justify-between text-sm">
                            <span class="text-slate-400">الفرق:</span>
                            <span id="difference" class="text-white font-bold">0.00 ر.س</span>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" disabled
                        class="w-full mt-6 py-3 bg-primary disabled:bg-slate-700 disabled:cursor-not-allowed text-white rounded-lg font-bold transition-all">
                        حفظ القيد
                    </button>
                </div>
            </div>

            <!-- Entry Lines -->
            <div class="lg:col-span-2">
                <div class="bg-card-dark border border-border-dark rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-border-dark flex justify-between items-center">
                        <h3 class="text-white font-bold">بنود القيد</h3>
                        <button type="button" onclick="addRow()"
                            class="text-primary hover:text-primary/80 flex items-center gap-1 text-sm font-medium">
                            <span class="material-symbols-outlined text-sm">add_circle</span>
                            إضافة سطر
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="text-slate-500 text-xs bg-slate-800/30">
                                    <th class="py-3 px-6">الحساب</th>
                                    <th class="py-3 px-6 w-32">مدين</th>
                                    <th class="py-3 px-6 w-32">دائن</th>
                                    <th class="py-3 px-2 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="lines-container" class="divide-y divide-border-dark">
                                <!-- Initial Rows -->
                                @for($i = 0; $i < 2; $i++)
                                    <tr class="entry-row">
                                        <td class="py-4 px-6">
                                            <select name="items[{{ $i }}][account_id]" required
                                                class="w-full bg-surface-dark border border-border-dark rounded-lg text-white text-sm focus:border-primary outline-none appearance-none px-4 py-2">
                                                <option value="">اختر الحساب...</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-4 px-6">
                                            <input type="number" step="0.01" name="items[{{ $i }}][debit]" value="0"
                                                oninput="calculateBalances()"
                                                class="w-full bg-surface-dark border border-border-dark rounded-lg text-white text-sm focus:border-primary outline-none px-3 py-2 text-center debit-input">
                                        </td>
                                        <td class="py-4 px-6">
                                            <input type="number" step="0.01" name="items[{{ $i }}][credit]" value="0"
                                                oninput="calculateBalances()"
                                                class="w-full bg-surface-dark border border-border-dark rounded-lg text-white text-sm focus:border-primary outline-none px-3 py-2 text-center credit-input">
                                        </td>
                                        <td class="py-4 px-2">
                                            <button type="button" onclick="removeRow(this)"
                                                class="text-slate-500 hover:text-red-400">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        let rowCount = 2;

        function addRow() {
            const container = document.getElementById('lines-container');
            const row = document.createElement('tr');
            row.className = 'entry-row';
            row.innerHTML = `
                    <td class="py-4 px-6">
                        <select name="items[${rowCount}][account_id]" required
                            class="w-full bg-surface-dark border border-border-dark rounded-lg text-white text-sm focus:border-primary outline-none appearance-none px-4 py-2">
                            <option value="">اختر الحساب...</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-4 px-6">
                        <input type="number" step="0.01" name="items[${rowCount}][debit]" value="0" oninput="calculateBalances()"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg text-white text-sm focus:border-primary outline-none px-3 py-2 text-center debit-input">
                    </td>
                    <td class="py-4 px-6">
                        <input type="number" step="0.01" name="items[${rowCount}][credit]" value="0" oninput="calculateBalances()"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg text-white text-sm focus:border-primary outline-none px-3 py-2 text-center credit-input">
                    </td>
                    <td class="py-4 px-2">
                        <button type="button" onclick="removeRow(this)" class="text-slate-500 hover:text-red-400">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </td>
                `;
            container.appendChild(row);
            rowCount++;
            calculateBalances();
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.entry-row');
            if (rows.length > 2) {
                btn.closest('tr').remove();
                calculateBalances();
            } else {
                alert('يجب وجود بندين على الأقل في القيد المحاسبي');
            }
        }

        function calculateBalances() {
            let totalDebit = 0;
            let totalCredit = 0;

            document.querySelectorAll('.debit-input').forEach(input => {
                totalDebit += parseFloat(input.value) || 0;
            });

            document.querySelectorAll('.credit-input').forEach(input => {
                totalCredit += parseFloat(input.value) || 0;
            });

            const diff = Math.abs(totalDebit - totalCredit);

            document.getElementById('total-debit').textContent = totalDebit.toFixed(2) + ' ر.س';
            document.getElementById('total-credit').textContent = totalCredit.toFixed(2) + ' ر.س';
            document.getElementById('difference').textContent = diff.toFixed(2) + ' ر.س';

            const submitBtn = document.getElementById('submit-btn');
            const diffEl = document.getElementById('difference');

            if (diff < 0.01 && (totalDebit > 0 || totalCredit > 0)) {
                submitBtn.disabled = false;
                diffEl.classList.remove('text-red-400');
                diffEl.classList.add('text-green-400');
            } else {
                submitBtn.disabled = true;
                diffEl.classList.remove('text-green-400');
                diffEl.classList.add('text-red-400');
            }
        }

        // Initialize
        calculateBalances();
    </script>
@endsection