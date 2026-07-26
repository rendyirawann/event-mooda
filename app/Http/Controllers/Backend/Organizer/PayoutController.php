<?php

namespace App\Http\Controllers\Backend\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Pencairan dana organizer (saldo dari penjualan tiket lunas → minta cair). */
class PayoutController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && (Auth::user()->hasRole('organizer') || Auth::user()->isSuperadmin()), 403);
    }

    /** Saldo: pendapatan tiket lunas − yang sudah diminta/dicairkan. */
    private function balance(string $orgId): array
    {
        $gross = (int) TicketOrder::whereHas('event', fn ($q) => $q->where('organizer_id', $orgId))
            ->where('status', 'paid')->sum('total');
        $withdrawn = (int) Payout::where('organizer_id', $orgId)
            ->whereIn('status', ['requested', 'paid'])->sum('amount');

        return ['gross' => $gross, 'withdrawn' => $withdrawn, 'available' => max(0, $gross - $withdrawn)];
    }

    public function index()
    {
        $this->guard();
        $orgId = Auth::id();
        $b = $this->balance($orgId);

        return view('backend.organizer.payout', [
            'gross'     => $b['gross'],
            'withdrawn' => $b['withdrawn'],
            'available' => $b['available'],
            'payouts'   => Payout::where('organizer_id', $orgId)->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->guard();
        $orgId = Auth::id();
        $available = $this->balance($orgId)['available'];

        if ($available < 10000) {
            return back()->with('error', 'Saldo belum cukup untuk pencairan (min Rp10.000).');
        }

        $data = $request->validate([
            'amount'       => ['required', 'integer', 'min:10000', 'max:' . $available],
            'method'       => ['required', 'string', 'max:50'],
            'account'      => ['required', 'string', 'max:60'],
            'account_name' => ['required', 'string', 'max:100'],
            'note'         => ['nullable', 'string', 'max:255'],
        ], ['amount.max' => 'Melebihi saldo tersedia (Rp ' . number_format($available, 0, ',', '.') . ').']);

        $data['organizer_id'] = $orgId;
        $data['status'] = 'requested';
        Payout::create($data);

        return back()->with('success', 'Permintaan pencairan dikirim. Menunggu diproses admin.');
    }
}
