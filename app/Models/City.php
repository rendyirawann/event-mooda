<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'province_id', 'name', 'slug', 'landmark_image', 'landmark_emoji',
        'color', 'is_featured', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_active', true)->where('is_featured', true)
            ->orderBy('sort_order')->orderBy('name');
    }

    public function gradient(): string
    {
        return 'linear-gradient(135deg,' . ($this->color ?: '#6366f1,#ec4899') . ')';
    }

    /** URL foto monumen bila diunggah, else null (view pakai emoji fallback). */
    public function landmarkUrl(): ?string
    {
        return $this->landmark_image ? asset('storage/' . $this->landmark_image) : null;
    }

    public function publishedEventsCount(): int
    {
        return $this->events()->where('status', 'published')->count();
    }
}
