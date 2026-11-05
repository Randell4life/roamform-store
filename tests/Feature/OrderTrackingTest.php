<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    use RefreshDatabase;

    private function createOrder(): Order
    {
        return Order::create([
            'order_number' => 'RF-TRACK1234',
            'customer_name' => 'Taylor Customer',
            'customer_email' => 'taylor@example.com',
            'customer_phone' => '+12025550123',
            'address_line_1' => '100 Main Street',
            'city' => 'Austin',
            'state_region' => 'TX',
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

    public function test_customer_can_find_order_with_matching_number_and_email(): void
    {
        $order = $this->createOrder();

        $this->post(route('orders.track.lookup'), [
            'order_number' => 'rf-track1234',
            'email' => 'TAYLOR@example.com',
        ])->assertRedirect(route('orders.track'));

        $this->assertSame($order->id, session('order_tracking_id'));

        $this->get(route('orders.track'))
            ->assertOk()
            ->assertSee('RF-TRACK1234')
            ->assertSee('Being prepared');
    }

    public function test_order_lookup_does_not_reveal_order_when_email_does_not_match(): void
    {
        $this->createOrder();

        $this->from(route('orders.track'))->post(route('orders.track.lookup'), [
            'order_number' => 'RF-TRACK1234',
            'email' => 'someone-else@example.com',
        ])->assertRedirect(route('orders.track'))
            ->assertSessionHasErrors('tracking');

        $this->assertNull(session('order_tracking_id'));
    }

    public function test_customer_can_clear_order_tracking_session(): void
    {
        $order = $this->createOrder();

        $this->withSession(['order_tracking_id' => $order->id])
            ->post(route('orders.track.clear'))
            ->assertRedirect(route('orders.track'));

        $this->assertNull(session('order_tracking_id'));
    }
}
