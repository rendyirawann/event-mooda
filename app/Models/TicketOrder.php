<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    protected $fillable = [
        'invoice_no', 'event_id', 'buyer_id', 'affiliate_id', 'reseller_id', 'items',
        'buyer_name', 'buyer_email', 'buyer_phone', 'subtotal', 'service_fee', 'total',
        'status', 'payment_method', 'payment_ref', 'payment_payload', 'paid_at', 'expired_at',
    ];

    protected $casts = [
        'paid_at'          => 'datetime',
        'expired_at'       => 'datetime',
        'items'            => 'array',
        'payment_payload'  => 'array',
    ];

    /** Prefix invoice tiket event (beda dari POS 'MDA-INV-') untuk pemisahan log Tripay. */
    public static function generateInvoiceNo(): string
    {
        do {
            $inv = 'TIX-' . now()->format('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        } while (static::where('invoice_no', $inv)->exists());

        return $inv;
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function affiliate()
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
