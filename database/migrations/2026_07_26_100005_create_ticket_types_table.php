<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Jenis tiket per event (Reguler, VIP, Early Bird) — harga & kuota.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('quota')->default(0);
            $table->unsignedInteger('sold')->default(0);
            $table->unsignedInteger('max_per_order')->default(10);
            $table->dateTime('sales_start')->nullable();
            $table->dateTime('sales_end')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['event_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
