<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'cover_photo',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'collection_photos', 'collection_id', 'photo_id')
                    ->withPivot('order')
                    ->withTimestamps()
                    ->orderBy('collection_photos.order');
    }

    public function getCoverPhotoUrlAttribute(): string
    {
        if ($this->cover_photo) {
            return asset('storage/' . $this->cover_photo);
        }

        $firstPhoto = $this->photos()->first();
        return $firstPhoto ? $firstPhoto->thumbnail_url : asset('images/default-collection.jpg');
    }

    public function getPhotosCountAttribute(): int
    {
        return $this->photos()->count();
    }
}
