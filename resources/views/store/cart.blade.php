@extends('layouts.store')
@section('title','Your bag — ROAMFORM')
@section('content')
<section class="section wrap">
 <div class="eyebrow">Your selection</div><h1 style="font-size:clamp(38px,6vw,58px)">Your bag</h1>
 @if(session('success'))<p class="notice success">{{ session('success') }}</p>@endif
 @if($errors->any())<div class="notice error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
 @if($items->isEmpty())
  <div class="empty">Your bag is empty. <a href="{{ route('home') }}">Explore the collection →</a></div>
 @else
  <div class="cart-layout"><div class="cart-items">
   @foreach($items as $item)
    <article class="cart-item">
     @if($item['product']->image)<img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}">@else<div class="placeholder">ROAMFORM</div>@endif
     <div class="cart-detail"><h2>{{ $item['product']->name }}</h2><p>{{ number_format((float)$item['product']->price,2) }} USD each · {{ $item['product']->stock_quantity }} available</p>
      <div class="cart-actions">
       <form method="POST" action="{{ route('cart.update',$item['product']) }}">@csrf @method('PATCH')<input aria-label="Quantity" type="number" name="quantity" min="1" max="{{ min(99,$item['product']->stock_quantity) }}" value="{{ $item['quantity'] }}"><button class="text-button" type="submit">Update</button></form>
       <form method="POST" action="{{ route('cart.destroy',$item['product']) }}">@csrf @method('DELETE')<button class="text-button" type="submit">Remove</button></form>
      </div>
     </div><strong>{{ number_format($item['line_total'],2) }} USD</strong>
    </article>
   @endforeach
  </div><aside class="cart-summary"><div class="eyebrow">Summary</div><div class="summary-line"><span>Subtotal</span><strong>{{ number_format($subtotal,2) }} USD</strong></div><p>Shipping and final availability will be confirmed before payment.</p><a class="button" href="{{ route('checkout.create') }}">Continue to checkout</a></aside></div>
 @endif
</section>
@endsection