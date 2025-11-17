<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_views',
        'total_downloads',
        'total_favorites',
        'total_collections',
        'total_ratings',
        'average_rating_given',
        'achievement_points',
        'user_level',
        'days_active',
        'last_activity',
    ];

    protected $casts = [
        'total_views' => 'integer',
        'total_downloads' => 'integer',
        'total_favorites' => 'integer',
        'total_collections' => 'integer',
        'total_ratings' => 'integer',
        'average_rating_given' => 'decimal:2',
        'achievement_points' => 'integer',
        'user_level' => 'integer',
        'days_active' => 'integer',
        'last_activity' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateStats(): void
    {
        $user = $this->user;
        
        $this->update([
            'total_favorites' => $user->favorites()->count(),
            'total_collections' => $user->collections()->count(),
            'total_ratings' => $user->ratings()->count(),
            'average_rating_given' => $user->ratings()->avg('rating') ?? 0,
            'last_activity' => now(),
        ]);
    }

    public function getEngagementScoreAttribute(): int
    {
        return ($this->total_views * 1) + 
               ($this->total_favorites * 3) + 
               ($this->total_downloads * 2) + 
               ($this->total_ratings * 4) + 
               ($this->total_collections * 5);
    }
}
