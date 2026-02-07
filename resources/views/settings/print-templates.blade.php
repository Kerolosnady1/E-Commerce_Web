@extends('layouts.app')

@section('title', 'قوالب الطباعة - نظام ERP')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">قوالب الطباعة</h1>
            <p class="text-slate-400 mt-1">تخصيص مظهر الفواتير والإيصالات</p>
        </div>
        <button onclick="openCreateModal()"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            إضافة قالب جديد
        </button>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates ?? [] as $template)
            <div
                class="bg-card-dark border border-border-dark rounded-2xl overflow-hidden hover:border-primary/50 transition-all group">
                <!-- Preview -->
                <div class="aspect-[3/4] bg-white p-4 relative group-hover:opacity-90 transition-opacity cursor-pointer"
                    onclick="openEditModal({{ json_encode($template) }})">
                    <div class="h-full border-2 border-dashed border-slate-200 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-300 text-5xl">receipt_long</span>
                    </div>
                    @if($template?->is_default)
                        <span class="absolute top-2 right-2 bg-primary text-white text-xs px-2 py-1 rounded">الافتراضي</span>
                    @endif
                    <div
                        class="absolute inset-0 bg-primary/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-bold bg-primary px-4 py-2 rounded-lg">تعديل التنسيق</span>
                    </div>
                </div>
                <!-- Info -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-white font-bold">{{ $template?->name }}</h3>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded">{{ $template?->style }}</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-4">نوع القالب: {{ $template?->template_type }}</p>
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ json_encode($template) }})"
                            class="flex-1 py-2 bg-primary/10 hover:bg-primary/20 text-primary text-sm rounded-lg transition-colors">
                            تعديل
                        </button>
                        @if(!($template?->is_default))
                            <form action="{{ route('settings.print-templates.destroy', $template) }}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذا القالب؟')" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 text-sm rounded-lg transition-colors">
                                    حذف
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-card-dark border border-dashed border-border-dark rounded-2xl">
                <span class="material-symbols-outlined text-slate-600 text-6xl mb-4">style</span>
                <p class="text-slate-400">لا توجد قوالب طباعة مخصصة حالياً</p>
                <button onclick="openCreateModal()" class="text-primary mt-2 hover:underline">إضافة قالبك الأول</button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    <div id="templateModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-card-dark border border-border-dark rounded-2xl w-full max-w-xl overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-border-dark flex items-center justify-between">
                <h2 id="modalTitle" class="text-xl font-bold text-white">إضافة قالب جديد</h2>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="templateForm" method="POST" class="p-6 space-y-4">
                @csrf
                <div id="methodField"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-bold mb-2 uppercase">اسم القالب</label>
                        <input type="text" name="name" id="template_name" required
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-bold mb-2 uppercase">نوع المستند</label>
                        <select name="template_type" id="template_type" required
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none">
                            <option value="sales_invoice">فاتورة مبيعات</option>
                            <option value="purchase_order">أمر شراء</option>
                            <option value="inventory_report">تقرير مخزون</option>
                            <option value="customer_statement">كشف حساب عميل</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-bold mb-2 uppercase">نمط التصميم</label>
                        <select name="style" id="template_style" required
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none">
                            <option value="standard">قياسي (Standard)</option>
                            <option value="thermal">حراري (Thermal)</option>
                            <option value="minimal">بسيط (Minimal)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-bold mb-2 uppercase">عنوان الترويسة</label>
                        <input type="text" name="header_title" id="template_header" placeholder="مثال: فاتورة ضريبية"
                            class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-2.5 text-white focus:border-primary outline-none">
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <label
                        class="flex items-center gap-3 p-3 bg-surface-dark/50 border border-border-dark rounded-xl cursor-pointer hover:border-primary/30 transition-all">
                        <input type="checkbox" name="is_default" id="template_default" value="1"
                            class="w-5 h-5 rounded border-slate-700 bg-slate-800 text-primary focus:ring-primary focus:ring-offset-slate-900">
                        <div>
                            <p class="text-white text-sm font-bold">تعيين كقالب افتراضي</p>
                            <p class="text-slate-500 text-xs">سيتم استخدام هذا القالب تلقائياً لهذا النوع من المستندات.</p>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 p-3 bg-surface-dark/50 border border-border-dark rounded-xl cursor-pointer hover:border-primary/30 transition-all">
                        <input type="checkbox" name="show_qr_code" id="template_qr" value="1"
                            class="w-5 h-5 rounded border-slate-700 bg-slate-800 text-primary focus:ring-primary focus:ring-offset-slate-900">
                        <div>
                            <p class="text-white text-sm font-bold">إظهار رمز QR</p>
                            <p class="text-slate-500 text-xs">عرض رمز الاستجابة السريعة (مطلوب للفواتير الضريبية المبسطة).
                            </p>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 p-3 bg-surface-dark/50 border border-border-dark rounded-xl cursor-pointer hover:border-primary/30 transition-all">
                        <input type="checkbox" name="show_signature" id="template_signature" value="1"
                            class="w-5 h-5 rounded border-slate-700 bg-slate-800 text-primary focus:ring-primary focus:ring-offset-slate-900">
                        <div>
                            <p class="text-white text-sm font-bold">إظهار التوقيع والختم</p>
                            <p class="text-slate-500 text-xs">عرض مساحة للتوقيع والختم أسفل المستند.</p>
                        </div>
                    </label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl transition-all font-bold">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="flex-[2] py-3 bg-primary hover:bg-primary/90 text-white rounded-xl shadow-lg shadow-primary/20 transition-all font-bold">
                        حفظ القالب
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('templateModal');
        const form = document.getElementById('templateForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');

        function openCreateModal() {
            modalTitle.innerText = 'إضافة قالب جديد';
            form.action = "{{ route('settings.print-templates.store') }}";
            methodField.innerHTML = '';

            // Reset form
            form.reset();

            modal.classList.remove('hidden');
        }

        function openEditModal(template) {
            modalTitle.innerText = 'تعديل القالب: ' + template.name;
            form.action = `/settings/print-templates/${template.id}`;
            methodField.innerHTML = '@method("PUT")';

            // Fill form
            document.getElementById('template_name').value = template.name;
            document.getElementById('template_type').value = template.template_type;
            document.getElementById('template_style').value = template.style;
            document.getElementById('template_header').value = template.header_title || '';
            document.getElementById('template_default').checked = template.is_default;
            document.getElementById('template_qr').checked = template.show_qr_code;
            document.getElementById('template_signature').checked = template.show_signature;

            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Close on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    </script>
@endsection