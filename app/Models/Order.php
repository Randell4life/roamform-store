<?php

namespace App\Models;

use App\Mail\OrderStatusUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_email', 'customer_phone',
        'address_line_1', 'address_line_2', 'city', 'state_region', 'postal_code',
        'country', 'customer_note', 'status', 'payment_status', 'currency',
        'subtotal', 'shipping_cost', 'total', 'shipping_carrier', 'tracking_number',
        'tracking_url', 'shipped_at', 'shipping_quote_accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'shipped_at' => 'datetime',
            'shipping_quote_accepted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Order $order): void {
            if ($order->isDirty(['subtotal', 'shipping_cost'])) {
                $order->total = (float) $order->subtotal + (float) $order->shipping_cost;
            }

            if (
                $order->exists
                && $order->isDirty('shipping_cost')
                && $order->status !== 'cancelled'
            ) {
                $order->status = 'awaiting_shipping_approval';
                $order->shipping_quote_accepted_at = null;
            }
        });

        static::updating(function (Order $order): void {
            if ($order->isDirty('status') && $order->status === 'shipped' && ! $order->shipped_at) {
                $order->shipped_at = now();
            }

            if ($order->isDirty('status') && $order->status === 'cancelled' && $order->getOriginal('status') !== 'cancelled') {
                $order->items()->with('product')->get()->each(function (OrderItem $item): void {
                    $item->product?->increment('stock_quantity', $item->quantity);
                });
            }
        });

        static::updated(function (Order $order): void {
            if (! $order->wasChanged('status') || ! $order->customer_email) {
                return;
            }

            try {
                $freshOrder = $order->fresh('items');

                if ($freshOrder) {
                    Mail::to($freshOrder->customer_email)->send(new OrderStatusUpdated($freshOrder));
                }
            } catch (\Throwable $exception) {
                Log::warning('ROAMFORM order status email could not be sent.', [
                    'order_id' => $order->getKey(),
                    'message' => $exception->getMessage(),
                ]);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
