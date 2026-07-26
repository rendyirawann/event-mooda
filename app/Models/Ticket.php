<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_order_id', 'ticket_type_id', 'event_id', 'code',
        'holder_name', 'status', 'checked_in_at', 'checked_in_by',
    ];

    protected $casts = ['checked_in_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(TicketOrder::class, 'ticket_order_id');
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }
}
