<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Musik & Konser', '🎵', '#7c3aed,#ec4899'],
            ['Festival',       '🎪', '#f97316,#ef4444'],
            ['Olahraga',       '⚽', '#22c55e,#0ea5e9'],
            ['Seminar',        '🎤', '#0ea5e9,#6366f1'],
            ['Workshop',       '🛠️', '#f59e0b,#f97316'],
            ['Teater & Seni',  '🎭', '#ec4899,#8b5cf6'],
            ['Komunitas',      '👥', '#14b8a6,#22c55e'],
            ['Pameran',        '🖼️', '#6366f1,#ec4899'],
        ];

        foreach ($categories as $i => [$name, $icon, $color]) {
            EventCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'icon' => $icon, 'color' => $color, 'sort_order' => $i, 'is_active' => true],
            );
        }

        $this->command->info('Kategori event: ' . count($categories) . ' item');
    }
}
