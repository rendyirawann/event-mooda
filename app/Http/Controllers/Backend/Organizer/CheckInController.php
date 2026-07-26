<?php

namespace App\Http\Controllers\Backend\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Check-in e-ticket di lokasi: pindai QR / input kode → tiket valid → used. */
class CheckInController extends Controller
{
    private function authorizeOwner(Event $event): void
    {
        abort_unless(
            Auth::check() && (Auth::user()->isSuperadmin() || $event->organizer_id === Auth::id()),
            403
        );
    }

    public function scanner(Event $event)
    {
        $this->authorizeOwner($event);

        return view('backend.organizer.checkin', [
            'event'   => $event,
            'total'   => $event->tickets()->count(),
            'checked' => $event->tickets()->where('status', 'used')->count(),
        ]);
    }

    public function check(Request $request, Event $event)
    {
        $this->authorizeOwner($event);
        $code = trim((string) $request->input('code'));

        $recount = fn () => [
            'total'   => $event->tickets()->count(),
            'checked' => $event->tickets()->where('status', 'used')->count(),
        ];

        if ($code === '') {
            return response()->json(['status' => 'invalid', 'message' => 'Kode tiket kosong.'] + $recount());
        }

        $ticket = Ticket::where('code', $code)->where('event_id', $event->id)->with('ticketType')->first();

        if (! $ticket) {
            return response()->json(['status' => 'invalid', 'message' => 'Tiket tidak ditemukan atau bukan untuk event ini.'] + $recount());
        }
        if ($ticket->status === 'void') {
            return response()->json(['status' => 'invalid', 'message' => 'Tiket dibatalkan (void).'] + $recount());
        }
        if ($ticket->status === 'used') {
            return response()->json([
                'status'  => 'used',
                'message' => 'Tiket SUDAH check-in ' . ($ticket->checked_in_at?->timezone('Asia/Jakarta')->format('d M H:i') ?? ''),
                'ticket'  => ['holder' => $ticket->holder_name, 'type' => $ticket->ticketType?->name, 'code' => $ticket->code],
            ] + $recount());
        }

        $ticket->update([
            'status'        => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => Auth::id(),
        ]);

        return response()->json([
            'status'  => 'valid',
            'message' => 'Check-in berhasil!',
            'ticket'  => ['holder' => $ticket->holder_name, 'type' => $ticket->ticketType?->name, 'code' => $ticket->code],
        ] + $recount());
    }
}
