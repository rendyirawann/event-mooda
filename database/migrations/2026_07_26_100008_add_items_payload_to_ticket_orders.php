<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Rincian item pesanan (jenis tiket + qty) & payload respons gateway (pay_code/qr_url/checkout_url).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->json('items')->nullable()->after('reseller_id');
            $table->json('payment_payload')->nullable()->after('payment_ref');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropColumn(['items', 'payment_payload']);
        });
    }
};
