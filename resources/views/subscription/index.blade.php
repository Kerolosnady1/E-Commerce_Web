@extends('layouts.app')

@section('title', 'خطة الاشتراك - نظام ERP')

@section('content')
    <div class="mb-8">
        <a href="{{ route('account') }}" class="text-primary hover:text-blue-300 flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            العودة إلى الحسابات
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white">خطة الاشتراك</h1>
                <p class="text-slate-400 mt-2">إدارة اشتراكك وترقية خطتك</p>
            </div>
            <span class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-full uppercase">
                {{ $subscription->plan ?? 'Professional' }}
            </span>
        </div>
    </div>

    <!-- Current Subscription Card -->
    <div class="bg-surface-dark border border-border-dark rounded-2xl p-8 mb-8">
        <div class="flex items-center justify-between border-b border-border-dark pb-6 mb-6">
            <h2 class="text-white text-2xl font-bold flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-3xl">credit_card</span>
                اشتراكك الحالي
            </h2>
            <span
                class="px-4 py-2 bg-green-500/20 text-green-400 text-sm font-bold rounded-full border border-green-500/30">
                ✓ فعال - يتم التجديد تلقائياً
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Subscription Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center p-4 bg-border-dark rounded-xl">
                    <span class="text-slate-400">تاريخ البدء</span>
                    <span
                        class="text-white font-bold">{{ $subscription->start_date ? \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') : '-' }}</span>
                </div>

                <div class="flex justify-between items-center p-4 bg-border-dark rounded-xl">
                    <span class="text-slate-400">المبلغ الشهري</span>
                    <span class="text-white font-bold text-xl">{{ number_format($subscription->monthly_cost ?? 199) }}
                        ر.س</span>
                </div>

                <!-- Storage Usage Progress -->
                @php
                    $storagePercent = $subscription->storage_used_percent ?? 25;
                    $storageUsed = number_format($subscription->storage_used ?? 12.5, 1);
                    $storageTotal = $subscription->storage_total ?? 50;
                @endphp
                <div class="p-6 bg-border-dark rounded-xl">
                    <div class="flex justify-between mb-3">
                        <span class="text-sm font-medium text-slate-400">استهلاك مساحة التخزين</span>
                        <span class="text-sm font-medium text-white">
                            {{ $storagePercent }}% ({{ $storageUsed }}GB / {{ $storageTotal }}GB)
                        </span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full transition-all" style="width: {{ $storagePercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Upgrade Card -->
            <div
                class="bg-border-dark p-6 rounded-xl flex flex-col justify-center items-center gap-4 text-center border border-white/5 shadow-inner">
                <span class="material-symbols-outlined text-5xl text-primary">auto_awesome</span>
                <div class="flex flex-col gap-2">
                    <p class="text-white font-bold text-lg">هل تحتاج للمزيد؟</p>
                    <p class="text-slate-400 text-sm">قم بترقية حسابك للحصول على مميزات غير محدودة.</p>
                </div>
                <button onclick="document.getElementById('plansSection').scrollIntoView({behavior: 'smooth'})"
                    class="w-full py-3 bg-white text-slate-900 text-sm font-bold rounded-xl hover:bg-slate-200 transition-colors shadow-lg">
                    ترقية الاشتراك الآن
                </button>
            </div>
        </div>
    </div>

    <!-- Available Plans -->
    @php
        $currentPlanId = strtolower($subscription->plan ?? 'pro');
    @endphp

    <div class="mb-8" id="plansSection">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">workspace_premium</span>
            الخطط المتاحة
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($plans as $plan)
                <div
                    class="bg-surface-dark border border-border-dark rounded-2xl p-6 hover:border-primary transition-all {{ $plan->slug == $currentPlanId ? 'ring-2 ring-primary border-primary' : '' }}">
                    @if($plan->is_popular)
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <span class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-full uppercase">الأكثر
                                شعبية</span>
                        </div>
                    @endif
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $plan->name_ar }}</h3>
                        <p class="text-slate-400 text-sm mb-4 uppercase">{{ $plan->name_en }}</p>
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl font-bold text-white">{{ number_format($plan->price_monthly) }}</span>
                            <span class="text-slate-400 text-sm">{{ $plan->currency }} / شهرياً</span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center gap-2 text-slate-300 p-3 bg-border-dark rounded-lg">
                            <span class="material-symbols-outlined text-primary text-sm">group</span>
                            <span
                                class="text-sm">{{ $plan->max_users ? $plan->max_users . ' مستخدم' : 'مستخدمين غير محدود' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300 p-3 bg-border-dark rounded-lg">
                            <span class="material-symbols-outlined text-primary text-sm">storage</span>
                            <span class="text-sm">{{ $plan->storage_limit_gb }} GB تخزين</span>
                        </div>
                    </div>

                    <div class="border-t border-border-dark pt-4 mb-6">
                        <p class="text-slate-400 text-xs font-bold mb-3">المميزات:</p>
                        <ul class="space-y-2 h-40 overflow-y-auto pr-2">
                            @if(is_array($plan->features))
                                @foreach($plan->features as $feature)
                                    <li class="flex items-center gap-2 text-slate-300 text-sm">
                                        <span class="material-symbols-outlined text-green-400 text-xs">done</span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <form action="{{ route('subscription.choose-plan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->slug }}">
                        <button type="submit"
                            class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-blue-600 transition-colors {{ $plan->slug == $currentPlanId ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $plan->slug == $currentPlanId ? 'disabled' : '' }}>
                            {{ $plan->slug == $currentPlanId ? 'خطتك الحالية' : 'اختر هذه الخطة' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-surface-dark border border-border-dark rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">payments</span>
            طرق الدفع
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center justify-between p-4 bg-border-dark rounded-xl border border-border-dark">
                <div class="flex items-center gap-4">
                    <span class="material-symbols-outlined text-3xl text-primary">credit_card</span>
                    <div>
                        <p class="text-white font-bold">•••• •••• ••••
                            {{ substr($subscription->card_number ?? '4532', -4) }}
                        </p>
                        <p class="text-slate-400 text-sm">تنتهي {{ $subscription->expiry_date ?? '12/2026' }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-bold rounded-full">افتراضي</span>
            </div>

            <button onclick="document.getElementById('paymentModal').classList.remove('hidden')"
                class="flex items-center justify-center gap-3 p-4 bg-border-dark rounded-xl border border-dashed border-border-dark hover:border-primary transition-colors">
                <span class="material-symbols-outlined text-slate-400">add</span>
                <span class="text-slate-400">إضافة طريقة دفع جديدة</span>
            </button>
        </div>
    </div>

    <!-- Payment Method Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-surface-dark border border-border-dark rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-4">إضافة طريقة دفع جديدة</h3>
            <form action="{{ route('subscription.add-payment-method') }}" method="POST">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">رقم البطاقة</label>
                        <input type="text" name="card_number" required maxlength="16" placeholder="XXXX XXXX XXXX XXXX"
                            class="w-full bg-border-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:border-primary outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-400 text-sm mb-2">تاريخ الانتهاء</label>
                            <input type="text" name="expiry_date" placeholder="MM/YY" required
                                class="w-full bg-border-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:border-primary outline-none text-center">
                        </div>
                        <div>
                            <label class="block text-slate-400 text-sm mb-2">CVV</label>
                            <input type="text" name="cvv" required maxlength="3" placeholder="XXX"
                                class="w-full bg-border-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:border-primary outline-none text-center">
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-all">
                        إضافة
                    </button>
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                        class="flex-1 bg-slate-700 text-white font-bold py-3 rounded-lg hover:bg-slate-600 transition-all">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection