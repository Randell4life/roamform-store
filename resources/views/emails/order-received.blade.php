<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;background:#f7f6f2;color:#17212b;font-family:Arial,sans-serif">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 12px"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#fff;border:1px solid #e7e5df"><tr><td style="padding:30px">
<p style="font-size:18px;font-weight:800;letter-spacing:4px;margin:0 0 30px">ROAMFORM</p>
<p style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#b58b62;font-weight:bold">Order received</p>
<h1 style="font-size:28px;margin:8px 0 14px">Thanks for your order request.</h1>
<p style="font-size:14px;line-height:1.7;color:#697581">Hello {{ $order->customer_name }}, we have received order <strong>{{ $order->order_number }}</strong>. Our team will review product availability, shipping and payment options and contact you with the next steps.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:22px 0">
@foreach ($order->items as $item)
<tr><td style="padding:12px 0;border-bottom:1px solid #e7e5df;font-size:13px">{{ $item->product_name }} × {{ $item->quantity }}</td><td align="right" style="padding:12px 0;border-bottom:1px solid #e7e5df;font-size:13px;font-weight:bold">&#36;{{ number_format((float) $item->line_total, 2) }}</td></tr>
@endforeach
<tr><td style="padding:14px 0;font-size:13px">Subtotal</td><td align="right" style="padding:14px 0;font-size:13px;font-weight:bold">&#36;{{ number_format((float) $order->subtotal, 2) }} USD</td></tr>
</table>
<p style="padding:15px;background:#f7f6f2;font-size:12px;line-height:1.7;color:#697581">No payment has been collected. Shipping cost and payment instructions will be confirmed before your order is finalized.</p>
<p style="font-size:13px;line-height:1.7;color:#697581">Thank you for choosing ROAMFORM.<br>Travel Better. Live Organized.</p>
</td></tr></table></td></tr></table>
</body></html>
