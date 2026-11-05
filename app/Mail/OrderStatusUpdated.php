<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('items');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update for your ROAMFORM order '.$this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
            with: [
                'statusLabel' => $this->statusLabel(),
            ],
        );
    }

    private function statusLabel(): string
    {
        return match ($this->order->status) {
            'pending_review' => 'Under review',
            'awaiting_shipping_approval' => 'Shipping quote ready — your approval is needed',
            'approved' => 'Confirmed',
            'processing' => 'Being prepared',
            'shipped' => 'Shipped',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => str($this->order->status)->replace('_', ' ')->title()->toString(),
        };
    }
}
