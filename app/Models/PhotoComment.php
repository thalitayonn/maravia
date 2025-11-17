<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id',
        'user_id',
        'name',
        'email',
        'comment',
        'status',
        'is_spam',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'is_spam' => 'boolean'
    ];

    // Relationships
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class, 'comment_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : $this->name;
    }

    public function getAuthorAvatarAttribute()
    {
        return $this->user ? $this->user->avatar_url : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=ffffff';
    }

    // Methods
    public function getReactionCounts(): array
    {
        return CommentReaction::getReactionCounts($this->id);
    }

    public function getUserReaction($userId): ?string
    {
        return CommentReaction::getUserReaction($this->id, $userId);
    }

    public function getTotalReactionsCount(): int
    {
        return $this->reactions()->count();
    }
}
