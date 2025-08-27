<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Photo::with(['category', 'tags']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'views':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $photos = $query->paginate(12)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('admin.photos.index', compact('photos', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $tags = Tag::active()->orderBy('name')->get();
        
        return view('admin.photos.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $photo = new Photo();
            $photo->title = $request->title;
            $photo->description = $request->description;
            $photo->category_id = $request->category_id;
            $photo->is_featured = $request->boolean('is_featured');
            $photo->is_active = $request->boolean('is_active', true);
            $photo->uploaded_by = auth()->id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Create directories if they don't exist
                $publicPath = public_path('storage/photos');
                $thumbnailPath = public_path('storage/photos/thumbnails');
                
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }
                if (!file_exists($thumbnailPath)) {
                    mkdir($thumbnailPath, 0755, true);
                }

                // Store original image
                $image->move($publicPath, $filename);
                $photo->image_path = 'photos/' . $filename;

                // Create thumbnail
                $thumbnailFilename = 'thumb_' . $filename;
                $img = Image::make($publicPath . '/' . $filename);
                $img->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($thumbnailPath . '/' . $thumbnailFilename);
                $photo->thumbnail_path = 'photos/thumbnails/' . $thumbnailFilename;

                // Get image dimensions
                $imageInfo = getimagesize($publicPath . '/' . $filename);
                $photo->width = $imageInfo[0];
                $photo->height = $imageInfo[1];
                $photo->file_size = filesize($publicPath . '/' . $filename);
            }

            $photo->save();

            // Attach tags
            if ($request->has('tags')) {
                $photo->tags()->attach($request->tags);
            }

            return redirect()->route('admin.photos.index')
                           ->with('success', 'Photo uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to upload photo: ' . $e->getMessage());
        }
    }

    public function show(Photo $photo)
    {
        $photo->load(['category', 'tags', 'uploader']);
        return view('admin.photos.show', compact('photo'));
    }

    public function edit(Photo $photo)
    {
        $categories = Category::active()->ordered()->get();
        $tags = Tag::active()->orderBy('name')->get();
        $photo->load(['tags']);
        
        return view('admin.photos.edit', compact('photo', 'categories', 'tags'));
    }

    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $photo->title = $request->title;
            $photo->description = $request->description;
            $photo->category_id = $request->category_id;
            $photo->is_featured = $request->boolean('is_featured');
            $photo->is_active = $request->boolean('is_active', true);

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old images
                if ($photo->image_path && file_exists(public_path('storage/' . $photo->image_path))) {
                    unlink(public_path('storage/' . $photo->image_path));
                }
                if ($photo->thumbnail_path && file_exists(public_path('storage/' . $photo->thumbnail_path))) {
                    unlink(public_path('storage/' . $photo->thumbnail_path));
                }

                $image = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                $publicPath = public_path('storage/photos');
                $thumbnailPath = public_path('storage/photos/thumbnails');

                // Store new image
                $image->move($publicPath, $filename);
                $photo->image_path = 'photos/' . $filename;

                // Create new thumbnail
                $thumbnailFilename = 'thumb_' . $filename;
                $img = Image::make($publicPath . '/' . $filename);
                $img->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($thumbnailPath . '/' . $thumbnailFilename);
                $photo->thumbnail_path = 'photos/thumbnails/' . $thumbnailFilename;

                // Update image info
                $imageInfo = getimagesize($publicPath . '/' . $filename);
                $photo->width = $imageInfo[0];
                $photo->height = $imageInfo[1];
                $photo->file_size = filesize($publicPath . '/' . $filename);
            }

            $photo->save();

            // Sync tags
            if ($request->has('tags')) {
                $photo->tags()->sync($request->tags);
            } else {
                $photo->tags()->detach();
            }

            return redirect()->route('admin.photos.index')
                           ->with('success', 'Photo updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update photo: ' . $e->getMessage());
        }
    }

    public function destroy(Photo $photo)
    {
        try {
            // Delete image files
            if ($photo->image_path && file_exists(public_path('storage/' . $photo->image_path))) {
                unlink(public_path('storage/' . $photo->image_path));
            }
            if ($photo->thumbnail_path && file_exists(public_path('storage/' . $photo->thumbnail_path))) {
                unlink(public_path('storage/' . $photo->thumbnail_path));
            }

            // Delete database record (this will also delete related records due to foreign key constraints)
            $photo->delete();

            return redirect()->route('admin.photos.index')
                           ->with('success', 'Photo deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete photo: ' . $e->getMessage());
        }
    }

    public function toggleFeatured(Photo $photo)
    {
        $photo->is_featured = !$photo->is_featured;
        $photo->save();

        return response()->json([
            'success' => true,
            'is_featured' => $photo->is_featured,
            'message' => $photo->is_featured ? 'Photo marked as featured' : 'Photo removed from featured'
        ]);
    }

    public function toggleActive(Photo $photo)
    {
        $photo->is_active = !$photo->is_active;
        $photo->save();

        return response()->json([
            'success' => true,
            'is_active' => $photo->is_active,
            'message' => $photo->is_active ? 'Photo activated' : 'Photo deactivated'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,feature,unfeature',
            'photos' => 'required|array',
            'photos.*' => 'exists:photos,id'
        ]);

        $photos = Photo::whereIn('id', $request->photos);
        $count = $photos->count();

        try {
            switch ($request->action) {
                case 'delete':
                    foreach ($photos->get() as $photo) {
                        // Delete files
                        if ($photo->image_path && file_exists(public_path('storage/' . $photo->image_path))) {
                            unlink(public_path('storage/' . $photo->image_path));
                        }
                        if ($photo->thumbnail_path && file_exists(public_path('storage/' . $photo->thumbnail_path))) {
                            unlink(public_path('storage/' . $photo->thumbnail_path));
                        }
                    }
                    $photos->delete();
                    $message = "{$count} photos deleted successfully!";
                    break;
                    
                case 'activate':
                    $photos->update(['is_active' => true]);
                    $message = "{$count} photos activated successfully!";
                    break;
                    
                case 'deactivate':
                    $photos->update(['is_active' => false]);
                    $message = "{$count} photos deactivated successfully!";
                    break;
                    
                case 'feature':
                    $photos->update(['is_featured' => true]);
                    $message = "{$count} photos marked as featured!";
                    break;
                    
                case 'unfeature':
                    $photos->update(['is_featured' => false]);
                    $message = "{$count} photos removed from featured!";
                    break;
            }

            return redirect()->route('admin.photos.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}
