<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Riwayat pembayaran TIKET EVENT (khusus event-mooda) — dari tabel lokal `ticket_orders`.
 * Riwayat Tripay KESELURUHAN (POS + event) ada di app monitor terpisah, bukan di sini.
 */
class TripayHistoryController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isSuperadmin(), 403);

        $status = strtolower((string) $request->query('status', ''));
        $q = TicketOrder::with(['event', 'buyer']);
        if (in_array($status, ['pending', 'paid', 'expired', 'cancelled'], true)) {
            $q->where('status', $status);
        }
        $orders = $q->latest()->paginate(30)->withQueryString();

        $counts = [
            'total'   => TicketOrder::count(),
            'paid'    => TicketOrder::where('status', 'paid')->count(),
            'pending' => TicketOrder::where('status', 'pending')->count(),
            'revenue' => (int) TicketOrder::where('status', 'paid')->sum('total'),
        ];

        return view('backend.superadmin.tripay-history.index', compact('orders', 'counts', 'status'));
    }
}
