<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'event_category_id', 'city_id', 'title', 'slug', 'tagline',
        'description', 'poster_image', 'banner_image', 'venue_name', 'venue_address',
        'starts_at', 'ends_at', 'status', 'is_featured', 'min_price', 'views',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'ends_at'     => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    /** Harga tiket termurah (pakai min_price bila ada, else hitung dari jenis tiket). */
    public function priceFrom(): int
    {
        return (int) ($this->min_price ?: ($this->ticketTypes()->min('price') ?? 0));
    }

    public function posterUrl(): ?string
    {
        return $this->poster_image ? asset('storage/' . $this->poster_image) : null;
    }

    /** Gradient poster (ikut warna kategori) untuk placeholder saat belum ada poster. */
    public function gradient(): string
    {
        return $this->category?->gradient() ?? 'linear-gradient(135deg,#7c3aed,#ec4899)';
    }
}
