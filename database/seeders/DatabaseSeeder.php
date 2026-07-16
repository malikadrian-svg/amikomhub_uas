<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\Partner;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // Akun Organizer Default untuk Pengujian
        User::firstOrCreate(
            ['email' => 'organizer@amikom.ac.id'],
            [
                'name' => 'HIMA Informatika',
                'password' => Hash::make('password'),
                'role' => 'organizer',
            ]
        );

        // 2. Kategori Event 
        $categories = [
            'IT' => Category::firstOrCreate(['name' => 'Seminar IT', 'slug' => 'seminar-it']),
            'Entertain' => Category::firstOrCreate(['name' => 'Entertainment', 'slug' => 'entertainment']),
            'Business' => Category::firstOrCreate(['name' => 'Bisnis & Kewirausahaan', 'slug' => 'bisnis-kewirausahaan']),
            'Culinary' => Category::firstOrCreate(['name' => 'Bazaar Kuliner Nusantara', 'slug' => 'bazaar-kuliner-nusantara']),
            'Esport' => Category::firstOrCreate(['name' => 'E-Sport Tournament', 'slug' => 'esport-tournament']),
        ];

        // 3. Events 
        $events = [
            [
                'category_id' => $categories['IT']->id,
                'title' => 'AI Summit & Expo 2026',
                'description' => 'Jelajahi tren terkini dalam bidang Artificial Intelligence',
                'date' => '2026-05-01 13:00:00',
                'location' => 'Ruang Cinema',
                'price' => 45000,
                'stock' => 150,
                'poster_path' => 'posters/event-2.png',
            ],
            [
                'category_id' => $categories['Entertain']->id,
                'title' => 'Jazz Night 2025',
                'description' => 'Nikmati malam yang indah dengan alunan musik.',
                'date' => '2026-05-10 19:00:00',
                'location' => 'Amikom Baru',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'posters/event-1.png',
            ],
            [
                'category_id' => $categories['Business']->id,
                'title' => 'Startup Masterclass 101',
                'description' => 'Pelajari cara membangun startup dari nol bersama para founder sukses. Cocok untuk mahasiswa yang ingin merintis usaha.',
                'date' => '2026-06-01 09:00:00',
                'location' => 'Ruang Cinema Amikom',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'posters/event-startup.png',
            ],
            [
                'category_id' => $categories['Business']->id,
                'title' => 'UMKM Go Digital Summit',
                'description' => 'Strategi digital marketing dan pemanfaatan platform online untuk meningkatkan omset bisnis UMKM secara signifikan.',
                'date' => '2026-06-15 13:00:00',
                'location' => 'Amikom Convention Hall',
                'price' => 35000,
                'stock' => 200,
                'poster_path' => 'posters/event-umkm.png',
            ],
            [
                'category_id' => $categories['Culinary']->id,
                'title' => 'Jogja Food Festival 2026',
                'description' => 'Nikmati ratusan stand makanan tradisional hingga modern dari seluruh penjuru Yogyakarta dalam satu lokasi.',
                'date' => '2026-07-10 16:00:00',
                'location' => 'Halaman Parkir Amikom',
                'price' => 15000,
                'stock' => 1000,
                'poster_path' => 'posters/event-foodfest.png',
            ],
            [
                'category_id' => $categories['Culinary']->id,
                'title' => 'Amikom Cooking Competition',
                'description' => 'Adu bakat memasak antar mahasiswa! Ciptakan hidangan terbaikmu dan menangkan total hadiah jutaan rupiah.',
                'date' => '2026-07-12 08:00:00',
                'location' => 'Selasar Gedung 6',
                'price' => 25000,
                'stock' => 50,
                'poster_path' => 'posters/event-cooking.png',
            ],
            [
                'category_id' => $categories['Esport']->id,
                'title' => 'Valorant U-Champ Amikom',
                'description' => 'Turnamen Valorant tingkat universitas! Bentuk tim terbaikmu dan rebut total hadiah jutaan Rupiah.',
                'date' => '2026-08-05 10:00:00',
                'location' => 'Amikom E-Sport Arena',
                'price' => 100000,
                'stock' => 32,
                'poster_path' => 'posters/event-valorant.png',
            ],
            [
                'category_id' => $categories['Esport']->id,
                'title' => 'Mobile Legends AMIKOM League',
                'description' => 'Adu kemampuan mekanik tim kamu di Land of Dawn! Acara e-sport paling ditunggu-tunggu tahun ini.',
                'date' => '2026-08-12 09:00:00',
                'location' => 'Amikom E-Sport Arena',
                'price' => 50000,
                'stock' => 64,
                'poster_path' => 'posters/event-mlbb.png',
            ]
        ];

        // Eksekusi Insert Events
        foreach ($events as $event) {
            Event::firstOrCreate(['title' => $event['title']], $event);
        }

        // 4. Partners
        $partners = [
            ['name' => 'PD Oktaviani Farida (Persero) Tbk', 'logo_url' => 'https://via.placeholder.com/100'],
            ['name' => 'Yayasan Pratiwi Tbk', 'logo_url' => 'https://via.placeholder.com/100'],
            ['name' => 'PT Prasasta Prakasa (Persero) Tbk', 'logo_url' => 'https://via.placeholder.com/100'],
            ['name' => 'PD Tampubolon Rahmawati', 'logo_url' => 'https://via.placeholder.com/100'],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(['name' => $partner['name']], $partner);
        }
    }
}
