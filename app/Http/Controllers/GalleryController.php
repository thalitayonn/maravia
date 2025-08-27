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
        
        return view('gallery.index', compact('featuredPhotos', 'categories', 'recentPhotos'));
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
}
