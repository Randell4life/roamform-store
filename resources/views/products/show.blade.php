@extends('layouts.store')
@section('title', $product->name . ' — ROAMFORM')
@section('content')
<section class="section wrap">
    <a href="{{ route('home') }}" style="font-size:12px;color:#697581">← Back to collection</a>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:42px;margin-top:28px">
        <div>
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width:100%;background:#eeece6">
            @else
                <div style="aspect-ratio:1;background:#eeece6;display:grid;place-items:center;color:#9a958b">ROAMFORM</div>
            @endif
        </div>
        <div>
            <div class="eyebrow">ROAMFORM collection</div>
            <h1 style="font-size:42px">{{ $product->name }}</h1>
            <p style="font-size:20px;font-weight:700">${{ number_format((float)$product->price, 2) }}</p>
            <p class="lead">{{ $product->short_description }}</p>
            <div style="line-height:1.8;color:#697581">{{ $product->description }}</div>
            <p style="margin-top:28px;font-size:12px;color:#697581">Checkout and supplier fulfillment will be enabled after payment and supplier accounts are verified.</p>
        </div>
    </div>
</section>
@endsection
