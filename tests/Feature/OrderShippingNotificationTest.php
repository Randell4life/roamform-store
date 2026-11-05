<?php

namespace Tests\Feature;

use App\Mail\OrderReceived;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderShippingNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function order(): Order
    {
        return Order::create([
            'order_number' => 'RF-SHIP12345',
            'customer_name' => 'Taylor Customer',
            'customer_email' => 'taylor@example.com',
            'customer_phone' => '+12025550123',
            'address_line_1' => '100 Main Street',
            'city' => 'Austin',
            'postal_code' => '78701',
            'country' => 'United States',
            'status' => 'processing',
            'payment_status' => 'not_paid',
            'currency' => 'USD',
            'subtotal' => 34.99,
            'shipping_cost' => 0,
            'total' => 34.99,
        ]);
    }

    public function test_status_change_sends_customer_email(): void
    {
        Mail::fake();

        $order = $this->order();
        $order->update(['status' => 'shipped']);

        Mail::assertSent(OrderStatusUpdated::class, function (OrderStatusUpdated $mail): bool {
            return $mail->hasTo('taylor@example.com')
                && $mail->order->order_number === 'RF-SHIP12345';
        });
    }

    public function test_tracking_details_are_saved_and_included_in_email(): void
    {
        Mail::fake();

        $order = $this->order();
        $order->update([
            'status' => 'shipped',
            'shipping_carrier' => 'UPS',
            'tracking_number' => '1Z999AA10123456784',
            'tracking_url' => 'https://www.ups.com/track?tracknum=1Z999AA10123456784',
        ]);

        $this->assertNotNull($order->fresh()->shipped_at);
        Mail::assertSent(OrderStatusUpdated::class, function (OrderStatusUpdated $mail): bool {
            return $mail->order->tracking_number === '1Z999AA10123456784'
                && $mail->order->tracking_url === 'https://www.ups.com/track?tracknum=1Z999AA10123456784';
        });
    }

    public function test_unchanged_order_status_does_not_send_duplicate_email(): void
    {
        Mail::fake();

        $order = $this->order();
        $order->update(['customer_note' => 'Please leave at the door']);

        Mail::assertNothingSent();
    }
}
