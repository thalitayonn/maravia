<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'reaction_type'
    ];

    // Available reaction types with their emoji representations
    public static function getReactionTypes(): array
    {
        return [
            'like' => '👍',
            'love' => '❤️',
            'laugh' => '😂',
            'wow' => '😮',
            'sad' => '😢',
            'angry' => '😠'
        ];
    }

    // Relationships
    public function comment()
    {
        return $this->belongsTo(PhotoComment::class, 'comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getEmojiAttribute(): string
    {
        $reactions = self::getReactionTypes();
        return $reactions[$this->reaction_type] ?? '👍';
    }

    // Static methods
    public static function getReactionCounts($commentId): array
    {
        $reactions = self::where('comment_id', $commentId)
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();

        $reactionTypes = self::getReactionTypes();
        $result = [];

        foreach ($reactionTypes as $type => $emoji) {
            $result[$type] = [
                'emoji' => $emoji,
                'count' => $reactions[$type] ?? 0
            ];
        }

        return $result;
    }

    public static function getUserReaction($commentId, $userId): ?string
    {
        $reaction = self::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        return $reaction ? $reaction->reaction_type : null;
    }
}
