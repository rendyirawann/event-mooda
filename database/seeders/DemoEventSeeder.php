<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Data contoh: 1 organizer + beberapa event published (lintas kategori & kota) + jenis tiket.
 * Supaya landing tampil dengan data nyata end-to-end.
 */
class DemoEventSeeder extends Seeder
{
    public function run(): void
    {
        $org = User::firstOrCreate(
            ['email' => 'organizer@eventmooda.id'],
            [
                'name'              => 'Demo Organizer',
                'username'          => 'demo_organizer',
                'password'          => Hash::make('password'),
                'no_wa'             => '081265558044',
                'email_verified_at' => now(),
                'is_active'         => true,
            ],
        );
        if (! $org->hasRole('organizer')) {
            $org->assignRole('organizer');
        }

        // [judul, slugKategori, slugKota, hari_dari_sekarang, venue, featured, tagline, [ [namaTiket, harga, kuota], ... ]]
        $events = [
            ['Soundrenaline Festival 2026', 'musik-konser', 'jakarta',    48, 'GBK Senayan',            true,  'Dua hari penuh musik & energi.',        [['Presale', 250000, 1000], ['Reguler', 350000, 2000], ['VIP', 750000, 300]]],
            ['Java Jazz Night',             'musik-konser', 'bandung',    22, 'Sabuga',                 false, 'Malam jazz intim di Bandung.',          [['Reguler', 200000, 500], ['VVIP', 500000, 100]]],
            ['Startup Summit Indonesia',    'seminar',      'surabaya',   60, 'Grand City Convex',      false, 'Bertemu founder & investor.',           [['Early Bird', 100000, 300], ['Reguler', 150000, 700]]],
            ['Color Run Fest',              'olahraga',     'jakarta',    30, 'Ancol',                  true,  'Lari 5K penuh warna.',                  [['Individu', 120000, 1500], ['Grup 4 Orang', 400000, 200]]],
            ['Creative Workshop Series',    'workshop',     'yogyakarta', 12, 'Jogja Expo Center',      false, 'Belajar langsung dari praktisi.',       [['1 Hari', 85000, 120], ['Full Access', 200000, 60]]],
            ['Pesta Rakyat Kuliner',        'festival',     'semarang',   40, 'Simpang Lima',           true,  'Festival kuliran gratis untuk semua.',  [['Tiket Masuk', 0, 5000]]],
            ['Indie Music Showcase',        'musik-konser', 'malang',     33, 'Kampoeng Kajoetangan',   false, 'Panggung untuk musisi indie lokal.',    [['Presale', 75000, 300], ['Reguler', 95000, 500]]],
            ['Tech & Gaming Expo',          'pameran',      'jakarta',    70, 'ICE BSD',                true,  'Pameran teknologi & gaming terbesar.',  [['1 Hari', 75000, 2000], ['Terusan 3 Hari', 175000, 800]]],
        ];

        foreach ($events as $ev) {
            [$title, $catSlug, $citySlug, $days, $venue, $featured, $tagline, $tickets] = $ev;

            $cat  = EventCategory::where('slug', $catSlug)->first();
            $city = City::where('slug', $citySlug)->first();
            if (! $cat || ! $city) {
                continue;
            }

            $start = now()->addDays($days)->setTime(19, 0);
            $event = Event::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'organizer_id'      => $org->id,
                    'event_category_id' => $cat->id,
                    'city_id'           => $city->id,
                    'title'             => $title,
                    'tagline'           => $tagline,
                    'description'       => $tagline . ' Diselenggarakan di ' . $venue . ', ' . $city->name . '. Amankan tiketmu sekarang!',
                    'venue_name'        => $venue,
                    'venue_address'     => $city->name,
                    'starts_at'         => $start,
                    'ends_at'           => $start->copy()->addHours(5),
                    'status'            => 'published',
                    'is_featured'       => $featured,
                ],
            );

            $min = null;
            foreach ($tickets as $k => [$tname, $price, $quota]) {
                TicketType::updateOrCreate(
                    ['event_id' => $event->id, 'name' => $tname],
                    ['price' => $price, 'quota' => $quota, 'sort_order' => $k, 'is_active' => true],
                );
                $min = is_null($min) ? $price : min($min, $price);
            }
            $event->update(['min_price' => (int) ($min ?? 0)]);
        }

        $this->command->info('Demo: organizer + ' . count($events) . ' event published');
    }
}
