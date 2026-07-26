<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'color', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    /** CSS gradient dari 2 warna tersimpan (mis. "#7c3aed,#ec4899"). */
    public function gradient(): string
    {
        return 'linear-gradient(135deg,' . ($this->color ?: '#7c3aed,#ec4899') . ')';
    }
}
