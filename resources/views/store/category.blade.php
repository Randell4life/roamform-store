@extends('layouts.store')
@section('title',$category->name.' — ROAMFORM')
@section('content')
<section class="section wrap">
 <div class="eyebrow">Explore by category</div><h1 style="font-size:clamp(38px,6vw,60px)">{{ $category->name }}</h1>
 @if($category->description)<p class="lead">{{ $category->description }}</p>@endif
 <div class="grid" style="margin-top:28px">
 @forelse($products as $product)
  <a class="product" href="{{ route('products.show',$product) }}">
   @if($product->image)<img src="{{ $product->image }}" alt="{{ $product->name }}">@else<div class="placeholder">ROAMFORM</div>@endif
   <div class="productinfo"><h3>{{ $product->name }}</h3><div class="price">${{ number_format((float)$product->price,2) }}</div></div>
  </a>
 @empty<div class="empty">No products in this category yet.</div>@endforelse
 </div><div style="margin-top:28px">{{ $products->links() }}</div>
</section>
@endsection