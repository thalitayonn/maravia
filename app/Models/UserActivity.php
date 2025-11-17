<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'activityable_type',
        'activityable_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActivityIconAttribute(): string
    {
        $icons = [
            'view' => 'fas fa-eye',
            'favorite' => 'fas fa-heart',
            'unfavorite' => 'fas fa-heart-broken',
            'download' => 'fas fa-download',
            'rate' => 'fas fa-star',
            'collection_create' => 'fas fa-folder-plus',
            'collection_add' => 'fas fa-plus-circle',
            'search' => 'fas fa-search',
            'share' => 'fas fa-share-alt',
        ];

        return $icons[$this->activity_type] ?? 'fas fa-circle';
    }

    public function getActivityColorAttribute(): string
    {
        $colors = [
            'view' => 'blue',
            'favorite' => 'red',
            'unfavorite' => 'gray',
            'download' => 'green',
            'rate' => 'yellow',
            'collection_create' => 'purple',
            'collection_add' => 'indigo',
            'search' => 'teal',
            'share' => 'pink',
        ];

        return $colors[$this->activity_type] ?? 'gray';
    }

    public function getActivityDescriptionAttribute(): string
    {
        $descriptions = [
            'view' => 'viewed a photo',
            'favorite' => 'favorited a photo',
            'unfavorite' => 'removed favorite from a photo',
            'download' => 'downloaded a photo',
            'rate' => 'rated a photo',
            'collection_create' => 'created a new collection',
            'collection_add' => 'added photo to collection',
            'search' => 'searched for photos',
            'share' => 'shared a photo',
        ];

        return $descriptions[$this->activity_type] ?? 'performed an action';
    }
}
