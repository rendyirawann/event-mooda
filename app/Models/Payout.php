<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'organizer_id', 'amount', 'method', 'account', 'account_name',
        'note', 'status', 'processed_by', 'processed_at',
    ];

    protected $casts = ['processed_at' => 'datetime'];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
}
