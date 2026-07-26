<?php

namespace App\Http\Controllers;

use App\Models\Event;

/** Halaman publik detail event (dari landing → klik event). */
class PublicEventController extends Controller
{
    public function show(string $slug)
    {
        $event = Event::published()->where('slug', $slug)
            ->with([
                'category',
                'city.province',
                'ticketTypes' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            ])
            ->firstOrFail();

        $event->increment('views');

        return view('public.event', ['event' => $event]);
    }
}
