<?php
namespace App\Http\Controllers\Storefront;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;
class HomeController extends Controller {
 public function __invoke(): View {
  $products=Product::query()->where('is_active',true)->with('category')->latest()->take(8)->get();
  $categories=Category::query()->where('is_active',true)->orderBy('sort_order')->get();
  return view('store.home',compact('products','categories'));
 }
}