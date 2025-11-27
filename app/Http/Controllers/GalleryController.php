<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Category;
use App\Models\Tag;
use App\Models\PhotoView;
use App\Models\Article;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $featuredPhotos = Photo::active()->featured()->with(['category', 'tags'])->latest()->take(6)->get();
        $categories = Category::active()->ordered()->withCount('activePhotos')->get();
        $recentPhotos = Photo::active()->with(['category', 'tags'])->latest()->take(12)->get();
        
        // Stats for homepage
        $stats = [
            'total_photos' => Photo::active()->count(),
            'categories' => Category::active()->count(),
            'total_views' => Photo::sum('view_count'),
        ];
        
        // Load recent articles only (video feature removed)
        $recentArticles = collect();
        
        try {
            if (class_exists('App\Models\Article')) {
                $recentArticles = \App\Models\Article::published()->latest()->limit(3)->get();
                if ($recentArticles->isEmpty()) {
                    $recentArticles = \App\Models\Article::where('is_published', true)->latest()->limit(3)->get();
                }

            }
        } catch (\Exception $e) {
            $recentArticles = collect();
        }

        return view('gallery.index', compact('featuredPhotos', 'categories', 'recentPhotos', 'stats', 'recentArticles'));
    }

    public function gallery(Request $request)
    {
        $query = Photo::active()->with(['category', 'tags']);

        // If logged in, include per-photo favorited flag
        if (auth()->check()) {
            $userId = auth()->id();
            $query->withCount(['favoritedBy as is_favorited' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }
        
        // Filter by tag
        if ($request->has('tag') && $request->tag) {
            $query->byTag($request->tag);
        }
        
        // Search (title only)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('tags', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->latest();
                break;
            default:
                $query->latest();
        }
        
        $photos = $query->paginate(12);
        $categories = Category::active()->ordered()->get();
        $tags = Tag::active()->orderBy('name')->get();
        
        return view('gallery.gallery', compact('photos', 'categories', 'tags'));
    }

    public function category(Category $category)
    {
        $photos = $category->activePhotos()->with('tags')->latest()->paginate(12);
        
        return view('gallery.category', compact('category', 'photos'));
    }

    public function tag(Tag $tag)
    {
        $photos = $tag->photos()->where('photos.is_active', true)
                     ->with(['category', 'tags'])
                     ->latest('photos.created_at')
                     ->paginate(12);
        
        return view('gallery.tag', compact('tag', 'photos'));
    }

    public function show(Photo $photo)
    {
        if (!$photo->is_active) {
            abort(404);
        }
        
        // Record view
        PhotoView::recordView(
            $photo->id,
            request()->ip(),
            request()->userAgent()
        );
        
        $photo->load(['category', 'tags', 'uploader']);
        
        // Related by shared tags first
        $tagIds = $photo->tags ? $photo->tags->pluck('id')->all() : [];
        $relatedPhotos = collect();
        if (!empty($tagIds)) {
            $relatedPhotos = Photo::active()
                ->where('id', '!=', $photo->id)
                ->whereHas('tags', function($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                })
                ->with(['category', 'tags'])
                ->latest()
                ->distinct()
                ->take(6)
                ->get();
        }

        // Fallback to same category if no tag matches
        if ($relatedPhotos->isEmpty()) {
            $relatedPhotos = Photo::active()
                ->when($photo->category_id, function($q) use ($photo) {
                    $q->where('category_id', $photo->category_id);
                })
                ->where('id', '!=', $photo->id)
                ->with(['category', 'tags'])
                ->latest()
                ->take(6)
                ->get();
        }

        // Fallback to latest if still empty
        if ($relatedPhotos->isEmpty()) {
            $relatedPhotos = Photo::active()
                ->where('id', '!=', $photo->id)
                ->with(['category', 'tags'])
                ->latest()
                ->take(6)
                ->get();
        }
        
        return view('gallery.show', compact('photo', 'relatedPhotos'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $photos = collect();
        
        if ($query) {
            $photos = Photo::active()
                ->with(['category', 'tags'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhereHas('category', function ($cq) use ($query) {
                          $cq->where('name', 'like', "%{$query}%");
                      })
                      ->orWhereHas('tags', function ($tq) use ($query) {
                          $tq->where('name', 'like', "%{$query}%");
                      });
                })
                ->latest()
                ->paginate(12);
        }
        
        return view('gallery.search', compact('photos', 'query'));
    }

    public function trackView(Photo $photo)
    {
        if (!$photo->is_active) {
            return response()->json(['error' => 'Photo not found'], 404);
        }

        PhotoView::recordView(
            $photo->id,
            request()->ip(),
            request()->userAgent()
        );

        return response()->json(['success' => true, 'views' => $photo->fresh()->view_count]);
    }

    // API Methods
    public function apiIndex(Request $request)
    {
        $query = Photo::active()->with(['category', 'tags']);
        
        // Apply same filters as gallery method
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }
        
        if ($request->has('tag') && $request->tag) {
            $query->byTag($request->tag);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('tags', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->latest();
                break;
            default:
                $query->latest();
        }
        
        $photos = $query->paginate(12);
        
        // Transform photos to include API URLs
        $photos->getCollection()->transform(function ($photo) {
            return [
                'id' => $photo->id,
                'title' => $photo->title,
                'description' => $photo->description,
                'category' => $photo->category->name,
                'view_count' => $photo->view_count,
                'is_featured' => $photo->is_featured,
                'created_at' => $photo->created_at->format('d M Y'),
                'image_url' => route('api.photos.image', $photo),
                'thumbnail_url' => route('api.photos.thumbnail', $photo),
                'detail_url' => route('gallery.photo', $photo)
            ];
        });
        
        return response()->json($photos);
    }

    public function apiShow(Photo $photo)
    {
        if (!$photo->is_active) {
            return response()->json(['error' => 'Photo not found'], 404);
        }

        $photo->load(['category', 'tags', 'uploader']);
        
        return response()->json([
            'id' => $photo->id,
            'title' => $photo->title,
            'description' => $photo->description,
            'category' => $photo->category->name,
            'tags' => $photo->tags->pluck('name'),
            'view_count' => $photo->view_count,
            'is_featured' => $photo->is_featured,
            'dimensions' => $photo->width . ' × ' . $photo->height,
            'file_size' => $photo->file_size_human,
            'created_at' => $photo->created_at->format('d M Y'),
            'image_url' => route('api.photos.image', $photo),
            'thumbnail_url' => route('api.photos.thumbnail', $photo)
        ]);
    }

    public function serveImage(Photo $photo)
    {
        if (!$photo->is_active) {
            abort(404, 'Photo not found');
        }

        $filePath = storage_path('app/public/' . $photo->path);
        
        if (!file_exists($filePath)) {
            \Log::warning('Photo file not found', [
                'photo_id' => $photo->id,
                'path' => $photo->path,
                'full_path' => $filePath
            ]);

            // Fallback to static placeholder if available
            $placeholder = public_path('images/placeholder.jpg');
            if (file_exists($placeholder)) {
                return response()->file($placeholder, [
                    'Content-Type' => 'image/jpeg',
                    'Content-Disposition' => 'inline',
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                    'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($placeholder)) . ' GMT'
                ]);
            }

            // Last resort: generate simple SVG placeholder so it's visible
            $safeTitle = htmlspecialchars($photo->title ?? config('app.name', 'Maravia'), ENT_QUOTES, 'UTF-8');
            $svg = '<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">'
                 . '<defs>'
                 . '<linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
                 . '<stop offset="0%" stop-color="#fdf2f8"/>'
                 . '<stop offset="100%" stop-color="#e0f2fe"/>'
                 . '</linearGradient>'
                 . '</defs>'
                 . '<rect width="100%" height="100%" fill="url(#g)"/>'
                 . '<text x="50%" y="50%" text-anchor="middle" font-family="Arial, sans-serif" font-size="28" fill="#334155">'
                 . $safeTitle
                 . '</text>'
                 . '<text x="50%" y="560" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" fill="#64748b">Image unavailable</text>'
                 . '</svg>';
            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        }

        $mimeType = mime_content_type($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            // Disable aggressive caching so updated photos show immediately
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT'
        ]);
    }

    public function serveThumbnail(Photo $photo)
    {
        if (!$photo->is_active) {
            abort(404, 'Photo not found');
        }

        // Try thumbnail first
        if ($photo->thumbnail_path) {
            $thumbnailPath = storage_path('app/public/' . $photo->thumbnail_path);
            
            if (file_exists($thumbnailPath)) {
                $mimeType = mime_content_type($thumbnailPath);
                
                return response()->file($thumbnailPath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline',
                    // Disable aggressive caching for thumbnails as well
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                    'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($thumbnailPath)) . ' GMT'
                ]);
            }
        }

        // Fallback to original image
        return $this->serveImage($photo);
    }

    public function serveArticleCover(Article $article)
    {
        if (!$article->cover_image) {
            abort(404, 'Cover not found');
        }

        $filePath = storage_path('app/public/' . $article->cover_image);

        if (!file_exists($filePath)) {
            abort(404, 'Cover file not found');
        }

        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT',
        ]);
    }

    public function toggleFavorite(Photo $photo)
    {
        if (!$photo->is_active) {
            return response()->json(['success' => false, 'message' => 'Photo not found'], 404);
        }

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userId = auth()->id();

        $already = $photo->favoritedBy()->where('users.id', $userId)->exists();
        if ($already) {
            $photo->favoritedBy()->detach($userId);
            $favorited = false;
        } else {
            $photo->favoritedBy()->attach($userId);
            $favorited = true;
        }

        $count = $photo->favoritedBy()->count();

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'favorites_count' => $count,
        ]);
    }

    // Public News Listing
    public function news(Request $request)
    {
        $q = Article::published()->latest();
        if ($s = $request->get('q')) {
            $q->where(function($qq) use ($s){
                $qq->where('title','like',"%$s%")
                   ->orWhere('excerpt','like',"%$s%")
                   ->orWhere('content','like',"%$s%");
            });
        }
        $articles = $q->paginate(9)->withQueryString();
        return view('news.index', compact('articles'));
    }

    // Public News Detail
    public function newsShow($slug)
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article && is_numeric($slug)) {
            $article = Article::find($slug);
        }
        abort_unless($article && $article->is_published, 404);
        return view('news.show', compact('article'));
    }
}
