@extends('layouts.app')

@section('title', 'نتائج البحث - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">نتائج البحث عن: "{{ $query }}"</h1>
        <p class="text-slate-400 mt-1">العثور على النتائج التالية</p>
    </div>

    @if($results['products']->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-white mb-4">المنتجات</h2>
            <div class="bg-card-dark border border-border-dark rounded-xl p-4">
                @foreach($results['products'] as $product)
                    <div class="flex items-center justify-between py-3 border-b border-border-dark last:border-0">
                        <div>
                            <a href="{{ route('products.show', $product) }}"
                                class="text-primary hover:underline">{{ $product->name }}</a>
                            <p class="text-sm text-slate-400">{{ $product->sku }}</p>
                        </div>
                        <span class="text-white">{{ number_format($product->price, 2) }} ر.س</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($results['customers']->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-white mb-4">العملاء</h2>
            <div class="bg-card-dark border border-border-dark rounded-xl p-4">
                @foreach($results['customers'] as $customer)
                    <div class="flex items-center justify-between py-3 border-b border-border-dark last:border-0">
                        <div>
                            <a href="{{ route('customers.show', $customer) }}"
                                class="text-primary hover:underline">{{ $customer->name }}</a>
                            <p class="text-sm text-slate-400">{{ $customer->email }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($results['invoices']->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-white mb-4">الفواتير</h2>
            <div class="bg-card-dark border border-border-dark rounded-xl p-4">
                @foreach($results['invoices'] as $invoice)
                    <div class="flex items-center justify-between py-3 border-b border-border-dark last:border-0">
                        <div>
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="text-primary hover:underline">{{ $invoice->number }}</a>
                            <p class="text-sm text-slate-400">{{ $invoice->customer->name }}</p>
                        </div>
                        <span class="text-white">{{ number_format($invoice->total, 2) }} ر.س</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($results['products']->count() == 0 && $results['customers']->count() == 0 && $results['invoices']->count() == 0 && $results['suppliers']->count() == 0)
        <div class="bg-card-dark border border-border-dark rounded-xl p-12 text-center">
            <span class="material-icons text-6xl text-slate-600 mb-4">search_off</span>
            <p class="text-slate-400">لم يتم العثور على نتائج</p>
        </div>
    @endif
@endsection