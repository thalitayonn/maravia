<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Category;
use App\Models\Tag;
use App\Models\PhotoView;
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
        
        // Load recent videos and articles
        $recentVideos = collect();
        $recentArticles = collect();
        
        try {
            if (class_exists('App\Models\Video')) {
                $recentVideos = \App\Models\Video::active()->featured()->latest()->limit(6)->get();
                if ($recentVideos->isEmpty()) {
                    $recentVideos = \App\Models\Video::active()->latest()->limit(6)->get();
                }
            }
        } catch (\Exception $e) {
            $recentVideos = collect();
        }
        
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

        return view('gallery.index', compact('featuredPhotos', 'categories', 'recentPhotos', 'stats', 'recentVideos', 'recentArticles'));
    }

    public function gallery(Request $request)
    {
        $query = Photo::active()->with(['category', 'tags']);
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }
        
        // Filter by tag
        if ($request->has('tag') && $request->tag) {
            $query->byTag($request->tag);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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
        
        // Get related photos from same category
        $relatedPhotos = Photo::active()
            ->where('category_id', $photo->category_id)
            ->where('id', '!=', $photo->id)
            ->with(['category', 'tags'])
            ->latest()
            ->take(6)
            ->get();
        
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
                      ->orWhereHas('tags', function ($tagQuery) use ($query) {
                          $tagQuery->where('name', 'like', "%{$query}%");
                      })
                      ->orWhereHas('category', function ($catQuery) use ($query) {
                          $catQuery->where('name', 'like', "%{$query}%");
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
                  ->orWhere('description', 'like', "%{$search}%");
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
            abort(404, 'Image file not found');
        }

        $mimeType = mime_content_type($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'public, max-age=86400', // 24 hours
            'Expires' => gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT',
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
                    'Cache-Control' => 'public, max-age=86400',
                    'Expires' => gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT',
                    'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($thumbnailPath)) . ' GMT'
                ]);
            }
        }

        // Fallback to original image
        return $this->serveImage($photo);
    }
}
