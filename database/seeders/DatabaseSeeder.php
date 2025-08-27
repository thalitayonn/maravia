<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Photo;
use App\Models\Page;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@sekolah.sch.id'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password123'),
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Kegiatan OSIS',
                'slug' => 'kegiatan-osis',
                'description' => 'Dokumentasi kegiatan Organisasi Siswa Intra Sekolah',
                'color' => '#3B82F6',
                'icon' => 'fas fa-users',
                'sort_order' => 1,
            ],
            [
                'name' => 'Lomba & Kompetisi',
                'slug' => 'lomba-kompetisi',
                'description' => 'Prestasi dan partisipasi dalam berbagai lomba',
                'color' => '#F59E0B',
                'icon' => 'fas fa-trophy',
                'sort_order' => 2,
            ],
            [
                'name' => 'Upacara & Peringatan',
                'slug' => 'upacara-peringatan',
                'description' => 'Upacara bendera dan peringatan hari besar',
                'color' => '#EF4444',
                'icon' => 'fas fa-flag',
                'sort_order' => 3,
            ],
            [
                'name' => 'Kegiatan Belajar',
                'slug' => 'kegiatan-belajar',
                'description' => 'Aktivitas pembelajaran dan praktikum',
                'color' => '#10B981',
                'icon' => 'fas fa-book',
                'sort_order' => 4,
            ],
            [
                'name' => 'Ekstrakurikuler',
                'slug' => 'ekstrakurikuler',
                'description' => 'Kegiatan ekstrakurikuler dan pengembangan bakat',
                'color' => '#8B5CF6',
                'icon' => 'fas fa-star',
                'sort_order' => 5,
            ],
            [
                'name' => 'Acara Sekolah',
                'slug' => 'acara-sekolah',
                'description' => 'Event dan acara besar sekolah',
                'color' => '#EC4899',
                'icon' => 'fas fa-calendar-alt',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate([
                'slug' => $categoryData['slug']
            ], $categoryData);
        }

        // Create tags
        $tags = [
            ['name' => 'Kegiatan OSIS', 'slug' => 'kegiatan-osis', 'color' => '#3B82F6'],
            ['name' => 'Lomba', 'slug' => 'lomba', 'color' => '#F59E0B'],
            ['name' => 'Upacara', 'slug' => 'upacara', 'color' => '#EF4444'],
            ['name' => 'Prestasi', 'slug' => 'prestasi', 'color' => '#10B981'],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'color' => '#8B5CF6'],
            ['name' => 'Seni', 'slug' => 'seni', 'color' => '#EC4899'],
            ['name' => 'Sains', 'slug' => 'sains', 'color' => '#06B6D4'],
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'color' => '#84CC16'],
            ['name' => 'Wisuda', 'slug' => 'wisuda', 'color' => '#F97316'],
            ['name' => 'Kelas', 'slug' => 'kelas', 'color' => '#6366F1'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate([
                'slug' => $tagData['slug']
            ], $tagData);
        }

        // Create sample pages
        $pages = [
            [
                'title' => 'Tentang Galeri',
                'slug' => 'tentang-galeri',
                'content' => '<h2>Selamat Datang di Galeri Sekolah</h2><p>Galeri ini merupakan dokumentasi digital dari berbagai kegiatan dan prestasi sekolah. Kami berkomitmen untuk mengabadikan setiap momen berharga dalam perjalanan pendidikan.</p>',
                'excerpt' => 'Dokumentasi digital kegiatan dan prestasi sekolah',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Cara Menggunakan',
                'slug' => 'cara-menggunakan',
                'content' => '<h2>Panduan Penggunaan Galeri</h2><p>Berikut adalah panduan untuk menggunakan galeri sekolah:</p><ul><li>Jelajahi kategori untuk menemukan foto berdasarkan jenis kegiatan</li><li>Gunakan fitur pencarian untuk menemukan foto spesifik</li><li>Klik foto untuk melihat detail dan informasi lengkap</li></ul>',
                'excerpt' => 'Panduan lengkap menggunakan galeri sekolah',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 2,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate([
                'slug' => $pageData['slug']
            ], $pageData);
        }

        // Create sample testimonials
        $testimonials = [
            [
                'name' => 'Sarah Putri',
                'role' => 'Siswa Kelas XII',
                'message' => 'Galeri sekolah ini sangat membantu untuk melihat kembali momen-momen indah selama bersekolah. Desainnya juga modern dan mudah digunakan!',
                'ip_address' => '127.0.0.1',
                'is_approved' => true,
                'is_featured' => true,
                'spam_score' => 0,
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ],
            [
                'name' => 'Budi Santoso',
                'role' => 'Guru',
                'message' => 'Sebagai guru, saya sangat senang melihat dokumentasi kegiatan siswa tersimpan dengan rapi di galeri digital ini. Sangat memudahkan untuk berbagi dengan orang tua.',
                'ip_address' => '127.0.0.1',
                'is_approved' => true,
                'is_featured' => true,
                'spam_score' => 0,
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ],
            [
                'name' => 'Ibu Siti',
                'role' => 'Orang Tua',
                'message' => 'Terima kasih untuk galeri yang luar biasa ini. Saya bisa melihat kegiatan anak saya di sekolah dengan mudah. Interface-nya juga user-friendly untuk orang tua seperti saya.',
                'ip_address' => '127.0.0.1',
                'is_approved' => true,
                'is_featured' => false,
                'spam_score' => 0,
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ],
        ];

        foreach ($testimonials as $testimonialData) {
            Testimonial::create($testimonialData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin credentials:');
        $this->command->info('Email: admin@sekolah.sch.id');
        $this->command->info('Password: password123');
    }
}
