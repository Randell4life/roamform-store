<?php
namespace App\Http\Controllers\Storefront;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
class CheckoutController extends Controller {
 public function create(): View|RedirectResponse {
  if(empty(session('cart',[]))) return redirect()->route('cart.index')->withErrors(['cart'=>'Your bag is empty.']);
  return view('store.checkout',['subtotal'=>$this->subtotal()]);
 }
 public function store(Request $request): RedirectResponse {
  $validated=$request->validate([
   'customer_name'=>['required','string','max:255'],'customer_email'=>['required','email','max:255'],
   'customer_phone'=>['required','string','max:40'],'address_line_1'=>['required','string','max:255'],
   'address_line_2'=>['nullable','string','max:255'],'city'=>['required','string','max:120'],
   'state_region'=>['nullable','string','max:120'],'postal_code'=>['required','string','max:30'],
   'country'=>['required','string','max:100'],'customer_note'=>['nullable','string','max:2000'],
  ]);
  $cart=session('cart',[]);
  if(empty($cart)) return redirect()->route('cart.index')->withErrors(['cart'=>'Your bag is empty.']);
  $order=DB::transaction(function() use($cart,$validated) {
   $products=Product::query()->whereIn('id',array_keys($cart))->where('is_active',true)->lockForUpdate()->get()->keyBy('id');
   if($products->count()!==count($cart)) throw ValidationException::withMessages(['cart'=>'One or more products are no longer available. Please review your bag.']);
   $subtotal=0;
   foreach($cart as $productId=>$quantity) {
    $product=$products->get($productId);
    if(!$product||$quantity>$product->stock_quantity) throw ValidationException::withMessages(['cart'=>'Stock changed for one or more products. Please review your bag.']);
    $subtotal+=(float)$product->price*(int)$quantity;
   }
   $order=Order::create([...$validated,'order_number'=>'RF-'.Str::upper(Str::random(10)),'status'=>'pending_review','payment_status'=>'not_paid','currency'=>'USD','subtotal'=>$subtotal,'shipping_cost'=>0,'total'=>$subtotal]);
   foreach($cart as $productId=>$quantity) {
    $product=$products->get($productId);
    $order->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'sku'=>$product->sku,'unit_price'=>$product->price,'quantity'=>$quantity,'line_total'=>(float)$product->price*(int)$quantity]);
    $product->decrement('stock_quantity', (int) $quantity);
   }
   return $order;
  });
  session()->forget('cart');
  return redirect()->route('checkout.success',$order->order_number);
 }
 public function success(Order $order): View { $order->load('items'); return view('store.order-confirmation',compact('order')); }
 private function subtotal(): float {
  $cart=session('cart',[]); $products=Product::query()->whereIn('id',array_keys($cart))->where('is_active',true)->get();
  return $products->sum(fn(Product $product)=>(float)$product->price*(int)$cart[$product->id]);
 }
}