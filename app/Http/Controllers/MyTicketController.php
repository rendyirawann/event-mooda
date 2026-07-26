<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use Illuminate\Support\Facades\Auth;

/** Tiket saya: daftar pesanan lunas + e-ticket ber-QR. */
class MyTicketController extends Controller
{
    public function index()
    {
        $orders = TicketOrder::where('buyer_id', Auth::id())
            ->where('status', 'paid')
            ->with('event')
            ->latest('paid_at')
            ->get();

        return view('public.my-tickets', ['orders' => $orders]);
    }

    public function show(TicketOrder $order)
    {
        abort_unless($order->buyer_id === Auth::id() || Auth::user()->isSuperadmin(), 403);

        if ($order->status !== 'paid') {
            return redirect()->route('checkout.show', $order)->with('error', 'Pesanan ini belum dibayar.');
        }

        $order->load(['event.city', 'tickets.ticketType']);

        return view('public.ticket-order', ['order' => $order]);
    }
}
