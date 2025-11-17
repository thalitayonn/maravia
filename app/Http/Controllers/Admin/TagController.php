<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('photos');
        
        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        
        // Sort functionality
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'photos':
                $query->orderBy('photos_count', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            default:
                $query->orderBy('name');
        }
        
        $tags = $query->paginate(20);
        
        // Statistics
        $stats = [
            'total_tags' => Tag::count(),
            'active_tags' => Tag::where('is_active', true)->count(),
            'tagged_photos' => \DB::table('photo_tags')->distinct('photo_id')->count(),
            'most_used' => Tag::withCount('photos')->orderBy('photos_count', 'desc')->first()?->name ?? '-'
        ];
        
        return view('admin.tags.index', compact('tags', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'required|string|max:7',
            'is_active' => 'required|boolean'
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'is_active' => $request->is_active
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $tag->load(['photos' => function($query) {
            $query->where('is_active', true)->latest()->take(12);
        }]);
        
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tags')->ignore($tag->id)],
            'color' => 'required|string|max:7',
            'is_active' => 'required|boolean'
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'is_active' => $request->is_active
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Check if tag has photos
        if ($tag->photos()->count() > 0) {
            return back()->with('error', 'Cannot delete tag that has associated photos.');
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully!');
    }

    /**
     * Toggle tag active status
     */
    public function toggleActive(Tag $tag)
    {
        $tag->update(['is_active' => !$tag->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tag status updated successfully!',
            'is_active' => $tag->is_active
        ]);
    }

    /**
     * Bulk actions for tags
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'tags' => 'required|array|min:1',
            'tags.*' => 'exists:tags,id'
        ]);

        $tags = Tag::whereIn('id', $request->tags);
        
        switch ($request->action) {
            case 'activate':
                $tags->update(['is_active' => true]);
                $message = 'Selected tags activated successfully!';
                break;
                
            case 'deactivate':
                $tags->update(['is_active' => false]);
                $message = 'Selected tags deactivated successfully!';
                break;
                
            case 'delete':
                // Check if any tags have photos
                $tagsWithPhotos = $tags->withCount('photos')->get()->filter(function($tag) {
                    return $tag->photos_count > 0;
                });
                
                if ($tagsWithPhotos->count() > 0) {
                    return back()->with('error', 'Cannot delete tags that have associated photos.');
                }
                
                $tags->delete();
                $message = 'Selected tags deleted successfully!';
                break;
        }

        return redirect()->route('admin.tags.index')->with('success', $message);
    }
}
