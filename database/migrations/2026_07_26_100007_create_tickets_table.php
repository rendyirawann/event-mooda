<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// E-ticket individual (satu baris = satu tiket ber-QR) untuk check-in di lokasi.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_order_id')->constrained('ticket_orders')->cascadeOnDelete();
            $table->foreignId('ticket_type_id')->constrained('ticket_types');
            $table->foreignId('event_id')->constrained('events');
            $table->string('code')->unique();                   // payload QR
            $table->string('holder_name')->nullable();
            $table->string('status', 20)->default('valid');     // valid|used|void
            $table->dateTime('checked_in_at')->nullable();
            $table->foreignUuid('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['status', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
