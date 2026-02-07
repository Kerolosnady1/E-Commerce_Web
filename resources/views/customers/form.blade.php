@extends('layouts.app')

@section('title', isset($customer) ? 'تعديل العميل' : 'عميل جديد')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('customers.index') }}" class="text-primary hover:underline flex items-center gap-1">
                <span class="material-icons text-sm">arrow_forward</span> العودة للعملاء
            </a>
        </div>

        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <h1 class="text-xl font-bold text-white mb-6">{{ isset($customer) ? 'تعديل العميل' : 'إضافة عميل جديد' }}</h1>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-red-300 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST"
                action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}">
                @csrf
                @if(isset($customer))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">اسم العميل *</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">نوع العميل *</label>
                        <select name="customer_type"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                            required>
                            <option value="individual" {{ old('customer_type', $customer->customer_type ?? '') == 'individual' ? 'selected' : '' }}>فرد</option>
                            <option value="company" {{ old('customer_type', $customer->customer_type ?? '') == 'company' ? 'selected' : '' }}>شركة</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-slate-400 text-sm mb-2">الرصيد الافتتاحي</label>
                    <input type="number" name="balance" step="0.01" value="{{ old('balance', $customer->balance ?? 0) }}"
                        class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-border-dark bg-surface-dark text-primary focus:ring-primary">
                        <span class="text-slate-300">العميل نشط</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition-colors">
                        {{ isset($customer) ? 'تحديث' : 'إضافة' }}
                    </button>
                    <a href="{{ route('customers.index') }}"
                        class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection