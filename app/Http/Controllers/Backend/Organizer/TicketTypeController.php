<?php

namespace App\Http\Controllers\Backend\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Jenis tiket per event — dikelola dari halaman edit event organizer. */
class TicketTypeController extends Controller
{
    private function guardOwner(Event $event): void
    {
        abort_unless(
            Auth::check() && (Auth::user()->isSuperadmin() || $event->organizer_id === Auth::id()),
            403
        );
    }

    public function store(Request $request, Event $event)
    {
        $this->guardOwner($event);
        $data = $this->validated($request);
        $data['max_per_order'] = (int) ($data['max_per_order'] ?? 10);
        $data['sort_order']    = (int) ($data['sort_order'] ?? ($event->ticketTypes()->max('sort_order') + 1));
        $data['is_active']     = $request->boolean('is_active', true);
        $event->ticketTypes()->create($data);
        $this->recalcMinPrice($event);

        return back()->with('success', 'Jenis tiket ditambahkan.');
    }

    public function update(Request $request, Event $event, TicketType $ticketType)
    {
        $this->guardOwner($event);
        abort_unless($ticketType->event_id === $event->id, 404);
        $data = $this->validated($request);
        $data['max_per_order'] = (int) ($data['max_per_order'] ?? $ticketType->max_per_order);
        $data['is_active']     = $request->boolean('is_active', true);
        $ticketType->update($data);
        $this->recalcMinPrice($event);

        return back()->with('success', 'Jenis tiket diperbarui.');
    }

    public function destroy(Event $event, TicketType $ticketType)
    {
        $this->guardOwner($event);
        abort_unless($ticketType->event_id === $event->id, 404);
        if ($ticketType->sold > 0) {
            return back()->with('error', 'Tidak bisa dihapus: tiket ini sudah ada yang terjual.');
        }
        $ticketType->delete();
        $this->recalcMinPrice($event);

        return back()->with('success', 'Jenis tiket dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:255'],
            'price'         => ['required', 'integer', 'min:0'],
            'quota'         => ['required', 'integer', 'min:0'],
            'max_per_order' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sales_start'   => ['nullable', 'date'],
            'sales_end'     => ['nullable', 'date', 'after_or_equal:sales_start'],
            'sort_order'    => ['nullable', 'integer', 'min:0'],
        ]);
    }

    /** Selaraskan harga termurah event dari jenis tiket aktif. */
    private function recalcMinPrice(Event $event): void
    {
        $min = $event->ticketTypes()->where('is_active', true)->min('price');
        $event->update(['min_price' => (int) ($min ?? 0)]);
    }
}
