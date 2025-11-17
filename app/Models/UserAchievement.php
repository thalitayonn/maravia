<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_type',
        'achievement_name',
        'description',
        'badge_icon',
        'badge_color',
        'points',
        'earned_at',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
        'points' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getAvailableAchievements(): array
    {
        return [
            'first_view' => [
                'name' => 'First Look',
                'description' => 'Viewed your first photo',
                'icon' => 'fas fa-eye',
                'color' => 'blue',
                'points' => 10,
            ],
            'first_favorite' => [
                'name' => 'Collector',
                'description' => 'Added your first favorite photo',
                'icon' => 'fas fa-heart',
                'color' => 'red',
                'points' => 20,
            ],
            'first_collection' => [
                'name' => 'Curator',
                'description' => 'Created your first collection',
                'icon' => 'fas fa-folder-plus',
                'color' => 'green',
                'points' => 30,
            ],
            'first_rating' => [
                'name' => 'Critic',
                'description' => 'Rated your first photo',
                'icon' => 'fas fa-star',
                'color' => 'yellow',
                'points' => 25,
            ],
            'explorer_10' => [
                'name' => 'Explorer',
                'description' => 'Viewed 10 different photos',
                'icon' => 'fas fa-compass',
                'color' => 'purple',
                'points' => 50,
            ],
            'collector_25' => [
                'name' => 'Photo Enthusiast',
                'description' => 'Favorited 25 photos',
                'icon' => 'fas fa-images',
                'color' => 'pink',
                'points' => 100,
            ],
            'active_7_days' => [
                'name' => 'Regular Visitor',
                'description' => 'Active for 7 consecutive days',
                'icon' => 'fas fa-calendar-check',
                'color' => 'indigo',
                'points' => 75,
            ],
            'social_butterfly' => [
                'name' => 'Social Butterfly',
                'description' => 'Left 10 photo reviews',
                'icon' => 'fas fa-comments',
                'color' => 'teal',
                'points' => 80,
            ],
        ];
    }

    public static function checkAndAward(User $user, string $achievementType): bool
    {
        $achievements = self::getAvailableAchievements();
        
        if (!isset($achievements[$achievementType])) {
            return false;
        }

        // Check if user already has this achievement
        if ($user->achievements()->where('achievement_type', $achievementType)->exists()) {
            return false;
        }

        $achievement = $achievements[$achievementType];
        
        // Award the achievement
        $user->achievements()->create([
            'achievement_type' => $achievementType,
            'achievement_name' => $achievement['name'],
            'description' => $achievement['description'],
            'badge_icon' => $achievement['icon'],
            'badge_color' => $achievement['color'],
            'points' => $achievement['points'],
            'earned_at' => now(),
        ]);

        // Update user stats
        if ($user->stats) {
            $user->stats->increment('achievement_points', $achievement['points']);
        }

        return true;
    }
}
