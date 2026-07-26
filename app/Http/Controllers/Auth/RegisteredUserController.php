<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Pra-isi kode referral dari ?ref= atau cookie (bila datang lewat link referral).
        $ref = $request->query('ref') ?: $request->cookie(config('affiliate.cookie_name', 'mooda_ref'));
        return view('auth.register', ['ref' => $ref]);
    }

    /**
     * Handle an incoming registration request.
     * Membuat 1 TENANT baru + user OWNER. Status langganan = inactive (terkunci)
     * sampai owner menyelesaikan pembayaran di halaman billing (Midtrans).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'role'          => ['nullable', 'in:buyer,organizer'],
            'email'         => [
                'required', 'string', 'lowercase', 'email:rfc', 'max:255',
                'regex:/^[a-z0-9]+([._-][a-z0-9]+)*@[a-z0-9]+([.-][a-z0-9]+)*\.[a-z]{2,}$/',
                'unique:' . User::class,
                function ($attribute, $value, $fail) {
                    if (substr_count(Str::before($value, '@'), '.') > 2) {
                        $fail('Email jangan pakai terlalu banyak titik (maksimal 2 sebelum tanda @).');
                    }
                },
            ],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.regex' => 'Format email tidak valid.',
        ]);

        // Event Mooda: daftar sebagai Pembeli (default) atau Penyelenggara — TANPA tenant/bisnis.
        $role = in_array($request->role, ['buyer', 'organizer'], true) ? $request->role : 'buyer';

        $user = DB::transaction(function () use ($request, $role) {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'username'  => $this->uniqueUsername($request->email),
                'no_wa'     => $request->phone,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
                'is_active' => true,
            ]);
            $user->assignRole($role);

            return $user;
        });

        $user->sendEmailVerificationNotification();
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('status', 'Akun berhasil dibuat! Kami mengirim link aktivasi ke email Anda (' . $user->email . '). Klik link tersebut untuk mengaktifkan akun.');
    }

    /** Catat pemakaian kode referral (cookie mooda_ref) oleh tenant yang baru daftar. */
    private function attachReferral(Tenant $tenant, Request $request): void
    {
        try {
            // Prioritas: kode yang diketik/terisi di form; fallback ke cookie link referral.
            $code = trim((string) ($request->input('ref') ?: $request->cookie(config('affiliate.cookie_name', 'mooda_ref'))));
            if ($code === '') {
                return;
            }
            $affiliate = \App\Models\Affiliate::whereRaw('UPPER(code) = ?', [strtoupper($code)])->first();
            if (! $affiliate) {
                return;
            }
            \App\Models\Referral::firstOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'affiliate_id' => $affiliate->id,
                    'tenant_name'  => $tenant->name,
                    'status'       => 'signup',
                ]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('attachReferral gagal: ' . $e->getMessage());
        }
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'tenant';
        $slug = $base;
        $i = 1;
        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function uniqueUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '_') ?: 'user';
        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }
        return $username;
    }
}
