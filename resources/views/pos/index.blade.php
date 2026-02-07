@extends('layouts.app')

@section('title', 'نقاط البيع - نظام ERP')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">نقاط البيع (POS)</h1>
            <p class="text-slate-400 mt-1">نظام نقاط البيع السريع</p>
        </div>
        <a href="{{ route('invoices.create') }}"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            بيع جديد
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Products Grid -->
        <div class="lg:col-span-2 bg-card-dark border border-border-dark rounded-2xl p-6">
            <!-- Search & Filter -->
            <div class="flex gap-4 mb-6">
                <div class="flex-1 relative">
                    <span
                        class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input type="text" id="search-products" placeholder="ابحث عن منتج بالاسم أو الباركود..."
                        class="w-full bg-surface-dark border border-border-dark rounded-lg pr-10 pl-4 py-3 text-white focus:border-primary outline-none">
                </div>
                <select id="filter-category"
                    class="bg-surface-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:border-primary outline-none">
                    <option value="">كل التصنيفات</option>
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="products-grid">
                @forelse(App\Models\Product::where('is_active', true)->take(24)->get() as $product)
                    <button type="button" data-category="{{ $product->category_id }}"
                        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->tax_rate ?? 15 }})"
                        class="bg-surface-dark border border-border-dark rounded-xl p-4 text-center hover:border-primary hover:bg-primary/5 transition-all group product-btn">
                        <div class="w-12 h-12 mx-auto mb-3 bg-primary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">inventory_2</span>
                        </div>
                        <p class="text-sm font-bold text-white truncate">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ number_format($product->price) }} ر.س</p>
                    </button>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2">inventory_2</span>
                        <p>لا توجد منتجات</p>
                        <a href="{{ route('products.create') }}" class="text-primary hover:underline mt-2 block">إضافة منتج
                            جديد</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Cart Sidebar -->
        <div class="bg-card-dark border border-border-dark rounded-2xl p-6 flex flex-col h-fit">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">shopping_cart</span>
                سلة المشتريات
            </h3>

            <!-- Cart Items -->
            <div id="cart-items" class="space-y-3 flex-1 min-h-[200px] max-h-[400px] overflow-y-auto">
                <div class="text-center text-slate-500 py-8" id="empty-cart">
                    <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                    <p class="mt-2">السلة فارغة</p>
                </div>
            </div>

            <!-- Totals -->
            <div class="border-t border-border-dark pt-4 mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">المجموع الفرعي</span>
                    <span class="text-white font-bold" id="subtotal">0.00 ر.س</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400" id="tax-label">ضريبة القيمة المضافة</span>
                    <span class="text-white font-bold" id="tax">0.00 ر.س</span>
                </div>
                <div class="flex justify-between text-lg pt-2 border-t border-border-dark">
                    <span class="text-white font-bold">الإجمالي</span>
                    <span class="text-primary font-bold" id="total">0.00 ر.س</span>
                </div>
            </div>

            <!-- Customer Selection -->
            <div class="mt-4">
                <label class="text-xs text-slate-400 mb-2 block">العميل</label>
                <select id="customer"
                    class="w-full bg-surface-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:border-primary outline-none">
                    <option value="">عميل نقدي</option>
                    @foreach(App\Models\Customer::all() as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="mt-4 space-y-3">
                <button onclick="checkout()"
                    class="w-full py-3 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">payments</span>
                    إتمام الدفع
                </button>
                <button onclick="clearCart()"
                    class="w-full py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition-colors">
                    إلغاء الطلب
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        // Search & Filter functionality
        document.getElementById('search-products').addEventListener('input', filterProducts);
        document.getElementById('filter-category').addEventListener('change', filterProducts);

        function filterProducts() {
            const searchTerm = document.getElementById('search-products').value.toLowerCase();
            const categoryId = document.getElementById('filter-category').value;
            const products = document.querySelectorAll('.product-btn');

            products.forEach(product => {
                const name = product.querySelector('p').innerText.toLowerCase();
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !categoryId || product.getAttribute('data-category') === categoryId;

                if (matchesSearch && matchesCategory) {
                    product.classList.remove('hidden');
                } else {
                    product.classList.add('hidden');
                }
            });
        }

        function addToCart(id, name, price, taxRate) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, taxRate, quantity: 1 });
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
        }

        function updateQuantity(id, change) {
            const item = cart.find(item => item.id === id);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(id);
                }
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');

            if (cart.length === 0) {
                container.innerHTML = `<div class="text-center text-slate-500 py-8" id="empty-cart">
                                                <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                                                <p class="mt-2">السلة فارغة</p>
                                            </div>`;
            } else {
                container.innerHTML = cart.map(item => `
                                                <div class="flex items-center justify-between bg-surface-dark rounded-lg p-3">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-bold text-white">${item.name}</p>
                                                        <p class="text-xs text-slate-400">${item.price.toFixed(2)} ر.س <span class="text-[10px] opacity-50">(${item.taxRate}%)</span></p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 bg-slate-700 rounded text-white">-</button>
                                                        <span class="text-white font-bold w-6 text-center">${item.quantity}</span>
                                                        <button onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 bg-slate-700 rounded text-white">+</button>
                                                        <button onclick="removeFromCart(${item.id})" class="text-red-500 mr-2">
                                                            <span class="material-symbols-outlined text-sm">delete</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            `).join('');
            }

            updateTotals();
        }

        function updateTotals() {
            let subtotal = 0;
            let taxTotal = 0;

            cart.forEach(item => {
                const itemSubtotal = item.price * item.quantity;
                const itemTax = itemSubtotal - (itemSubtotal / (1 + (item.taxRate / 100)));
                subtotal += itemSubtotal;
                taxTotal += itemTax;
            });

            const total = subtotal; // Inclusive
            const netAmount = subtotal - taxTotal;

            document.getElementById('subtotal').textContent = netAmount.toFixed(2) + ' ر.س';
            document.getElementById('tax').textContent = taxTotal.toFixed(2) + ' ر.س';
            document.getElementById('total').textContent = total.toFixed(2) + ' ر.س';
        }

        function clearCart() {
            if (cart.length === 0) return;

            if (confirm('هل أنت متأكد من إلغاء الطلب؟')) {
                fetch('{{ route("pos.cancel") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    cart = [];
                    renderCart();
                });
            }
        }

        function checkout() {
            if (cart.length === 0) {
                alert('السلة فارغة');
                return;
            }
            // Store cart in session via AJAX or redirect with data
            // For now, redirect to invoice creation
            window.location.href = '{{ route("invoices.create") }}';
        }
    </script>
@endsection