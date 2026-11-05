@extends('layouts.store')
@section('title','Checkout — ROAMFORM')
@section('content')
<section class="section wrap"><div class="eyebrow">Almost there</div><h1 style="font-size:clamp(38px,6vw,58px)">Delivery details</h1>
 <p class="lead">Send your order request. We will review product availability, shipping and payment options before confirming it.</p>
 @if($errors->any())<div class="notice error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
 <form class="checkout-form" method="POST" action="{{ route('checkout.store') }}">@csrf
  <div class="form-grid">
   <label>Full name<input name="customer_name" value="{{ old('customer_name') }}" required autocomplete="name"></label>
   <label>Email address<input type="email" name="customer_email" value="{{ old('customer_email') }}" required autocomplete="email"></label>
   <label>Phone number<input name="customer_phone" value="{{ old('customer_phone') }}" required autocomplete="tel"></label>
   <label>Country<input name="country" value="{{ old('country','United States') }}" required autocomplete="country-name"></label>
   <label class="full">Address line 1<input name="address_line_1" value="{{ old('address_line_1') }}" required autocomplete="address-line1"></label>
   <label class="full">Address line 2 (optional)<input name="address_line_2" value="{{ old('address_line_2') }}" autocomplete="address-line2"></label>
   <label>City<input name="city" value="{{ old('city') }}" required autocomplete="address-level2"></label>
   <label>State / region<input name="state_region" value="{{ old('state_region') }}" autocomplete="address-level1"></label>
   <label>ZIP / postal code<input name="postal_code" value="{{ old('postal_code') }}" required autocomplete="postal-code"></label>
   <label class="full">Order note (optional)<textarea name="customer_note" rows="3">{{ old('customer_note') }}</textarea></label>
  </div>
  <aside class="cart-summary checkout-summary"><div class="summary-line"><span>Estimated subtotal</span><strong>{{ number_format($subtotal,2) }} USD</strong></div><p>No payment is collected at this stage. Shipping cost and payment instructions will be confirmed by the store.</p><button class="button" type="submit">Submit order request</button></aside>
 </form>
</section>
@endsection