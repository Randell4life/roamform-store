@extends('layouts.store')
@section('title','ROAMFORM — Travel Better. Live Organized.')
@section('content')
<section class="hero"><div class="wrap"><div class="eyebrow">Thoughtful essentials for every journey</div><h1>Travel better.<br>Live organized.</h1><p class="lead">Discover considered travel essentials designed to make packing simpler, journeys smoother, and everyday life more organized.</p><a class="button" href="#collection">Explore the collection</a></div></section>
<section class="section wrap" id="collection">
 @if($categories->isNotEmpty())<div class="categorylinks">@foreach($categories as $category)<a href="{{ route('categories.show',$category) }}">{{ $category->name }} <span>↗</span></a>@endforeach</div>@endif
 <div class="sectionhead"><div><div class="eyebrow">The edit</div><h2>Travel essentials</h2></div><span style="font-size:12px;color:#697581">Thoughtfully selected</span></div>
 <div class="grid">
 @forelse($products as $product)
  <a class="product" href="{{ route('products.show',$product) }}">
   @if($product->image)<img src="{{ $product->image }}" alt="{{ $product->name }}">@else<div class="placeholder">ROAMFORM</div>@endif
   <div class="productinfo">@if($product->category)<div class="eyebrow" style="font-size:9px;margin-bottom:7px">{{ $product->category->name }}</div>@endif<h3>{{ $product->name }}</h3><div class="price">${{ number_format((float)$product->price,2) }}</div></div>
  </a>
 @empty<div class="empty">Our first collection is being carefully selected. Check back soon.</div>@endforelse
 </div>
</section>
@endsection