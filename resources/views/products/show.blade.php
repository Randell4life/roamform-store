@extends('layouts.store')
@section('title',$product->name.' — ROAMFORM')
@section('content')
<section class="section wrap"><a href="{{ route('home') }}" style="font-size:12px;color:#697581">← Back to collection</a>
 <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:42px;margin-top:28px">
  <div>@if($product->image)<img src="{{ $product->image }}" alt="{{ $product->name }}" style="width:100%;background:#eeece6">@else<div class="placeholder">ROAMFORM</div>@endif</div>
  <div><div class="eyebrow">{{ $product->category?->name ?? 'ROAMFORM collection' }}</div><h1 style="font-size:42px">{{ $product->name }}</h1>
   <p style="font-size:20px;font-weight:700">${{ number_format((float)$product->price,2) }}</p>
   <p class="lead">{{ $product->short_description }}</p><div style="line-height:1.8;color:#697581;white-space:pre-line">{{ $product->description }}</div>
   @if($product->stock_quantity > 0)
    <form method="POST" action="{{ route('cart.store',$product) }}" style="margin-top:26px">@csrf
     <label style="font-size:12px">Quantity <input type="number" name="quantity" value="1" min="1" max="{{ min(99,$product->stock_quantity) }}" style="padding:12px;width:76px;border:1px solid #e7e5df"></label>
     <button class="button" type="submit">Add to bag</button>
    </form>
   @else <p style="color:#8d3333;font-size:13px">Currently unavailable</p>@endif
   @if($errors->any())<div class="notice error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
   <p style="margin-top:28px;font-size:12px;color:#697581">Order requests are reviewed before shipping and payment are confirmed.</p>
  </div>
 </div>
</section>
@endsection