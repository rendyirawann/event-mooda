<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Pesanan tiket (checkout) — dibayar via gateway (Tripay). Bisa lewat affiliate/reseller.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignUuid('buyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('affiliate_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('reseller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_phone', 30)->nullable();
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('service_fee')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->string('status', 20)->default('pending');   // pending|paid|expired|cancelled|refunded
            $table->string('payment_method')->nullable();
            $table->string('payment_ref')->nullable();          // reference gateway (Tripay)
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
