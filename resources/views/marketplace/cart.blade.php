@extends('layouts.patient.patient-layout')

@section('content')
<div class="health-dashboard marketplace">
    <section class="dashboard-hero compact-hero market-hero"><div><span class="eyebrow">MYSIHAT MARKET</span><h1>Your cart</h1><p>Review your health essentials before confirming your order.</p></div><a class="primary-action" href="{{ route('marketplace.index') }}"><i class="fas fa-arrow-left"></i> Continue shopping</a></section>
    <section class="cart-layout">
        <article class="panel">
            @forelse($items as $item)
            <div class="cart-item"><div class="product-icon small"><i class="fas {{ $item['icon'] }}"></i></div><div class="cart-product"><strong>{{ $item['name'] }}</strong><p>RM {{ number_format($item['price'], 2) }} each</p></div><form method="POST" action="{{ route('marketplace.update', $item['id']) }}" class="quantity-form">@csrf @method('PATCH')<label class="sr-only" for="quantity-{{ $item['id'] }}">Quantity</label><input id="quantity-{{ $item['id'] }}" type="number" name="quantity" min="0" max="10" value="{{ $item['quantity'] }}"><button type="submit">Update</button></form><strong>RM {{ number_format($item['subtotal'], 2) }}</strong></div>
            @empty <div class="empty-state"><i class="fas fa-bag-shopping"></i><h3>Your cart is empty</h3><p>Explore simple tools for your everyday health routine.</p><a class="secondary-action" href="{{ route('marketplace.index') }}">Browse products</a></div>@endforelse
        </article>
        <aside class="panel order-summary"><span class="eyebrow">ORDER SUMMARY</span><h2>Ready when you are.</h2><div><span>Items</span><strong>{{ $cartCount }}</strong></div><div><span>Delivery</span><strong>Free</strong></div><div class="total-row"><span>Total</span><strong>RM {{ number_format($total, 2) }}</strong></div>@if($items)<form method="POST" action="{{ route('marketplace.checkout') }}">@csrf<button class="checkout-button" type="submit">Confirm order <i class="fas fa-arrow-right"></i></button></form><small>Demo marketplace — no payment will be collected.</small>@endif</aside>
    </section>
</div>
@endsection
