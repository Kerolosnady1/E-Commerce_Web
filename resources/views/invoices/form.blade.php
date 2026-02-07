@extends('layouts.app')

@section('title', isset($invoice) ? 'تعديل الفاتورة - نظام ERP' : 'إنشاء فاتورة - نظام ERP')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    {{ isset($invoice) ? 'تعديل فاتورة مبيعات' : 'إنشاء فاتورة مبيعات' }}
                </h1>
                <p class="text-slate-400">أكمل نموذج الفاتورة وأضف المنتجات المطلوبة</p>
            </div>
            <span class="material-symbols-outlined text-5xl text-primary">receipt</span>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-2xl p-8">
            <form action="{{ isset($invoice) ? route('invoices.update', $invoice) : route('invoices.store') }}"
                method="POST" id="invoice-form">
                @csrf
                @if(isset($invoice))
                    @method('PUT')
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-8">
                    <!-- General Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-8 border-b border-border-dark">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-3">العميل</label>
                            <select name="customer_id" required
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                                <option value="">اختر العميل</option>
                                @foreach($customers ?? [] as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', isset($invoice) ? $invoice->customer_id : '') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-3">تاريخ الفاتورة</label>
                            <input type="date" name="issued_date"
                                value="{{ old('issued_date', (isset($invoice) && $invoice->issued_date) ? $invoice->issued_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-3">رقم الفاتورة (اختياري)</label>
                            <input type="text" name="number" value="{{ old('number', $invoice->number ?? '') }}"
                                placeholder="اتركه فارغاً للتوليد التلقائي"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-3">الحالة</label>
                            <select name="status"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                                <option value="pending" {{ old('status', $invoice->status ?? 'pending') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                <option value="paid" {{ old('status', $invoice->status ?? '') == 'paid' ? 'selected' : '' }}>
                                    مدفوعة</option>
                                <option value="overdue" {{ old('status', $invoice->status ?? '') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-3">قالب الطباعة</label>
                            <select name="print_template_id"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none">
                                <option value="">القالب الافتراضي</option>
                                @foreach($templates ?? [] as $template)
                                    <option value="{{ $template->id }}" {{ old('print_template_id', isset($invoice) ? $invoice->print_template_id : '') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="flex items-center gap-3 cursor-pointer p-4 bg-surface-dark/50 border border-border-dark rounded-xl">
                                <input type="checkbox" name="includes_vat" value="1" {{ old('includes_vat', isset($invoice) ? $invoice->includes_vat : true) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded accent-primary cursor-pointer">
                                <div>
                                    <span class="text-white font-semibold">الأسعار تشمل ضريبة القيمة المضافة</span>
                                    <p class="text-slate-400 text-xs mt-1">إذا تم تفعيله، سيتم اعتبار الأسعار المدخلة شاملة
                                        للضريبة بالفعل</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">inventory_2</span>
                            منتجات الفاتورة
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-right" id="items-table">
                                <thead>
                                    <tr class="text-slate-400 text-xs">
                                        <th class="pb-4 pr-2">المنتج</th>
                                        <th class="pb-4">الكمية</th>
                                        <th class="pb-4">سعر الوحدة</th>
                                        <th class="pb-4">المجموع</th>
                                        <th class="pb-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-dark" id="items-container">
                                    <!-- Dynamic Rows -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" onclick="addItem()"
                            class="mt-4 flex items-center gap-2 text-primary hover:text-primary/80 font-bold transition-colors">
                            <span class="material-symbols-outlined">add_circle</span>
                            إضافة منتج
                        </button>
                    </div>

                    <!-- Totals and Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-border-dark">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-3">ملاحظات</label>
                            <textarea name="notes" rows="4"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-xl text-white focus:border-primary outline-none transition-all resize-none">{{ old('notes', $invoice->notes ?? '') }}</textarea>
                        </div>

                        <div class="bg-surface-dark rounded-2xl p-6 border border-border-dark space-y-4">
                            <div class="flex justify-between text-slate-400">
                                <span>المجموع الفرعي</span>
                                <span id="subtotal">0.00 ر.س</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span id="tax-label">ضريبة القيمة المضافة</span>
                                <span id="tax-total">0.00 ر.س</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold border-t border-border-dark pt-4">
                                <span class="text-white">الإجمالي النهائي</span>
                                <span class="text-primary" id="grand-total">0.00 ر.س</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                            class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white font-semibold px-8 py-3 rounded-xl transition-all shadow-lg">
                            <span class="material-symbols-outlined">save</span>
                            حفظ الفاتورة
                        </button>
                        <a href="{{ route('invoices.index') }}"
                            class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 text-slate-100 font-semibold px-8 py-3 rounded-xl transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemsCount = 0;
        const products = @json($products ?? []);
        const existingItems = @json(isset($invoice) ? $invoice->items : []);

        function addItem(itemData = null) {
            const rowId = itemsCount++;
            const row = document.createElement('tr');
            row.id = `row-${rowId}`;
            row.innerHTML = `
                                            <td class="py-4 pr-2">
                                                <select name="items[${rowId}][product_id]" required onchange="updateRowPrice(this, ${rowId})"
                                                        class="w-full px-3 py-2 bg-surface-dark border border-border-dark rounded-lg text-white outline-none focus:border-primary">
                                                    <option value="">اختر منتج</option>
                                                    ${products.map(p => `<option value="${p.id}" data-price="${p.price}" data-tax="${p.tax_rate}" ${itemData && itemData.product_id == p.id ? 'selected' : ''}>${p.name} (${p.tax_rate}%)</option>`).join('')}
                                                </select>
                                            </td>
                                            <td class="py-4">
                                                <input type="number" name="items[${rowId}][quantity]" value="${itemData ? itemData.quantity : 1}" min="1" required oninput="calculateTotals()"
                                                       class="w-24 px-3 py-2 bg-surface-dark border border-border-dark rounded-lg text-white outline-none focus:border-primary text-center">
                                            </td>
                                            <td class="py-4">
                                                <input type="number" name="items[${rowId}][unit_price]" step="0.01" value="${itemData ? itemData.unit_price : 0}" required oninput="calculateTotals()"
                                                       class="w-32 px-3 py-2 bg-surface-dark border border-border-dark rounded-lg text-white outline-none focus:border-primary text-center">
                                            </td>
                                            <td class="py-4 text-white font-bold" id="subtotal-${rowId}">0.00 ر.س</td>
                                            <td class="py-4 text-left">
                                                <button type="button" onclick="removeRow(${rowId})" class="text-red-500 hover:text-red-400">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </td>
                                        `;
            document.getElementById('items-container').appendChild(row);
            calculateTotals();
        }

        function removeRow(id) {
            document.getElementById(`row-${id}`).remove();
            calculateTotals();
        }

        function updateRowPrice(select, id) {
            const price = select.options[select.selectedIndex].dataset.price || 0;
            document.querySelector(`input[name="items[${id}][unit_price]"]`).value = price;
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            let taxTotal = 0;
            const rows = document.querySelectorAll('#items-container tr');
            const includesVat = document.querySelector('input[name="includes_vat"]').checked;

            rows.forEach(row => {
                const id = row.id.split('-')[1];
                const productSelect = row.querySelector(`select[name="items[${id}][product_id]"]`);
                const qtyInput = row.querySelector(`input[name="items[${id}][quantity]"]`);
                const priceInput = row.querySelector(`input[name="items[${id}][unit_price]"]`);

                if (qtyInput && priceInput && productSelect.selectedIndex > 0) {
                    const qty = parseFloat(qtyInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const productId = productSelect.value;
                    const product = products.find(p => p.id == productId);
                    const taxRate = product ? parseFloat(product.tax_rate) : 0;
                    const rowSubtotal = qty * price;

                    let rowTax = 0;
                    if (includesVat) {
                        rowTax = rowSubtotal - (rowSubtotal / (1 + (taxRate / 100)));
                    } else {
                        rowTax = rowSubtotal * (taxRate / 100);
                    }

                    const subtotalEl = document.getElementById(`subtotal-${id}`);
                    if (subtotalEl) {
                        subtotalEl.textContent = rowSubtotal.toFixed(2) + ' ر.س';
                    }

                    subtotal += rowSubtotal;
                    taxTotal += rowTax;
                }
            });

            const grandTotal = includesVat ? subtotal : (subtotal + taxTotal);
            const netSubtotal = includesVat ? (subtotal - taxTotal) : subtotal;

            document.getElementById('subtotal').textContent = netSubtotal.toFixed(2) + ' ر.س';
            document.getElementById('tax-total').textContent = taxTotal.toFixed(2) + ' ر.س';
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2) + ' ر.س';

            // Update labels based on VAT inclusion
            const taxLabel = document.getElementById('tax-label');
            if (taxLabel) {
                taxLabel.textContent = includesVat ? 'ضريبة القيمة المضافة (ضمن السعر)' : 'ضريبة القيمة المضافة';
            }
        }

        // Add listener for VAT checkbox
        document.querySelector('input[name="includes_vat"]').addEventListener('change', calculateTotals);

        // Initialize items
        if (existingItems.length > 0) {
            existingItems.forEach(item => addItem(item));
        } else {
            addItem();
        }
    </script>
@endsection