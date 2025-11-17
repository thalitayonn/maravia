<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorite_categories',
        'favorite_tags',
        'theme_preference',
        'email_notifications',
        'new_photo_alerts',
    ];

    protected $casts = [
        'favorite_categories' => 'array',
        'favorite_tags' => 'array',
        'email_notifications' => 'boolean',
        'new_photo_alerts' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
