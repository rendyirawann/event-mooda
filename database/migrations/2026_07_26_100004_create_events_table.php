<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Event/acara — dibuat organizer, punya kategori & kota, berisi jenis tiket.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_category_id')->constrained('event_categories');
            $table->foreignId('city_id')->constrained('cities');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->longText('description')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->string('status', 20)->default('draft');       // draft|published|archived
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('min_price')->default(0);  // harga termurah (denormalisasi)
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->index(['status', 'is_featured']);
            $table->index('starts_at');
            $table->index('city_id');
            $table->index('event_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
