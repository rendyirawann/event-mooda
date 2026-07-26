<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'ticket_order_id', 'user_id', 'role', 'base_amount', 'rate', 'amount', 'status',
    ];

    protected $casts = ['rate' => 'decimal:2'];

    public function order()
    {
        return $this->belongsTo(TicketOrder::class, 'ticket_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
