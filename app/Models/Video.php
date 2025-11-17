<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category_id',
        'file_path', 'poster_path', 'duration',
        'is_active', 'is_featured', 'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'duration' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeActive($q){ return $q->where('is_active', true); }
    public function scopeFeatured($q){ return $q->where('is_featured', true); }

    public function getUrlAttribute()
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    public function getPosterUrlAttribute()
    {
        return $this->poster_path ? asset('storage/'.$this->poster_path) : null;
    }
}
