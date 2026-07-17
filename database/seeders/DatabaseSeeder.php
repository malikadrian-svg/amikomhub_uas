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
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@amikom.ac.id'],
            [
                'name' => 'HIMA Informatika',
                'password' => Hash::make('password'),
                'role' => 'organizer',
            ]
        );

        // 2. Kategori Event 
        $categories = [
            'Concert' => Category::firstOrCreate(['name' => 'Konser Musik', 'slug' => 'konser-musik']),
            'Web' => Category::firstOrCreate(['name' => 'Web Development', 'slug' => 'web-development']),
            'Design' => Category::firstOrCreate(['name' => 'UI/UX Design', 'slug' => 'ui-ux-design']),
            'Mobile' => Category::firstOrCreate(['name' => 'Mobile Development', 'slug' => 'mobile-development']),
            'Security' => Category::firstOrCreate(['name' => 'Cyber Security', 'slug' => 'cyber-security']),
        ];

        // 3. Events 
        $events = [
            // Konser Musik
            [
                'category_id' => $categories['Concert']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Amikom Fest 2026',
                'description' => 'Konser musik tahunan terbesar di Universitas AMIKOM Yogyakarta. Menampilkan deretan musisi papan atas nasional dan komunitas musik mahasiswa. Nikmati panggung megah, bazar kuliner, dan malam yang tak terlupakan!',
                'date' => '2026-09-10 18:00:00',
                'location' => 'Lapangan Depan AMIKOM',
                'price' => 75000,
                'stock' => 500,
                'poster_path' => 'posters/event-concert1.png',
                'status' => 'approved',
            ],
            [
                'category_id' => $categories['Concert']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Jazz Afternoon & Akustik Santai',
                'description' => 'Sore santai menikmati musik jazz dan akustik di halaman taman utama kampus. Terbuka gratis untuk seluruh mahasiswa AMIKOM dan umum. Dapatkan kopi gratis untuk 100 pendaftar pertama!',
                'date' => '2026-09-25 19:30:00',
                'location' => 'Taman Utama AMIKOM',
                'price' => 0,
                'stock' => 200,
                'poster_path' => 'posters/event-concert2.png',
                'status' => 'approved',
            ],
            // Web Development
            [
                'category_id' => $categories['Web']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Modern Frontend with Next.js 15 & Tailwind',
                'description' => 'Masterclass intensif membedah fitur-fitur mutakhir Next.js 15 seperti Server Components lanjutan, Server Actions untuk mutasi data, dan teknik styling modern menggunakan Tailwind CSS. Dilengkapi e-certificate.',
                'date' => '2026-10-05 09:00:00',
                'location' => 'Ruang Cinema AMIKOM',
                'price' => 35000,
                'stock' => 150,
                'poster_path' => 'posters/event-web1.png',
                'status' => 'approved',
            ],
            [
                'category_id' => $categories['Web']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Getting Started with Web Development',
                'description' => 'Seminar dasar pemrograman web untuk pemula. Belajar memahami HTML5, CSS3, dan dasar-dasar JavaScript untuk membangun website pertama Anda dari nol. Cocok bagi mahasiswa baru dari jurusan manapun.',
                'date' => '2026-10-12 13:00:00',
                'location' => 'Aula Gedung 3 AMIKOM',
                'price' => 0,
                'stock' => 250,
                'poster_path' => 'posters/event-web2.png',
                'status' => 'approved',
            ],
            // UI/UX Design
            [
                'category_id' => $categories['Design']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Advanced Figma Design System Workshop',
                'description' => 'Workshop praktis menguasai pembuatan Variable, Component States, Auto Layout 5.0, dan Design Token menggunakan Figma. Belajar menyusun design system yang scalable dan siap diserahterimakan ke developer (handoff).',
                'date' => '2026-10-20 09:00:00',
                'location' => 'Laboratorium Multimedia AMIKOM',
                'price' => 45000,
                'stock' => 80,
                'poster_path' => 'posters/event-design1.png',
                'status' => 'approved',
            ],
            [
                'category_id' => $categories['Design']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Introduction to User Research & UX Methods',
                'description' => 'Belajar cara melakukan riset pengguna yang tepat untuk mengidentifikasi kebutuhan user. Membahas empathy mapping, customer journey, hingga metode usability testing sederhana agar produk digital Anda disukai.',
                'date' => '2026-10-27 13:00:00',
                'location' => 'Ruang Seminar Gedung 4 AMIKOM',
                'price' => 0,
                'stock' => 120,
                'poster_path' => 'posters/event-design2.png',
                'status' => 'approved',
            ],
            // Mobile Development
            [
                'category_id' => $categories['Mobile']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Flutter Cross-Platform Production Architecture',
                'description' => 'Seminar dan live coding mendesain arsitektur aplikasi Flutter yang clean dan mudah ditest menggunakan BLoC state management dan Clean Architecture. Dirancang untuk tingkat intermediate.',
                'date' => '2026-11-03 09:00:00',
                'location' => 'Ruang Cinema AMIKOM',
                'price' => 50000,
                'stock' => 120,
                'poster_path' => 'posters/event-mobile1.png',
                'status' => 'approved',
            ],
            [
                'category_id' => $categories['Mobile']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Introduction to Jetpack Compose for Android',
                'description' => 'Ingin belajar bikin aplikasi native Android? Yuk gabung di webinar ini untuk mempelajari Jetpack Compose, framework modern bentukan Google untuk mendesain UI Android secara deklaratif menggunakan Kotlin.',
                'date' => '2026-11-10 13:00:00',
                'location' => 'Webinar Zoom Meeting',
                'price' => 0,
                'stock' => 150,
                'poster_path' => 'posters/event-mobile2.png',
                'status' => 'approved',
            ],
            // Cyber Security
            [
                'category_id' => $categories['Security']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Ethical Hacking & Web Vulnerability Assessment',
                'description' => 'Hands-on lab melakukan penetrasi keamanan aplikasi web menggunakan OWASP Top 10 framework. Belajar cara mendeteksi kerentanan SQL Injection, XSS, dan cara melakukan patching keamanan secara efektif.',
                'date' => '2026-11-18 09:00:00',
                'location' => 'Amikom Convention Hall',
                'price' => 60000,
                'stock' => 100,
                'poster_path' => 'posters/event-security1.png',
                'status' => 'approved',
            ],
            [
                'category_id' => $categories['Security']->id,
                'organizer_id' => $organizer->id,
                'title' => 'Cyber Security Awareness: Guarding Your Digital Life',
                'description' => 'Webinar edukatif mengenai pentingnya keamanan informasi di era digital. Pelajari cara mengenali phishing, mengamankan akun sosial media, dan melindungi data pribadi dari kebocoran data di internet.',
                'date' => '2026-11-25 13:00:00',
                'location' => 'Auditorium Gedung 5 AMIKOM',
                'price' => 0,
                'stock' => 500,
                'poster_path' => 'posters/event-security2.png',
                'status' => 'approved',
            ],
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
