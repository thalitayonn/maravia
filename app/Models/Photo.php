<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'filename',
        'original_filename',
        'path',
        'thumbnail_path',
        'mime_type',
        'file_size',
        'width',
        'height',
        'category_id',
        'uploaded_by',
        'view_count',
        'is_featured',
        'is_active',
        'taken_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'taken_at' => 'datetime',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'view_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'photo_tags');
    }

    public function views(): HasMany
    {
        return $this->hasMany(PhotoView::class);
    }

    // User engagement relationships
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites')->withTimestamps();
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(PhotoRating::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(UserCollection::class, 'collection_photos', 'photo_id', 'collection_id')
                    ->withPivot('order')
                    ->withTimestamps();
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(UserActivity::class, 'activityable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    public function getUrlAttribute()
    {
        // Use asset() for public storage
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return $this->url;
    }

    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    // Engagement helper methods
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getRatingCountAttribute(): int
    {
        return $this->ratings()->count();
    }

    public function getFavoritesCountAttribute(): int
    {
        return $this->favoritedBy()->count();
    }

    public function getEngagementScoreAttribute(): int
    {
        return ($this->view_count * 1) + 
               ($this->download_count * 2) + 
               ($this->favorites_count * 3) + 
               ($this->rating_count * 4);
    }
}
