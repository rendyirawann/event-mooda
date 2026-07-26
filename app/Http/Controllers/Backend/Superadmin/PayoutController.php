<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Kelola permintaan pencairan (Superadmin): tandai dibayar / tolak. */
class PayoutController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isSuperadmin(), 403);
    }

    public function index()
    {
        $this->guard();

        return view('backend.superadmin.payouts', [
            'payouts' => Payout::with('organizer')->latest()->get(),
        ]);
    }

    public function update(Request $request, Payout $payout)
    {
        $this->guard();
        $status = $request->validate(['status' => ['required', 'in:paid,rejected']])['status'];
        $payout->update([
            'status'       => $status,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Status pencairan diperbarui menjadi: ' . $status);
    }
}
