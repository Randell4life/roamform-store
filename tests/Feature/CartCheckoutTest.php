<?php
namespace Tests\Feature;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceived;
use Tests\TestCase;
class CartCheckoutTest extends TestCase {
 use RefreshDatabase;
 private function product(int $stock=5): Product {
  return Product::create(['name'=>'Packing Cubes Set','slug'=>'packing-cubes-set','short_description'=>'Organize your luggage','description'=>'Travel organizer set','price'=>34.99,'stock_quantity'=>$stock,'low_stock_threshold'=>2,'is_active'=>true]);
 }
 public function test_customer_can_add_available_product_to_bag(): void {
  $product=$this->product();
  $this->post(route('cart.store',$product),['quantity'=>2])->assertRedirect(route('cart.index'));
  $this->assertSame(2,session('cart')[$product->id]);
 }
 public function test_checkout_creates_order_and_reserves_inventory(): void {
  Mail::fake();
  $product=$this->product(5);
  $this->withSession(['cart'=>[$product->id=>2]])->post(route('checkout.store'),[
   'customer_name'=>'Taylor Customer','customer_email'=>'taylor@example.com','customer_phone'=>'+12025550123',
   'address_line_1'=>'100 Main Street','city'=>'Austin','state_region'=>'TX','postal_code'=>'78701','country'=>'United States',
  ])->assertRedirect();
  $this->assertDatabaseHas('orders',['customer_email'=>'taylor@example.com','status'=>'pending_review','payment_status'=>'not_paid','total'=>'69.98']);
  $this->assertDatabaseHas('order_items',['product_id'=>$product->id,'quantity'=>2]);
  $this->assertSame(3,$product->fresh()->stock_quantity);
  Mail::assertSent(OrderReceived::class, fn (OrderReceived $mail): bool => $mail->hasTo('taylor@example.com'));
 }
 public function test_cancelling_order_restores_reserved_inventory(): void {
  $product=$this->product(5);
  $order=Order::create(['order_number'=>'RF-TEST12345','customer_name'=>'Taylor Customer','customer_email'=>'taylor@example.com','customer_phone'=>'+12025550123','address_line_1'=>'100 Main Street','city'=>'Austin','postal_code'=>'78701','country'=>'United States','status'=>'pending_review','payment_status'=>'not_paid','currency'=>'USD','subtotal'=>69.98,'shipping_cost'=>0,'total'=>69.98]);
  $order->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'unit_price'=>$product->price,'quantity'=>2,'line_total'=>69.98]);
  $product->decrement('stock_quantity',2);
  $order->update(['status'=>'cancelled']);
  $this->assertSame(5,$product->fresh()->stock_quantity);
 }
}