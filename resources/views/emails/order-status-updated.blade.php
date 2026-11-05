<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;background:#f7f6f2;color:#17212b;font-family:Arial,sans-serif">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 12px"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#fff;border:1px solid #e7e5df"><tr><td style="padding:30px">
<p style="font-size:18px;font-weight:800;letter-spacing:4px;margin:0 0 30px">ROAMFORM</p>
<p style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#b58b62;font-weight:bold">Order update</p>
<h1 style="font-size:28px;margin:8px 0 14px">Your order has a new update.</h1>
<p style="font-size:14px;line-height:1.7;color:#697581">Hello {{ $order->customer_name }}, there is a new update for order <strong>{{ $order->order_number }}</strong>.</p>
<div style="padding:18px;background:#f7f6f2;margin:22px 0">
<p style="margin:0 0 6px;font-size:11px;color:#697581;text-transform:uppercase;letter-spacing:1px">Current status</p>
<p style="font-size:18px;font-weight:bold;margin:0">{{ $statusLabel }}</p>
</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:18px 0">
<tr><td style="padding:10px 0;border-bottom:1px solid #e7e5df;font-size:13px">Items subtotal</td><td align="right" style="padding:10px 0;border-bottom:1px solid #e7e5df;font-size:13px">&#36;{{ number_format((float) $order->subtotal, 2) }}</td></tr>
<tr><td style="padding:10px 0;border-bottom:1px solid #e7e5df;font-size:13px">Shipping</td><td align="right" style="padding:10px 0;border-bottom:1px solid #e7e5df;font-size:13px">{{ $order->status === 'pending_review' ? 'To be confirmed' : '$'.number_format((float) $order->shipping_cost, 2) }}</td></tr>
<tr><td style="padding:12px 0;font-size:13px;font-weight:bold">Current total</td><td align="right" style="padding:12px 0;font-size:13px;font-weight:bold">&#36;{{ number_format((float) $order->total, 2) }} {{ $order->currency }}</td></tr>
</table>
@if ($order->status === 'awaiting_shipping_approval')
<p style="font-size:13px;line-height:1.7;color:#697581">Please open your order tracking page to review and approve the shipping quote. Your order will not proceed to payment until you approve it.</p>
@elseif ($order->status === 'approved' && $order->payment_status === 'awaiting_confirmation')
<p style="font-size:13px;line-height:1.7;color:#697581">Thank you for approving the shipping quote. Our team will contact you with the available payment instructions.</p>
@endif
@if ($order->tracking_number)
<p style="font-size:14px;line-height:1.7"><strong>Carrier:</strong> {{ $order->shipping_carrier ?: 'Shipping carrier' }}<br><strong>Tracking number:</strong> {{ $order->tracking_number }}</p>
@if ($order->tracking_url)<p><a href="{{ $order->tracking_url }}" style="display:inline-block;padding:13px 20px;background:#17212b;color:#fff;text-decoration:none;font-size:12px;font-weight:bold">TRACK SHIPMENT</a></p>@endif
@endif
<p style="font-size:13px;line-height:1.7;color:#697581">Thank you for choosing ROAMFORM.<br>Travel Better. Live Organized.</p>
</td></tr></table></td></tr></table>
</body></html>
