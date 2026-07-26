<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Komisi affiliate/reseller atas pesanan tiket yang lunas.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_order_id')->constrained('ticket_orders')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 20);                 // affiliate | reseller
            $table->unsignedBigInteger('base_amount');   // total pesanan
            $table->decimal('rate', 5, 2);               // persen komisi
            $table->unsignedBigInteger('amount');        // nilai komisi
            $table->string('status', 20)->default('pending'); // pending | paid
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
