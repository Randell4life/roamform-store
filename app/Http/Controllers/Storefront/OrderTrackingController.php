<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('order_tracking_id');

        return redirect()->route('orders.track');
    }
}
