<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{
    public function __construct()
    {
        // Middleware handled by route group
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

        // Filter by featured
        if ($request->has('featured') && $request->featured !== '') {
            $query->where('is_featured', $request->featured);
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
        
        // Stats for dashboard
        $stats = [
            'featured' => Photo::where('is_featured', true)->count(),
            'active' => Photo::where('is_active', true)->count(),
            'total_views' => Photo::sum('view_count'),
        ];

        return view('admin.photos.index', compact('photos', 'categories', 'stats'));
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
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $image = $request->file('image');
                
                // Validate file exists and is readable
                if (!$image->isValid()) {
                    throw new \Exception('Uploaded file is not valid');
                }
                
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

                // Store original image using Laravel's store method (more reliable)
                $path = $image->storeAs('photos', $filename, 'public');
                
                $photo->path = $path;
                $photo->filename = $filename;
                $photo->original_filename = $image->getClientOriginalName();
                $photo->mime_type = $image->getMimeType();

                // Get full path for thumbnail creation
                $fullPath = storage_path('app/public/' . $path);

                // Create thumbnail using GD library
                $thumbnailFilename = 'thumb_' . $filename;
                $thumbnailFullPath = storage_path('app/public/photos/thumbnails/' . $thumbnailFilename);
                
                // Ensure thumbnail directory exists
                $thumbnailDir = dirname($thumbnailFullPath);
                if (!file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }
                
                $this->createThumbnail($fullPath, $thumbnailFullPath, 300, 300);
                $photo->thumbnail_path = 'photos/thumbnails/' . $thumbnailFilename;

                // Get image dimensions
                $imageInfo = getimagesize($fullPath);
                $photo->width = $imageInfo[0];
                $photo->height = $imageInfo[1];
                $photo->file_size = $image->getSize();
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
                // Delete old images from storage
                $oldStoragePath = storage_path('app/public/' . $photo->path);
                $oldThumbnailPath = storage_path('app/public/' . $photo->thumbnail_path);
                
                if ($photo->path && file_exists($oldStoragePath)) {
                    unlink($oldStoragePath);
                }
                if ($photo->thumbnail_path && file_exists($oldThumbnailPath)) {
                    unlink($oldThumbnailPath);
                }
                
                // Also delete from public/storage if exists
                $oldPublicPath = public_path('storage/' . $photo->path);
                $oldPublicThumb = public_path('storage/' . $photo->thumbnail_path);
                
                if (file_exists($oldPublicPath)) {
                    unlink($oldPublicPath);
                }
                if (file_exists($oldPublicThumb)) {
                    unlink($oldPublicThumb);
                }

                $image = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Store new image using Laravel's store method
                $path = $image->storeAs('photos', $filename, 'public');
                
                $photo->path = $path;
                $photo->filename = $filename;
                $photo->original_filename = $image->getClientOriginalName();
                $photo->mime_type = $image->getMimeType();

                // Get full path for thumbnail creation
                $fullPath = storage_path('app/public/' . $path);
                
                // Create new thumbnail using GD library
                $thumbnailFilename = 'thumb_' . $filename;
                $thumbnailFullPath = storage_path('app/public/photos/thumbnails/' . $thumbnailFilename);
                
                // Ensure thumbnail directory exists
                $thumbnailDir = dirname($thumbnailFullPath);
                if (!file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }
                
                $this->createThumbnail($fullPath, $thumbnailFullPath, 300, 300);
                $photo->thumbnail_path = 'photos/thumbnails/' . $thumbnailFilename;

                // Update image info
                $imageInfo = getimagesize($fullPath);
                $photo->width = $imageInfo[0];
                $photo->height = $imageInfo[1];
                $photo->file_size = $image->getSize();
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
            // Delete image files from storage
            $storagePath = storage_path('app/public/' . $photo->path);
            $thumbnailPath = storage_path('app/public/' . $photo->thumbnail_path);
            
            if ($photo->path && file_exists($storagePath)) {
                unlink($storagePath);
            }
            if ($photo->thumbnail_path && file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }

            // Also try to delete from public/storage if symlink exists
            $publicPath = public_path('storage/' . $photo->path);
            $publicThumbPath = public_path('storage/' . $photo->thumbnail_path);
            
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
            if (file_exists($publicThumbPath)) {
                unlink($publicThumbPath);
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
                        // Delete files from storage
                        $storagePath = storage_path('app/public/' . $photo->path);
                        $thumbnailPath = storage_path('app/public/' . $photo->thumbnail_path);
                        
                        if ($photo->path && file_exists($storagePath)) {
                            unlink($storagePath);
                        }
                        if ($photo->thumbnail_path && file_exists($thumbnailPath)) {
                            unlink($thumbnailPath);
                        }
                        
                        // Also delete from public/storage if exists
                        $publicPath = public_path('storage/' . $photo->path);
                        $publicThumb = public_path('storage/' . $photo->thumbnail_path);
                        
                        if (file_exists($publicPath)) {
                            unlink($publicPath);
                        }
                        if (file_exists($publicThumb)) {
                            unlink($publicThumb);
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

    private function createThumbnail($sourcePath, $destinationPath, $width, $height)
    {
        // Check if GD extension is loaded
        if (!extension_loaded('gd')) {
            // If GD not available, just copy the original file
            copy($sourcePath, $destinationPath);
            return true;
        }

        try {
            $imageInfo = getimagesize($sourcePath);
            $mime = $imageInfo['mime'];

            switch ($mime) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($sourcePath);
                    break;
                default:
                    // Unsupported format, just copy
                    copy($sourcePath, $destinationPath);
                    return true;
            }

            $sourceWidth = imagesx($source);
            $sourceHeight = imagesy($source);

            // Calculate aspect ratio
            $aspectRatio = $sourceWidth / $sourceHeight;
            
            if ($width / $height > $aspectRatio) {
                $width = $height * $aspectRatio;
            } else {
                $height = $width / $aspectRatio;
            }

            $thumbnail = imagecreatetruecolor($width, $height);
            
            // Preserve transparency for PNG and GIF
            if ($mime == 'image/png' || $mime == 'image/gif') {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
                imagefilledrectangle($thumbnail, 0, 0, $width, $height, $transparent);
            }

            imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($thumbnail, $destinationPath, 90);
                    break;
                case 'image/png':
                    imagepng($thumbnail, $destinationPath);
                    break;
                case 'image/gif':
                    imagegif($thumbnail, $destinationPath);
                    break;
            }

            imagedestroy($source);
            imagedestroy($thumbnail);
            
            return true;
        } catch (\Exception $e) {
            // If thumbnail creation fails, just copy the original
            copy($sourcePath, $destinationPath);
            return true;
        }
    }
}
