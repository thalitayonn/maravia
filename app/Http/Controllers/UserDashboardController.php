<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Category;
use App\Models\User;
use App\Models\UserCollection;
use App\Models\UserAchievement;
use App\Models\PhotoRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $user->load(['stats', 'achievements', 'favorites', 'collections']);
        
        // Initialize user data if not exists
        $user->initializeUserData();
        
        // Get recent activities
        $recentActivities = $user->activities()
            ->with('activityable')
            ->latest()
            ->take(10)
            ->get();
        
        // Get recommended photos based on user preferences
        $recommendedPhotos = $this->getRecommendedPhotos($user);
        
        // Get user's recent favorites - dengan fallback ke semua foto jika tidak ada favorites
        $recentFavorites = $user->favorites()
            ->with(['category', 'tags'])
            ->latest('user_favorites.created_at')
            ->take(6)
            ->get();
        
        // Jika user belum punya favorites, ambil foto terbaru sebagai contoh
        if ($recentFavorites->isEmpty()) {
            $recentFavorites = \App\Models\Photo::active()
                ->with(['category', 'tags'])
                ->latest()
                ->take(6)
                ->get();
        }
        
        // Get user's collections
        $collections = $user->collections()
            ->withCount('photos')
            ->latest()
            ->take(4)
            ->get();
        
        // Get achievement progress
        $achievementProgress = $this->getAchievementProgress($user);
        
        // Get leaderboard position
        $leaderboardPosition = $this->getLeaderboardPosition($user);
        
        return view('user.dashboard', compact(
            'user',
            'recentActivities',
            'recommendedPhotos',
            'recentFavorites',
            'collections',
            'achievementProgress',
            'leaderboardPosition'
        ));
    }

    public function toggleFavorite(Photo $photo): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->hasFavorited($photo)) {
            $user->favorites()->detach($photo->id);
            $user->recordActivity('unfavorite', $photo);
            $favorited = false;
        } else {
            $user->favorites()->attach($photo->id);
            $user->recordActivity('favorite', $photo);
            $favorited = true;
            
            // Check for achievements
            $this->checkFavoriteAchievements($user);
        }
        
        // Update user stats
        if ($user->stats) {
            $user->stats->updateStats();
        }
        
        return response()->json([
            'favorited' => $favorited,
            'message' => $favorited ? 'Photo added to favorites!' : 'Photo removed from favorites!'
        ]);
    }

    public function ratePhoto(Request $request, Photo $photo): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);
        
        $user = Auth::user();
        
        $rating = $user->ratings()->updateOrCreate(
            ['photo_id' => $photo->id],
            [
                'rating' => $request->rating,
                'review' => $request->review
            ]
        );
        
        $user->recordActivity('rate', $photo, ['rating' => $request->rating]);
        
        // Check for achievements
        $this->checkRatingAchievements($user);
        
        // Update user stats
        if ($user->stats) {
            $user->stats->updateStats();
        }
        
        return response()->json([
            'message' => 'Photo rated successfully!',
            'rating' => $rating
        ]);
    }

    public function createCollection(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean'
        ]);
        
        $user = Auth::user();
        
        $collection = $user->collections()->create([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false)
        ]);
        
        $user->recordActivity('collection_create', $collection);
        
        // Check for achievements
        UserAchievement::checkAndAward($user, 'first_collection');
        
        // Update user stats
        if ($user->stats) {
            $user->stats->updateStats();
        }
        
        return response()->json([
            'message' => 'Collection created successfully!',
            'collection' => $collection
        ]);
    }

    public function addToCollection(Request $request, UserCollection $collection, Photo $photo): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user owns the collection
        if ($collection->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Check if photo is already in collection
        if ($collection->photos()->where('photo_id', $photo->id)->exists()) {
            return response()->json(['message' => 'Photo already in collection'], 400);
        }
        
        $collection->photos()->attach($photo->id, ['order' => $collection->photos()->count()]);
        
        $user->recordActivity('collection_add', $photo, ['collection_id' => $collection->id]);
        
        return response()->json([
            'message' => 'Photo added to collection successfully!'
        ]);
    }

    public function getCollections(): JsonResponse
    {
        $user = Auth::user();
        $collections = $user->collections()->withCount('photos')->get();
        
        return response()->json($collections);
    }

    public function getStats(): JsonResponse
    {
        $user = Auth::user();
        $user->load(['stats', 'achievements']);
        
        return response()->json([
            'stats' => $user->stats,
            'level' => $user->user_level,
            'progress' => $user->progress_to_next_level,
            'achievements_count' => $user->achievements->count()
        ]);
    }

    private function getRecommendedPhotos(User $user)
    {
        // Get user's favorite categories and tags from their activity
        $favoriteCategories = $user->favorites()
            ->with('category')
            ->get()
            ->pluck('category.id')
            ->unique()
            ->filter();
        
        $query = Photo::active()->with(['category', 'tags']);
        
        if ($favoriteCategories->isNotEmpty()) {
            $query->whereIn('category_id', $favoriteCategories);
        }
        
        return $query->latest()
            ->take(8)
            ->get();
    }

    private function getAchievementProgress(User $user): array
    {
        $availableAchievements = UserAchievement::getAvailableAchievements();
        $userAchievements = $user->achievements->pluck('achievement_type')->toArray();
        
        $progress = [];
        foreach ($availableAchievements as $type => $achievement) {
            $progress[] = [
                'type' => $type,
                'name' => $achievement['name'],
                'description' => $achievement['description'],
                'icon' => $achievement['icon'],
                'color' => $achievement['color'],
                'points' => $achievement['points'],
                'earned' => in_array($type, $userAchievements),
                'progress' => $this->getAchievementProgressPercentage($user, $type)
            ];
        }
        
        return $progress;
    }

    private function getAchievementProgressPercentage(User $user, string $type): int
    {
        switch ($type) {
            case 'explorer_10':
                return min(($user->stats->total_views ?? 0) / 10 * 100, 100);
            case 'collector_25':
                return min(($user->stats->total_favorites ?? 0) / 25 * 100, 100);
            case 'social_butterfly':
                return min(($user->stats->total_ratings ?? 0) / 10 * 100, 100);
            case 'active_7_days':
                return min(($user->stats->days_active ?? 0) / 7 * 100, 100);
            default:
                return 0;
        }
    }

    private function getLeaderboardPosition(User $user): array
    {
        $userStats = $user->stats;
        if (!$userStats) {
            return ['position' => null, 'total_users' => 0];
        }
        
        $position = User::whereHas('stats', function ($query) use ($userStats) {
            $query->where('achievement_points', '>', $userStats->achievement_points);
        })->count() + 1;
        
        $totalUsers = User::whereHas('stats')->count();
        
        return [
            'position' => $position,
            'total_users' => $totalUsers,
            'percentile' => $totalUsers > 0 ? round((($totalUsers - $position + 1) / $totalUsers) * 100) : 0
        ];
    }

    private function checkFavoriteAchievements(User $user): void
    {
        $favoritesCount = $user->favorites()->count();
        
        if ($favoritesCount === 1) {
            UserAchievement::checkAndAward($user, 'first_favorite');
        } elseif ($favoritesCount === 25) {
            UserAchievement::checkAndAward($user, 'collector_25');
        }
    }

    private function checkRatingAchievements(User $user): void
    {
        $ratingsCount = $user->ratings()->count();
        
        if ($ratingsCount === 1) {
            UserAchievement::checkAndAward($user, 'first_rating');
        } elseif ($ratingsCount === 10) {
            UserAchievement::checkAndAward($user, 'social_butterfly');
        }
    }
}
