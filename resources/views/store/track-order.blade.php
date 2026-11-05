@extends('layouts.store')

@section('title', 'Track your order — ROAMFORM')

@section('content')
<section class="section wrap">
    <div class="eyebrow">Order support</div>
    <h1 style="font-size:clamp(38px,6vw,58px)">Track your order.</h1>
    <p class="lead">Enter the order number from your confirmation and the email address used at checkout to view the latest status.</p>
    @if ($errors->any())
        <div class="notice error" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    @if (session('success'))<div class="notice success" role="status">{{ session('success') }}</div>@endif

    @if ($order)
        @php
            $steps = ['pending_review', 'approved', 'processing', 'shipped', 'completed'];
            $labels = [
                'pending_review' => 'Under review',
                'approved' => 'Confirmed',
                'processing' => 'Being prepared',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];
            $currentStep = array_search($order->status, $steps, true);
        @endphp
        <div class="tracking-card">
            <div class="tracking-head">
                <div>
                    <div class="eyebrow">Order number</div>
                    <h2>{{ $order->order_number }}</h2>
                    <p>Placed {{ $order->created_at->format('M j, Y') }}</p>
                </div>
                <span class="tracking-status {{ $order->status === 'cancelled' ? 'is-cancelled' : '' }}">
                    {{ $labels[$order->status] ?? str($order->status)->replace('_', ' ')->title() }}
                </span>
            </div>
            @if ($order->status === 'cancelled')
                <div class="tracking-cancelled">This order has been cancelled. If you need help, contact our support team.</div>
            @else
                <ol class="tracking-steps" aria-label="Order progress">
                    @foreach ($steps as $index => $step)
                        <li class="{{ $currentStep !== false && $currentStep >= $index ? 'is-done' : '' }} {{ $order->status === $step ? 'is-current' : '' }}">
                            <span class="tracking-dot"></span><span>{{ $labels[$step] }}</span>
                        </li>
                    @endforeach
                </ol>
            @endif
            @if ($order->tracking_number)
                <div class="shipment-panel">
                    <div class="eyebrow">Shipment tracking</div>
                    <h3>{{ $order->shipping_carrier ?: 'Shipping carrier' }}</h3>
                    <p><strong>Tracking number:</strong> {{ $order->tracking_number }}</p>
                    @if ($order->shipped_at)
                        <p><strong>Shipped:</strong> {{ $order->shipped_at->format('M j, Y g:i A') }}</p>
                    @endif
                    @if ($order->tracking_url)
                        <a class="button" href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer">Track shipment</a>
                    @endif
                </div>
            @endif
            <div class="tracking-columns">
                <div>
                    <h3>Items</h3>
                    @foreach ($order->items as $item)
                        <div class="summary-line"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><strong>&#36;{{ number_format((float) $item->line_total, 2) }}</strong></div>
                    @endforeach
                </div>
                <div>
                    <h3>Order summary</h3>
                    <div class="summary-line"><span>Subtotal</span><strong>&#36;{{ number_format((float) $order->subtotal, 2) }}</strong></div>
                    <div class="summary-line"><span>Shipping</span><strong>{{ $order->shipping_cost > 0 ? '$'.number_format((float) $order->shipping_cost, 2) : 'To be confirmed' }}</strong></div>
                    <div class="summary-line"><span>Total</span><strong>&#36;{{ number_format((float) $order->total, 2) }} {{ $order->currency }}</strong></div>
                    <div class="summary-line"><span>Payment</span><strong>{{ str($order->payment_status)->replace('_', ' ')->title() }}</strong></div>
                </div>
            </div>
            <form method="POST" action="{{ route('orders.track.clear') }}">@csrf
                <button class="text-button" type="submit">Track a different order</button>
            </form>
        </div>
    @else
        <form class="tracking-form" method="POST" action="{{ route('orders.track.lookup') }}">
            @csrf
            <label>Order number
                <input name="order_number" value="{{ old('order_number') }}" placeholder="RF-XXXXXXXXXX" required maxlength="32" autocomplete="off">
            </label>
            <label>Email address
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
            </label>
            <button class="button" type="submit">Find my order</button>
        </form>
        <p class="tracking-help">Your order number is shown on the confirmation page after checkout. For privacy, both details must match the original order.</p>
    @endif
</section>
@endsection
