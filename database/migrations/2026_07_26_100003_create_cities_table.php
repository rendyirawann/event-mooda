<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Master kota (milik provinsi) + gambar/ikon monumen kota untuk tampil di landing.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('landmark_image')->nullable();       // foto monumen (upload Superadmin)
            $table->string('landmark_emoji', 16)->nullable();   // fallback ikon monumen
            $table->string('color', 40)->default('#6366f1,#ec4899');
            $table->boolean('is_featured')->default(false);     // tampil di seksi Kota landing
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
