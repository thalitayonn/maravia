<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'role',
        'message',
        'ip_address',
        'user_agent',
        'is_approved',
        'is_featured',
        'spam_score',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'spam_score' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function calculateSpamScore()
    {
        $score = 0;
        
        // Check for suspicious patterns
        if (preg_match('/https?:\/\//', $this->message)) {
            $score += 30; // Contains URLs
        }
        
        if (strlen($this->message) < 10) {
            $score += 20; // Too short
        }
        
        if (preg_match('/(.)\1{4,}/', $this->message)) {
            $score += 25; // Repeated characters
        }
        
        // Check for spam keywords
        $spamKeywords = ['viagra', 'casino', 'loan', 'money', 'free', 'click here'];
        foreach ($spamKeywords as $keyword) {
            if (stripos($this->message, $keyword) !== false) {
                $score += 40;
            }
        }
        
        $this->spam_score = $score;
        $this->save();
        
        return $score;
    }

    public function isLikelySpam()
    {
        return $this->spam_score > 50;
    }
}
