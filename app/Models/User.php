<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'location',
        'birth_date',
        'gender',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'user_favorites')->withTimestamps();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(UserCollection::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(PhotoRating::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(UserStats::class);
    }

    // Helper methods
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        // Generate avatar using initials
        $initials = strtoupper(substr($this->name, 0, 1));
        if (strpos($this->name, ' ') !== false) {
            $nameParts = explode(' ', $this->name);
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        }
        
        return "https://ui-avatars.com/api/?name={$initials}&background=f59e0b&color=fff&size=200";
    }

    public function getUserLevelAttribute(): int
    {
        $stats = $this->stats;
        if (!$stats) return 1;
        
        $totalPoints = $stats->achievement_points;
        return min(floor($totalPoints / 100) + 1, 50); // Max level 50
    }

    public function getProgressToNextLevelAttribute(): int
    {
        $stats = $this->stats;
        if (!$stats) return 0;
        
        $currentLevel = $this->user_level;
        $pointsForCurrentLevel = ($currentLevel - 1) * 100;
        $pointsForNextLevel = $currentLevel * 100;
        
        return (($stats->achievement_points - $pointsForCurrentLevel) / ($pointsForNextLevel - $pointsForCurrentLevel)) * 100;
    }

    public function hasFavorited(Photo $photo): bool
    {
        return $this->favorites()->where('photo_id', $photo->id)->exists();
    }

    public function hasRated(Photo $photo): bool
    {
        return $this->ratings()->where('photo_id', $photo->id)->exists();
    }

    public function getRatingFor(Photo $photo): ?PhotoRating
    {
        return $this->ratings()->where('photo_id', $photo->id)->first();
    }

    public function recordActivity(string $type, $model = null, array $metadata = []): void
    {
        $this->activities()->create([
            'activity_type' => $type,
            'activityable_type' => $model ? get_class($model) : null,
            'activityable_id' => $model ? $model->id : null,
            'metadata' => $metadata,
        ]);
    }

    public function updateLastActivity(): void
    {
        $this->update(['last_login_at' => now()]);
        
        if ($this->stats) {
            $this->stats->update(['last_activity' => now()]);
        }
    }

    public function initializeUserData(): void
    {
        // Create user preferences if not exists
        if (!$this->preferences) {
            $this->preferences()->create([
                'theme_preference' => 'light',
                'email_notifications' => true,
                'new_photo_alerts' => false,
            ]);
        }

        // Create user stats if not exists
        if (!$this->stats) {
            $this->stats()->create([
                'total_views' => 0,
                'total_downloads' => 0,
                'total_favorites' => 0,
                'total_collections' => 0,
                'total_ratings' => 0,
                'achievement_points' => 0,
                'user_level' => 1,
                'days_active' => 1,
                'last_activity' => now(),
            ]);
        }
    }
}
