<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Photo;
use App\Models\UserCollection;
use App\Models\UserAchievement;
use App\Models\PhotoRating;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Hash;

class UserEngagementSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'bio' => 'Pecinta fotografi dan seni visual. Suka mengkoleksi foto-foto menarik dari berbagai acara sekolah.',
                'location' => 'Jakarta',
                'gender' => 'male',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'bio' => 'Mahasiswa yang gemar mengabadikan momen-momen berharga di sekolah.',
                'location' => 'Bandung',
                'gender' => 'female',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'bio' => 'Fotografer amatir yang senang berbagi koleksi foto dengan teman-teman.',
                'location' => 'Surabaya',
                'gender' => 'male',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya@example.com',
                'password' => Hash::make('password123'),
                'bio' => 'Seniman muda yang tertarik dengan dokumentasi kegiatan sekolah.',
                'location' => 'Yogyakarta',
                'gender' => 'female',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Initialize user data
            $user->initializeUserData();
            
            // Create some sample activities and engagement
            $this->createUserEngagement($user);
        }
    }

    private function createUserEngagement(User $user): void
    {
        $photos = Photo::active()->inRandomOrder()->take(rand(5, 15))->get();
        
        foreach ($photos as $photo) {
            // Random chance to favorite photos
            if (rand(1, 100) <= 30) { // 30% chance
                $user->favorites()->attach($photo->id);
                $user->recordActivity('favorite', $photo);
            }
            
            // Random chance to rate photos
            if (rand(1, 100) <= 25) { // 25% chance
                $rating = rand(3, 5); // Mostly positive ratings
                $reviews = [
                    'Foto yang sangat bagus!',
                    'Momen yang indah sekali.',
                    'Kualitas fotonya luar biasa.',
                    'Sangat berkesan!',
                    'Dokumentasi yang sempurna.',
                    'Angle yang bagus sekali.',
                    'Lighting-nya perfect!',
                    'Moment yang tepat!',
                ];
                
                PhotoRating::create([
                    'user_id' => $user->id,
                    'photo_id' => $photo->id,
                    'rating' => $rating,
                    'review' => rand(1, 100) <= 70 ? $reviews[array_rand($reviews)] : null,
                ]);
                
                $user->recordActivity('rate', $photo, ['rating' => $rating]);
            }
            
            // Record view activity
            if (rand(1, 100) <= 80) { // 80% chance
                $user->recordActivity('view', $photo);
            }
        }
        
        // Create some collections
        $collectionNames = [
            'Foto Favorit Saya',
            'Momen Terbaik',
            'Kegiatan Ekstrakurikuler',
            'Acara Sekolah',
            'Prestasi Siswa',
            'Dokumentasi Kelas',
            'Event Spesial',
            'Koleksi Pribadi',
        ];
        
        $numCollections = rand(1, 3);
        for ($i = 0; $i < $numCollections; $i++) {
            $collection = UserCollection::create([
                'user_id' => $user->id,
                'name' => $collectionNames[array_rand($collectionNames)] . ' ' . ($i + 1),
                'description' => 'Koleksi foto pilihan yang saya kumpulkan dengan cermat.',
                'is_public' => rand(1, 100) <= 60, // 60% chance to be public
            ]);
            
            // Add some photos to collection
            $collectionPhotos = $user->favorites()->inRandomOrder()->take(rand(3, 8))->get();
            foreach ($collectionPhotos as $index => $photo) {
                $collection->photos()->attach($photo->id, ['order' => $index]);
                $user->recordActivity('collection_add', $photo, ['collection_id' => $collection->id]);
            }
            
            $user->recordActivity('collection_create', $collection);
        }
        
        // Award some achievements based on activity
        $this->awardAchievements($user);
        
        // Update user stats
        if ($user->stats) {
            $user->stats->updateStats();
            
            // Add some additional stats
            $user->stats->update([
                'total_views' => rand(10, 100),
                'total_downloads' => rand(5, 30),
                'days_active' => rand(1, 30),
            ]);
        }
    }
    
    private function awardAchievements(User $user): void
    {
        // Award first achievements
        UserAchievement::checkAndAward($user, 'first_view');
        
        if ($user->favorites()->count() > 0) {
            UserAchievement::checkAndAward($user, 'first_favorite');
        }
        
        if ($user->collections()->count() > 0) {
            UserAchievement::checkAndAward($user, 'first_collection');
        }
        
        if ($user->ratings()->count() > 0) {
            UserAchievement::checkAndAward($user, 'first_rating');
        }
        
        // Award milestone achievements
        if ($user->activities()->where('activity_type', 'view')->count() >= 10) {
            UserAchievement::checkAndAward($user, 'explorer_10');
        }
        
        if ($user->favorites()->count() >= 25) {
            UserAchievement::checkAndAward($user, 'collector_25');
        }
        
        if ($user->ratings()->count() >= 10) {
            UserAchievement::checkAndAward($user, 'social_butterfly');
        }
        
        // Random chance for active user achievement
        if (rand(1, 100) <= 30) {
            UserAchievement::checkAndAward($user, 'active_7_days');
        }
    }
}
