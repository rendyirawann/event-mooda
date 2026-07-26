<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quota', 'sold',
        'max_per_order', 'sales_start', 'sales_end', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'sales_start' => 'datetime',
        'sales_end'   => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function remaining(): int
    {
        return max(0, (int) $this->quota - (int) $this->sold);
    }

    public function isSoldOut(): bool
    {
        return $this->remaining() <= 0;
    }
}
