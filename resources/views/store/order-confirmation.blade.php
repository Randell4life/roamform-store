@extends('layouts.store')

@section('title', 'Order request received — ROAMFORM')

@section('content')
<section class="section wrap confirmation">
    <div class="eyebrow">Request received</div>
    <h1 style="font-size:clamp(38px,6vw,58px)">Thank you.</h1>
    <p class="lead">Your order request <strong>{{ $order->order_number }}</strong> has been received. It is awaiting availability, shipping and payment review. No payment has been taken.</p>
    <div class="cart-summary" style="max-width:520px;margin-top:30px">
        @foreach ($order->items as $item)
            <div class="summary-line"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><strong>{{ number_format((float) $item->line_total, 2) }} USD</strong></div>
        @endforeach
        <div class="summary-line"><span>Subtotal</span><strong>{{ number_format((float) $order->subtotal, 2) }} USD</strong></div>
        <div class="summary-line"><span>Shipping</span><strong>To be confirmed</strong></div>
        <div class="summary-line"><span>Request status</span><strong>Pending review</strong></div>
    </div>
    <a class="button" href="{{ route('orders.track') }}">Track this order</a>
    <a class="button" href="{{ route('home') }}" style="margin-left:8px">Continue exploring</a>
</section>
@endsection
