<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(Request $request): View
    {
        $order = null;
        $orderId = $request->session()->get('order_tracking_id');

        if ($orderId) {
            $order = Order::query()->with('items')->find($orderId);

            if (! $order) {
                $request->session()->forget('order_tracking_id');
            }
        }

        return view('store.track-order', compact('order'));
    }

    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $order = Order::query()
            ->where('order_number', strtoupper(trim($validated['order_number'])))
            ->whereRaw('LOWER(customer_email) = ?', [mb_strtolower(trim($validated['email']))])
            ->first();

        if (! $order) {
            return back()
                ->withErrors(['tracking' => 'We could not find an order matching those details. Check your order number and email, then try again.'])
                ->onlyInput('order_number', 'email');
        }

        $request->session()->put('order_tracking_id', $order->getKey());

        return redirect()->route('orders.track')->with('success', 'Your order details are ready.');
    }

    public function acceptShippingQuote(Request $request): RedirectResponse
    {
        $orderId = $request->session()->get('order_tracking_id');

        $accepted = DB::transaction(function () use ($orderId): bool {
            $order = Order::query()->lockForUpdate()->find($orderId);

            if (! $order || $order->status !== 'awaiting_shipping_approval') {
                return false;
            }

            $order->update([
                'status' => 'approved',
                'payment_status' => 'awaiting_confirmation',
                'shipping_quote_accepted_at' => now(),
            ]);

            return true;
        });

        if (! $accepted) {
            return redirect()->route('orders.track')
                ->withErrors(['shipping_quote' => 'There is no pending shipping quote to approve.']);
        }

        return redirect()->route('orders.track')
            ->with('success', 'Shipping quote accepted. We will contact you with payment instructions.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('order_tracking_id');

        return redirect()->route('orders.track');
    }
}
