@extends('layouts.patient.patient-layout')

@section('content')
<div class="health-dashboard marketplace">
    <section class="dashboard-hero market-hero">
        <div><span class="eyebrow">MYSIHAT MARKET</span><h1>Everyday care, closer to home.</h1><p>Browse a small selection of health essentials chosen for simple home care.</p></div>
        <a class="primary-action" href="{{ route('marketplace.cart') }}"><i class="fas fa-bag-shopping"></i> Cart @if($cartCount) <span class="cart-count">{{ $cartCount }}</span> @endif</a>
    </section>
    @if(session('success'))<div class="market-notice"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
    @if(session('error'))<div class="market-notice market-error"><i class="fas fa-lock"></i> {{ session('error') }}</div>@endif
    <div class="market-heading"><div><span class="eyebrow">HEALTH ESSENTIALS</span><h2>Made for your everyday routine</h2></div><span>{{ count($products) }} products</span></div>
    <section class="product-grid">
        @foreach($products as $id => $product)
        <article class="product-card">
            <div class="product-icon"><i class="fas {{ $product['icon'] }}"></i></div>
            <span class="eyebrow">{{ $product['category'] }}</span>
            <h2>{{ $product['name'] }}</h2>
            <p>{{ $product['description'] }}</p>
            @if($product['requires_prescription'])<span class="prescription-badge"><i class="fas fa-lock"></i> Doctor approval required</span>@endif
            <div class="product-footer"><strong>RM {{ number_format($product['price'], 2) }}</strong><form method="POST" action="{{ route('marketplace.add', $id) }}">@csrf<button type="submit" aria-label="Add {{ $product['name'] }} to cart"><i class="fas {{ $product['requires_prescription'] ? 'fa-lock' : 'fa-plus' }}"></i> {{ $product['requires_prescription'] ? 'Request access' : 'Add' }}</button></form></div>
        </article>
        @endforeach
    </section>
</div>
@endsection
