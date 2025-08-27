<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Category::withCount('photos');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Sort options
        $sort = $request->get('sort', 'order');
        switch ($sort) {
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
                $query->ordered();
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->description = $request->description;
            $category->color = $request->color ?: '#3B82F6';
            $category->icon = $request->icon;
            $category->is_active = $request->boolean('is_active', true);
            
            // Set order to be last
            $category->order = Category::max('order') + 1;
            
            $category->save();

            return redirect()->route('admin.categories.index')
                           ->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    public function show(Category $category)
    {
        $category->load(['photos' => function ($query) {
            $query->latest()->take(12);
        }]);
        
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        try {
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->description = $request->description;
            $category->color = $request->color ?: '#3B82F6';
            $category->icon = $request->icon;
            $category->is_active = $request->boolean('is_active', true);
            
            $category->save();

            return redirect()->route('admin.categories.index')
                           ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Check if category has photos
            if ($category->photos()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'Cannot delete category that contains photos. Please move or delete the photos first.');
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                           ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    public function toggleActive(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'Category activated' : 'Category deactivated'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer|min:1'
        ]);

        try {
            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])
                       ->update(['order' => $categoryData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category order updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $categories = Category::whereIn('id', $request->categories);
        $count = $categories->count();

        try {
            switch ($request->action) {
                case 'delete':
                    // Check if any categories have photos
                    $categoriesWithPhotos = $categories->withCount('photos')
                                                     ->get()
                                                     ->filter(function ($category) {
                                                         return $category->photos_count > 0;
                                                     });
                    
                    if ($categoriesWithPhotos->count() > 0) {
                        $names = $categoriesWithPhotos->pluck('name')->join(', ');
                        return redirect()->back()
                                       ->with('error', "Cannot delete categories that contain photos: {$names}");
                    }
                    
                    $categories->delete();
                    $message = "{$count} categories deleted successfully!";
                    break;
                    
                case 'activate':
                    $categories->update(['is_active' => true]);
                    $message = "{$count} categories activated successfully!";
                    break;
                    
                case 'deactivate':
                    $categories->update(['is_active' => false]);
                    $message = "{$count} categories deactivated successfully!";
                    break;
            }

            return redirect()->route('admin.categories.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}
