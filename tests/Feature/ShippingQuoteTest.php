<?php

namespace Tests\Feature;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ShippingQuoteTest extends TestCase
{
    use RefreshDatabase;

    private function createOrder(): Order
    {
        return Order::create([
            'order_number' => 'RF-QUOTE1234',
            'customer_name' => 'Taylor Customer',
            'customer_email' => 'taylor@example.com',
            'customer_phone' => '+12025550123',
            'address_line_1' => '100 Main Street',
            'city' => 'Austin',
            'postal_code' => '78701',
            'country' => 'United States',
            'status' => 'pending_review',
            'payment_status' => 'not_paid',
            'currency' => 'USD',
            'subtotal' => 34.99,
            'shipping_cost' => 0,
            'total' => 34.99,
        ]);
    }

    public function test_shipping_quote_recalculates_total_and_waits_for_customer_approval(): void
    {
        Mail::fake();

        $order = $this->createOrder();
        $order->update(['shipping_cost' => 8.50]);

        $order->refresh();

        $this->assertSame('42.49', $order->total);
        $this->assertSame('awaiting_shipping_approval', $order->status);
        $this->assertNull($order->shipping_quote_accepted_at);

        Mail::assertSent(OrderStatusUpdated::class, fn (OrderStatusUpdated $mail): bool =>
            $mail->order->status === 'awaiting_shipping_approval'
        );
    }

    public function test_customer_can_approve_quote_only_for_order_in_tracking_session(): void
    {
        Mail::fake();

        $order = $this->createOrder();
        $order->update(['shipping_cost' => 8.50]);

        $this->withSession(['order_tracking_id' => $order->id])
            ->post(route('orders.track.accept-shipping'))
            ->assertRedirect(route('orders.track'))
            ->assertSessionHas('success');

        $order->refresh();

        $this->assertSame('approved', $order->status);
        $this->assertSame('awaiting_confirmation', $order->payment_status);
        $this->assertNotNull($order->shipping_quote_accepted_at);
    }

    public function test_customer_cannot_approve_quote_without_a_matching_tracking_session(): void
    {
        $order = $this->createOrder();
        $order->update(['shipping_cost' => 8.50]);

        $this->post(route('orders.track.accept-shipping'))
            ->assertRedirect(route('orders.track'))
            ->assertSessionHasErrors('shipping_quote');

        $this->assertSame('awaiting_shipping_approval', $order->fresh()->status);
    }

    public function test_changing_quote_after_approval_requires_customer_approval_again(): void
    {
        Mail::fake();

        $order = $this->createOrder();
        $order->update(['shipping_cost' => 8.50]);
        $order->update([
            'status' => 'approved',
            'payment_status' => 'awaiting_confirmation',
            'shipping_quote_accepted_at' => now(),
        ]);

        $order->update(['shipping_cost' => 10.00]);

        $this->assertSame('awaiting_shipping_approval', $order->fresh()->status);
        $this->assertNull($order->fresh()->shipping_quote_accepted_at);
        $this->assertSame('44.99', $order->fresh()->total);
    }
}
