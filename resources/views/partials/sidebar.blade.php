<aside
    class="w-72 flex-shrink-0 bg-[#0f172a] text-slate-300 flex flex-col hidden lg:flex border-l border-slate-800 transition-all duration-300">
    <div class="p-8 flex items-center gap-3">
        <div
            class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-900/20">
            <span class="material-symbols-outlined">enterprise</span>
        </div>
        <span class="text-xl font-bold text-white tracking-wide">بيانات الأعمال</span>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
        <div class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-widest">الرئيسية</div>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl transition-all' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('dashboard') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('dashboard*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">dashboard</span>
            <span class="font-medium">لوحة التحكم</span>
        </a>

        <div class="px-4 py-6 text-xs font-semibold text-slate-500 uppercase tracking-widest">العمليات</div>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('pos*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('pos') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('pos*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">point_of_sale</span>
            <span class="font-medium">نقاط البيع</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('inventory*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('inventory') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('inventory*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">inventory_2</span>
            <span class="font-medium">المخزون</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('invoices*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('invoices.index') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('invoices*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">receipt_long</span>
            <span class="font-medium">الفواتير والمبيعات</span>
        </a>

        <div class="px-4 py-6 text-xs font-semibold text-slate-500 uppercase tracking-widest">الإدارة</div>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('customers*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('customers.index') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('customers*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">groups</span>
            <span class="font-medium">العملاء والموردين</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('employees*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('employees') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('employees*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">badge</span>
            <span class="font-medium">الموظفين</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('accounting*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('accounting') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('accounting*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">account_balance</span>
            <span class="font-medium">المحاسبة المالية</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('reports*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('reports.index') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('reports*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">analytics</span>
            <span class="font-medium">التقارير</span>
        </a>

        <div class="px-4 py-6 text-xs font-semibold text-slate-500 uppercase tracking-widest">النظام</div>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('settings*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('settings.general') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('settings*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">settings</span>
            <span class="font-medium">الإعدادات</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('security*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('security') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('security*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">security</span>
            <span class="font-medium">الأمان</span>
        </a>
        <a class="flex items-center px-4 py-3 {{ request()->routeIs('account*') ? 'text-white bg-primary/20 border-r-4 border-primary rounded-l-xl' : 'hover:bg-slate-800 rounded-xl transition-all group' }}"
            href="{{ route('account') }}">
            <span
                class="material-symbols-outlined ml-3 {{ request()->routeIs('account*') ? 'text-primary' : 'group-hover:text-primary transition-colors' }}">manage_accounts</span>
            <span class="font-medium">إدارة الحساب</span>
        </a>
    </nav>

    <div class="p-6 bg-slate-900/50">
        <div class="flex items-center gap-3 p-3 bg-slate-800 rounded-2xl">
            @auth
                <div
                    class="w-10 h-10 rounded-full border border-slate-600 object-cover bg-primary/10 flex items-center justify-center text-primary font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">مدير النظام</p>
                </div>
                <a class="text-slate-500 hover:text-white" href="{{ route('profile') }}">
                    <span class="material-symbols-outlined text-sm">settings</span>
                </a>
            @endauth
        </div>
    </div>
</aside>