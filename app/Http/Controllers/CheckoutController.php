<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use App\Models\TicketType;
use App\Services\Tripay\Tripay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Checkout tiket event → pembayaran via Tripay (Closed Payment, prefix invoice TIX-).
 * Konfirmasi lewat POLLING /transaction/detail (bukan callback) → tidak menyentuh stakko/POS.
 */
class CheckoutController extends Controller
{
    /** 1) Buat pesanan (pending) dari form pilih tiket di halaman event. */
    public function order(Request $request, Event $event)
    {
        $data  = $request->validate(['qty' => ['required', 'array'], 'qty.*' => ['nullable', 'integer', 'min:0', 'max:100']]);
        $types = $event->ticketTypes()->where('is_active', true)->get()->keyBy('id');

        $items = [];
        $subtotal = 0;
        foreach ($data['qty'] as $ttId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }
            $tt = $types->get($ttId);
            if (! $tt) {
                continue;
            }
            if ($qty > $tt->max_per_order) {
                return back()->with('error', "Maksimal {$tt->max_per_order} tiket untuk {$tt->name}.");
            }
            if ($qty > $tt->remaining()) {
                return back()->with('error', "Sisa tiket {$tt->name} tinggal {$tt->remaining()}.");
            }
            $items[] = ['ticket_type_id' => $tt->id, 'name' => $tt->name, 'price' => (int) $tt->price, 'qty' => $qty];
            $subtotal += (int) $tt->price * $qty;
        }
        if (empty($items)) {
            return back()->with('error', 'Pilih minimal 1 tiket.');
        }

        $user  = Auth::user();
        $order = TicketOrder::create([
            'invoice_no'  => TicketOrder::generateInvoiceNo(),
            'event_id'    => $event->id,
            'buyer_id'    => $user->id,
            'items'       => $items,
            'buyer_name'  => $user->name,
            'buyer_email' => $user->email,
            'buyer_phone' => $user->no_wa ?: $user->phone,
            'subtotal'    => $subtotal,
            'service_fee' => 0,
            'total'       => $subtotal,
            'status'      => 'pending',
            'expired_at'  => now()->addHours(24),
        ]);

        return redirect()->route('checkout.show', $order);
    }

    /** 2) Ringkasan pesanan + pilih metode pembayaran (channel Tripay live). */
    public function show(TicketOrder $order)
    {
        $this->authorizeOwner($order);
        if ($order->status === 'paid') {
            return redirect()->route('my-tickets.show', $order);
        }
        $order->load('event.city');
        $tripay   = new Tripay();
        $channels = $tripay->isConfigured() ? $tripay->paymentChannels() : [];

        return view('public.checkout', ['order' => $order, 'channels' => $channels]);
    }

    /** 3) Proses bayar: buat transaksi Tripay (atau langsung terbit bila gratis). */
    public function pay(Request $request, TicketOrder $order)
    {
        $this->authorizeOwner($order);
        if ($order->status !== 'pending') {
            return redirect()->route('checkout.show', $order);
        }

        // Tiket gratis → terbitkan langsung tanpa gateway.
        if ((int) $order->total <= 0) {
            $this->finalize($order);

            return redirect()->route('my-tickets.show', $order)->with('success', 'Tiket gratis berhasil diterbitkan!');
        }

        $method = $request->validate(['method' => ['required', 'string', 'max:40']])['method'];
        $tripay = new Tripay();
        if (! $tripay->isConfigured()) {
            return back()->with('error', 'Pembayaran belum dikonfigurasi. Hubungi admin.');
        }
        if (! $tripay->channelActive($method)) {
            return back()->with('error', 'Metode pembayaran tidak tersedia. Pilih yang lain.');
        }

        $orderItems = array_map(fn ($i) => [
            'name' => $i['name'], 'price' => (int) $i['price'], 'quantity' => (int) $i['qty'],
        ], $order->items);

        $res = $tripay->createClosedTransaction([
            'method'         => $method,
            'merchant_ref'   => $order->invoice_no,
            'amount'         => (int) $order->total,
            'customer_name'  => $order->buyer_name ?: 'Pelanggan',
            'customer_email' => $order->buyer_email ?: 'noemail@event.mooda.id',
            'customer_phone' => $order->buyer_phone ?: '',
            'order_items'    => $orderItems,
            'return_url'     => route('my-tickets.show', $order),
        ]);

        if (! ($res['success'] ?? false) || empty($res['data']['reference'])) {
            return back()->with('error', 'Gagal membuat transaksi: ' . ($res['message'] ?? 'coba metode lain.'));
        }

        $order->update([
            'payment_method'  => $method,
            'payment_ref'     => $res['data']['reference'],
            'payment_payload' => $res['data'],
        ]);

        return redirect()->route('checkout.payment', $order);
    }

    /** 4) Halaman pembayaran: tampilkan VA/QRIS + polling status. */
    public function payment(TicketOrder $order)
    {
        $this->authorizeOwner($order);
        if ($order->status === 'paid') {
            return redirect()->route('my-tickets.show', $order);
        }
        if (! $order->payment_ref) {
            return redirect()->route('checkout.show', $order);
        }
        $order->load('event');

        return view('public.payment', ['order' => $order, 'pay' => $order->payment_payload ?? []]);
    }

    /** 5) Endpoint polling (JSON) — dipanggil berkala oleh halaman pembayaran. */
    public function status(TicketOrder $order)
    {
        $this->authorizeOwner($order);
        if ($order->status === 'paid') {
            return response()->json(['status' => 'paid', 'redirect' => route('my-tickets.show', $order)]);
        }
        if ($order->payment_ref) {
            $detail = (new Tripay())->transactionDetail($order->payment_ref);
            $st = strtoupper((string) data_get($detail, 'data.status', ''));
            if ($st === 'PAID') {
                $this->finalize($order);

                return response()->json(['status' => 'paid', 'redirect' => route('my-tickets.show', $order)]);
            }
            if (in_array($st, ['EXPIRED', 'FAILED', 'REFUND'], true)) {
                $order->update(['status' => $st === 'EXPIRED' ? 'expired' : 'cancelled']);

                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'pending']);
    }

    /** Terbitkan tiket + tandai lunas + tambah 'sold' (idempotent, aman dari polling ganda). */
    private function finalize(TicketOrder $order): void
    {
        DB::transaction(function () use ($order) {
            $fresh = TicketOrder::whereKey($order->id)->lockForUpdate()->first();
            if (! $fresh || $fresh->status === 'paid') {
                return;
            }
            foreach ((array) $fresh->items as $it) {
                $tt = TicketType::find($it['ticket_type_id']);
                if (! $tt) {
                    continue;
                }
                for ($i = 0; $i < (int) $it['qty']; $i++) {
                    Ticket::create([
                        'ticket_order_id' => $fresh->id,
                        'ticket_type_id'  => $tt->id,
                        'event_id'        => $fresh->event_id,
                        'code'            => $this->ticketCode(),
                        'holder_name'     => $fresh->buyer_name,
                        'status'          => 'valid',
                    ]);
                }
                $tt->increment('sold', (int) $it['qty']);
            }
            $fresh->update(['status' => 'paid', 'paid_at' => now()]);
        });
    }

    private function ticketCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(Str::random(10));
        } while (Ticket::where('code', $code)->exists());

        return $code;
    }

    private function authorizeOwner(TicketOrder $order): void
    {
        abort_unless(Auth::check() && ($order->buyer_id === Auth::id() || Auth::user()->isSuperadmin()), 403);
    }
}
