<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model {
 protected $fillable=['order_number','customer_name','customer_email','customer_phone','address_line_1','address_line_2','city','state_region','postal_code','country','customer_note','status','payment_status','currency','subtotal','shipping_cost','total'];
 protected function casts(): array { return ['subtotal'=>'decimal:2','shipping_cost'=>'decimal:2','total'=>'decimal:2']; }
 protected static function booted(): void {
  static::updating(function (Order $order): void {
   if ($order->isDirty('status') && $order->status === 'cancelled' && $order->getOriginal('status') !== 'cancelled') {
    $order->items()->with('product')->get()->each(function (OrderItem $item): void {
     $item->product?->increment('stock_quantity', $item->quantity);
    });
   }
  });
 }
 public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}