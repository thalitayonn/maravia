<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PhotoVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id',
        'user_id',
        'vote_type',
        'category',
        'score',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    // Vote types
    const VOTE_LIKE = 'like';
    const VOTE_CONTEST = 'contest_vote';
    const VOTE_POLL = 'poll_vote';
    const VOTE_RATING = 'rating';

    // Relationships
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('vote_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeThisWeek($query)
    {
        return $query->where('created_at', '>=', now()->startOfWeek());
    }

    public function scopeThisMonth($query)
    {
        return $query->where('created_at', '>=', now()->startOfMonth());
    }

    // Static methods for gamification
    public static function getTopPhotos($voteType = self::VOTE_LIKE, $category = null, $limit = 10)
    {
        $query = self::select('photo_id', DB::raw('COUNT(*) as vote_count'))
            ->byType($voteType)
            ->groupBy('photo_id')
            ->orderBy('vote_count', 'desc')
            ->limit($limit);

        if ($category) {
            $query->byCategory($category);
        }

        return $query->with('photo')->get();
    }

    public static function getTopVoters($voteType = null, $period = 'all', $limit = 10)
    {
        $query = self::select('user_id', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('user_id')
            ->orderBy('vote_count', 'desc')
            ->limit($limit);

        if ($voteType) {
            $query->byType($voteType);
        }

        switch ($period) {
            case 'week':
                $query->thisWeek();
                break;
            case 'month':
                $query->thisMonth();
                break;
        }

        return $query->with('user')->get();
    }

    public static function getPhotoVoteCount($photoId, $voteType = self::VOTE_LIKE, $category = null)
    {
        $query = self::where('photo_id', $photoId)->byType($voteType);
        
        if ($category) {
            $query->byCategory($category);
        }

        return $query->count();
    }

    public static function getUserVote($photoId, $userId, $voteType = self::VOTE_LIKE, $category = null)
    {
        $query = self::where('photo_id', $photoId)
            ->where('user_id', $userId)
            ->byType($voteType);

        if ($category) {
            $query->byCategory($category);
        }

        return $query->first();
    }

    public static function toggleVote($photoId, $userId, $voteType = self::VOTE_LIKE, $category = null, $score = 1)
    {
        $existingVote = self::getUserVote($photoId, $userId, $voteType, $category);

        if ($existingVote) {
            $existingVote->delete();
            return ['action' => 'removed', 'vote' => null];
        } else {
            $vote = self::create([
                'photo_id' => $photoId,
                'user_id' => $userId,
                'vote_type' => $voteType,
                'category' => $category,
                'score' => $score
            ]);
            return ['action' => 'added', 'vote' => $vote];
        }
    }

    public static function getContestLeaderboard($category, $limit = 10)
    {
        return self::select('photo_id', DB::raw('COUNT(*) as vote_count'))
            ->byType(self::VOTE_CONTEST)
            ->byCategory($category)
            ->groupBy('photo_id')
            ->orderBy('vote_count', 'desc')
            ->limit($limit)
            ->with(['photo' => function($query) {
                $query->with(['category', 'user']);
            }])
            ->get();
    }

    public static function getPollResults($category)
    {
        return self::select('photo_id', DB::raw('COUNT(*) as vote_count'))
            ->byType(self::VOTE_POLL)
            ->byCategory($category)
            ->groupBy('photo_id')
            ->orderBy('vote_count', 'desc')
            ->with(['photo' => function($query) {
                $query->with(['category', 'user']);
            }])
            ->get();
    }

    public static function getUserVotingStats($userId)
    {
        return [
            'total_votes' => self::where('user_id', $userId)->count(),
            'likes_given' => self::where('user_id', $userId)->byType(self::VOTE_LIKE)->count(),
            'contest_votes' => self::where('user_id', $userId)->byType(self::VOTE_CONTEST)->count(),
            'poll_votes' => self::where('user_id', $userId)->byType(self::VOTE_POLL)->count(),
            'this_week' => self::where('user_id', $userId)->thisWeek()->count(),
            'this_month' => self::where('user_id', $userId)->thisMonth()->count(),
        ];
    }

    // Achievement helpers
    public static function checkVotingAchievements($userId)
    {
        $stats = self::getUserVotingStats($userId);
        $achievements = [];

        if ($stats['total_votes'] >= 10) {
            $achievements[] = 'active_voter';
        }
        if ($stats['total_votes'] >= 50) {
            $achievements[] = 'super_voter';
        }
        if ($stats['contest_votes'] >= 5) {
            $achievements[] = 'contest_participant';
        }
        if ($stats['this_week'] >= 20) {
            $achievements[] = 'weekly_voter';
        }

        return $achievements;
    }
}
