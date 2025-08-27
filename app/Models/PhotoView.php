<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoView extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public $timestamps = false;

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class);
    }

    public static function recordView($photoId, $ipAddress, $userAgent = null)
    {
        // Prevent duplicate views from same IP within 1 hour
        $existingView = static::where('photo_id', $photoId)
            ->where('ip_address', $ipAddress)
            ->where('viewed_at', '>', now()->subHour())
            ->first();

        if (!$existingView) {
            static::create([
                'photo_id' => $photoId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'viewed_at' => now(),
            ]);

            // Update photo view count
            Photo::find($photoId)?->incrementViewCount();
        }
    }
}
