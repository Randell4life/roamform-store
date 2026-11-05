<?php
namespace App\Http\Controllers\Storefront;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class CartController extends Controller {
 public function index(): View {
  $cart=session('cart',[]);
  $products=Product::query()->whereIn('id',array_keys($cart))->where('is_active',true)->get()->keyBy('id');
  $items=collect($cart)->map(function (int $quantity,string|int $id) use ($products) {
   $product=$products->get($id);
   return $product ? ['product'=>$product,'quantity'=>$quantity,'line_total'=>(float)$product->price*$quantity] : null;
  })->filter()->values();
  $subtotal=$items->sum('line_total');
  return view('store.cart',compact('items','subtotal'));
 }
 public function store(Request $request,Product $product): RedirectResponse {
  abort_unless($product->is_active,404);
  $validated=$request->validate(['quantity'=>['nullable','integer','min:1','max:99']]);
  $quantity=(int)($validated['quantity']??1);
  if ($product->stock_quantity<1) return back()->withErrors(['cart'=>'This item is currently unavailable.']);
  $cart=session('cart',[]); $newQuantity=($cart[$product->id]??0)+$quantity;
  if ($newQuantity>$product->stock_quantity) return back()->withErrors(['cart'=>'The requested quantity is above the available stock.']);
  $cart[$product->id]=$newQuantity; session(['cart'=>$cart]);
  return redirect()->route('cart.index')->with('success','Added to your bag.');
 }
 public function update(Request $request,Product $product): RedirectResponse {
  $validated=$request->validate(['quantity'=>['required','integer','min:1','max:99']]); $quantity=(int)$validated['quantity'];
  if (!$product->is_active||$quantity>$product->stock_quantity) return back()->withErrors(['cart'=>'The requested quantity is not currently available.']);
  $cart=session('cart',[]); if(array_key_exists($product->id,$cart)){ $cart[$product->id]=$quantity; session(['cart'=>$cart]); }
  return back()->with('success','Bag updated.');
 }
 public function destroy(Product $product): RedirectResponse {
  $cart=session('cart',[]); unset($cart[$product->id]); session(['cart'=>$cart]);
  return back()->with('success','Item removed.');
 }
}