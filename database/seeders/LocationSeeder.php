<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Master provinsi & kota. Emoji monumen = placeholder; foto asli diunggah via
 * Superadmin (kolom landmark_image). is_featured -> tampil di seksi "Kota" landing.
 */
class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // [Provinsi, [ [Kota, emojiMonumen, gradient, featured], ... ]]
        $data = [
            ['DKI Jakarta',       [['Jakarta',    '🗼', '#7c3aed,#ec4899', true]]],
            ['Jawa Barat',        [['Bandung',    '🏛️', '#0ea5e9,#6366f1', true], ['Bogor', '⛰️', '#22c55e,#14b8a6', false]]],
            ['DI Yogyakarta',     [['Yogyakarta', '🛕', '#f59e0b,#f97316', true]]],
            ['Jawa Tengah',       [['Semarang',   '🏛️', '#6366f1,#8b5cf6', true], ['Solo', '👑', '#ec4899,#f97316', false]]],
            ['Jawa Timur',        [['Surabaya',   '🦈', '#0ea5e9,#22c55e', true], ['Malang', '⛰️', '#14b8a6,#0ea5e9', false]]],
            ['Bali',              [['Denpasar',   '🛕', '#f97316,#ef4444', true]]],
            ['Sumatera Utara',    [['Medan',      '🕌', '#22c55e,#0ea5e9', true]]],
            ['Sulawesi Selatan',  [['Makassar',   '⚓', '#6366f1,#ec4899', true]]],
        ];

        foreach ($data as $i => [$prov, $cities]) {
            $p = Province::updateOrCreate(['slug' => Str::slug($prov)], ['name' => $prov]);
            foreach ($cities as $j => [$cname, $emoji, $color, $featured]) {
                City::updateOrCreate(
                    ['slug' => Str::slug($cname)],
                    [
                        'province_id'    => $p->id,
                        'name'           => $cname,
                        'landmark_emoji' => $emoji,
                        'color'          => $color,
                        'is_featured'    => $featured,
                        'sort_order'     => $i * 10 + $j,
                        'is_active'      => true,
                    ],
                );
            }
        }

        $this->command->info('Provinsi: ' . count($data) . ', Kota: ' . City::count());
    }
}
