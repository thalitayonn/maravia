<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoVote;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaderboardController extends Controller
{
    public function index()
    {
        $topPhotos = PhotoVote::getTopPhotos(PhotoVote::VOTE_LIKE, null, 20);
        $topVoters = PhotoVote::getTopVoters(null, 'month', 15);
        $weeklyTopPhotos = PhotoVote::getTopPhotos(PhotoVote::VOTE_LIKE, null, 10);
        
        // Get active contests and polls
        $activeContests = $this->getActiveContests();
        $activePolls = $this->getActivePolls();
        
        // Get user stats if authenticated
        $userStats = null;
        $userRank = null;
        if (Auth::check()) {
            $userStats = PhotoVote::getUserVotingStats(Auth::id());
            $userRank = $this->getUserRank(Auth::id());
        }

        return view('leaderboard.index', compact(
            'topPhotos',
            'topVoters',
            'weeklyTopPhotos',
            'activeContests',
            'activePolls',
            'userStats',
            'userRank'
        ));
    }

    public function votePhoto(Request $request, Photo $photo): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk memberikan vote'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'vote_type' => 'required|string|in:like,contest_vote,poll_vote,rating',
            'category' => 'nullable|string|max:50',
            'score' => 'nullable|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $voteType = $request->vote_type;
        $category = $request->category;
        $score = $request->score ?? 1;

        $result = PhotoVote::toggleVote($photo->id, $user->id, $voteType, $category, $score);
        
        // Record activity
        $activityType = $result['action'] === 'added' ? 'vote' : 'unvote';
        $user->recordActivity($activityType, $photo, [
            'vote_type' => $voteType,
            'category' => $category
        ]);

        // Check for achievements
        if ($result['action'] === 'added') {
            $achievements = PhotoVote::checkVotingAchievements($user->id);
            foreach ($achievements as $achievement) {
                UserAchievement::checkAndAward($user, $achievement);
            }
        }

        // Update user stats
        if ($user->stats) {
            $user->stats->updateStats();
        }

        $voteCount = PhotoVote::getPhotoVoteCount($photo->id, $voteType, $category);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'message' => $result['action'] === 'added' ? 'Vote berhasil diberikan!' : 'Vote berhasil dibatalkan!',
            'vote_count' => $voteCount,
            'user_voted' => $result['action'] === 'added'
        ]);
    }

    public function getContestLeaderboard(Request $request): JsonResponse
    {
        $category = $request->get('category', 'weekly_contest');
        $limit = $request->get('limit', 10);

        $leaderboard = PhotoVote::getContestLeaderboard($category, $limit);

        return response()->json([
            'success' => true,
            'leaderboard' => $leaderboard->map(function ($item) {
                return [
                    'photo' => [
                        'id' => $item->photo->id,
                        'title' => $item->photo->title,
                        'thumbnail_url' => $item->photo->thumbnail_url,
                        'user_name' => $item->photo->user->name ?? 'Unknown'
                    ],
                    'vote_count' => $item->vote_count,
                    'rank' => $leaderboard->search($item) + 1
                ];
            }),
            'category' => $category
        ]);
    }

    public function getPollResults(Request $request): JsonResponse
    {
        $category = $request->get('category');
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category is required'
            ], 400);
        }

        $results = PhotoVote::getPollResults($category);
        $totalVotes = $results->sum('vote_count');

        return response()->json([
            'success' => true,
            'results' => $results->map(function ($item) use ($totalVotes) {
                $percentage = $totalVotes > 0 ? round(($item->vote_count / $totalVotes) * 100, 1) : 0;
                
                return [
                    'photo' => [
                        'id' => $item->photo->id,
                        'title' => $item->photo->title,
                        'thumbnail_url' => $item->photo->thumbnail_url,
                        'user_name' => $item->photo->user->name ?? 'Unknown'
                    ],
                    'vote_count' => $item->vote_count,
                    'percentage' => $percentage
                ];
            }),
            'total_votes' => $totalVotes,
            'category' => $category
        ]);
    }

    public function getUserStats(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $userId = $request->get('user_id', Auth::id());
        $stats = PhotoVote::getUserVotingStats($userId);
        $rank = $this->getUserRank($userId);

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'rank' => $rank
        ]);
    }

    public function getTopVoters(Request $request): JsonResponse
    {
        $voteType = $request->get('vote_type');
        $period = $request->get('period', 'month');
        $limit = $request->get('limit', 10);

        $topVoters = PhotoVote::getTopVoters($voteType, $period, $limit);

        return response()->json([
            'success' => true,
            'top_voters' => $topVoters->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => [
                        'id' => $item->user->id,
                        'name' => $item->user->name,
                        'avatar_url' => $item->user->avatar_url,
                        'level' => $item->user->user_level
                    ],
                    'vote_count' => $item->vote_count
                ];
            }),
            'period' => $period
        ]);
    }

    private function getActiveContests(): array
    {
        // This would typically come from a contests table
        // For now, return some sample contests
        return [
            [
                'id' => 'weekly_contest',
                'title' => 'Foto Terbaik Minggu Ini',
                'description' => 'Vote foto favorit Anda minggu ini!',
                'end_date' => now()->endOfWeek(),
                'category' => 'weekly_contest'
            ],
            [
                'id' => 'nature_contest',
                'title' => 'Kontes Foto Alam',
                'description' => 'Kompetisi foto alam terbaik',
                'end_date' => now()->addDays(7),
                'category' => 'nature_contest'
            ]
        ];
    }

    private function getActivePolls(): array
    {
        // This would typically come from a polls table
        // For now, return some sample polls
        return [
            [
                'id' => 'best_category',
                'title' => 'Kategori Foto Favorit',
                'description' => 'Pilih kategori foto yang paling Anda sukai',
                'category' => 'best_category'
            ],
            [
                'id' => 'photo_style',
                'title' => 'Gaya Fotografi Terbaik',
                'description' => 'Vote gaya fotografi yang paling menarik',
                'category' => 'photo_style'
            ]
        ];
    }

    private function getUserRank($userId): array
    {
        $userVoteCount = PhotoVote::where('user_id', $userId)->count();
        
        $rank = PhotoVote::selectRaw('user_id, COUNT(*) as vote_count')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > ?', [$userVoteCount])
            ->count() + 1;

        $totalUsers = PhotoVote::distinct('user_id')->count();

        return [
            'rank' => $rank,
            'total_users' => $totalUsers,
            'percentile' => $totalUsers > 0 ? round((($totalUsers - $rank + 1) / $totalUsers) * 100) : 0
        ];
    }
}
