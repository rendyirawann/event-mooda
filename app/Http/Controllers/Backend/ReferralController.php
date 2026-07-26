<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/** Dashboard referral & komisi untuk affiliate (10%) & reseller (15%). */
class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless(
            $user->hasRole('affiliate') || $user->hasRole('reseller') || $user->isSuperadmin(),
            403,
            'Halaman ini untuk affiliate / reseller.'
        );

        // Pastikan punya kode referral unik.
        if (! $user->referral_code) {
            do {
                $code = strtoupper(Str::random(6));
            } while (User::where('referral_code', $code)->exists());
            $user->update(['referral_code' => $code]);
        }

        $isReseller = $user->hasRole('reseller') && ! $user->hasRole('affiliate');
        $role = $isReseller ? 'reseller' : ($user->hasRole('affiliate') ? 'affiliate' : 'referral');

        return view('backend.referral.index', [
            'user'         => $user,
            'role'         => $role,
            'rate'         => $isReseller ? 15 : 10,
            'link'         => url('/') . '?ref=' . $user->referral_code,
            'commissions'  => Commission::where('user_id', $user->id)->with('order.event')->latest()->take(100)->get(),
            'totalPending' => (int) Commission::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
            'totalPaid'    => (int) Commission::where('user_id', $user->id)->where('status', 'paid')->sum('amount'),
            'countRef'     => Commission::where('user_id', $user->id)->count(),
        ]);
    }
}
