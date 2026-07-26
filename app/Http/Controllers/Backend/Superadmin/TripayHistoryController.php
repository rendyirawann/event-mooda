<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use App\Services\Tripay\Tripay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Dashboard riwayat pembayaran Tripay TERPADU (POS + tiket event) — Superadmin.
 * Sumber: API /merchant/transactions (merchant sama). Tipe ditandai dari prefix invoice:
 * TIX- = Tiket Event, selain itu (DSP-DEP-, MDA-INV-, dll) = POS / Mooda.
 */
class TripayHistoryController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isSuperadmin(), 403);

        $tripay  = new Tripay();
        $page    = max(1, (int) $request->query('page', 1));
        $status  = strtoupper((string) $request->query('status', ''));
        $perPage = 25;

        $rows = [];
        $pagination = [];
        $error = null;

        if (! $tripay->isConfigured()) {
            $error = 'Tripay belum dikonfigurasi (cek kredensial di .env).';
        } else {
            $params = ['page' => $page, 'per_page' => $perPage, 'sort' => 'desc'];
            if (in_array($status, ['UNPAID', 'PAID', 'EXPIRED', 'FAILED', 'REFUND'], true)) {
                $params['status'] = $status;
            }
            $res = $tripay->merchantTransactions($params);

            if (! ($res['success'] ?? false)) {
                $error = $res['message'] ?? 'Gagal memuat data dari Tripay. Coba lagi.';
            } else {
                foreach (($res['data'] ?? []) as $tx) {
                    $mref    = (string) ($tx['merchant_ref'] ?? '');
                    $isEvent = str_starts_with(strtoupper($mref), 'TIX-');
                    $rows[] = [
                        'reference'    => (string) ($tx['reference'] ?? ''),
                        'merchant_ref' => $mref,
                        'type'         => $isEvent ? 'event' : 'pos',
                        'type_label'   => $isEvent ? 'Tiket Event' : 'POS / Mooda',
                        'customer'     => (string) ($tx['customer_name'] ?? '-'),
                        'method'       => (string) ($tx['payment_name'] ?? ($tx['payment_method'] ?? '-')),
                        'amount'       => (int) ($tx['amount'] ?? 0),
                        'status'       => strtoupper((string) ($tx['status'] ?? '')),
                        'created_at'   => $this->fmtTime($tx['created_at'] ?? null),
                        'paid_at'      => $this->fmtTime($tx['paid_at'] ?? null),
                    ];
                }
                $pagination = $res['pagination'] ?? [];
            }
        }

        return view('backend.superadmin.tripay-history.index', [
            'rows'       => $rows,
            'pagination' => $pagination,
            'error'      => $error,
            'status'     => $status,
            'production' => $tripay->isProduction(),
        ]);
    }

    private function fmtTime($v): ?string
    {
        if (empty($v)) {
            return null;
        }
        try {
            $c = is_numeric($v)
                ? Carbon::createFromTimestamp((int) $v, 'Asia/Jakarta')
                : Carbon::parse($v)->setTimezone('Asia/Jakarta');

            return $c->format('d M Y H:i');
        } catch (\Throwable $e) {
            return (string) $v;
        }
    }
}
