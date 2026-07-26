<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Permintaan pencairan dana hasil penjualan tiket (organizer → diproses Superadmin).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('method')->nullable();        // bank / e-wallet
            $table->string('account')->nullable();        // no rekening / akun
            $table->string('account_name')->nullable();
            $table->text('note')->nullable();
            $table->string('status', 20)->default('requested'); // requested | paid | rejected
            $table->foreignUuid('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['organizer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
