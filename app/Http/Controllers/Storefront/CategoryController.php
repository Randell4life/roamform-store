<?php
namespace App\Http\Controllers\Storefront;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;
class CategoryController extends Controller {
 public function show(Category $category): View {
  abort_unless($category->is_active,404);
  $products=$category->products()->where('is_active',true)->latest()->paginate(12);
  return view('store.category',compact('category','products'));
 }
}