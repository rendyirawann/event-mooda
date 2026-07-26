<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

/** Halaman publik detail event (dari landing → klik event). */
class PublicEventController extends Controller
{
    public function show(Request $request, string $slug)
    {
        // Simpan kode referral (affiliate/reseller) bila datang via ?ref=KODE.
        if ($ref = $request->query('ref')) {
            session(['ref_code' => substr((string) $ref, 0, 20)]);
        }

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
