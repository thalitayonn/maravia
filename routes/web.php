<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\TestimonialController as GuestTestimonialController;
use App\Http\Controllers\Admin\PhotoCommentController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CommentController;

// Guest Routes
Route::get('/', [GalleryController::class, 'index'])->name('home');
Route::get('/gallery', [GalleryController::class, 'gallery'])->name('gallery');
Route::get('/gallery/photo/{photo}', [GalleryController::class, 'show'])->name('gallery.photo');
Route::get('/gallery/category/{category}', [GalleryController::class, 'category'])->name('gallery.category');
Route::get('/gallery/tag/{tag}', [GalleryController::class, 'tag'])->name('gallery.tag');
Route::get('/search', [GalleryController::class, 'search'])->name('gallery.search');
Route::post('/gallery/photo/{photo}/view', [GalleryController::class, 'trackView'])->name('gallery.track-view');

// Public News routes
Route::get('/news', [GalleryController::class, 'news'])->name('news.index');
Route::get('/news/{slug}', [GalleryController::class, 'newsShow'])->name('news.show');

// Download routes
Route::get('/download/photo/{photo}', [DownloadController::class, 'downloadPhoto'])->name('download.photo');
Route::post('/download/bulk', [DownloadController::class, 'bulkDownload'])->name('download.bulk');

// Comment routes
Route::get('/gallery/photo/{photo}/comments', [CommentController::class, 'getComments'])->name('gallery.photo.comments');
Route::post('/gallery/photo/{photo}/comments', [CommentController::class, 'store'])->middleware('auth')->name('gallery.photo.comment.store');
Route::post('/comments/{comment}/react', [CommentController::class, 'toggleReaction'])->middleware('auth')->name('comments.react');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');

// User favorite routes (public gallery)
Route::post('/photos/{photo}/favorite', [GalleryController::class, 'toggleFavorite'])->middleware('auth')->name('photos.favorite.toggle');


// Testimonials
Route::get('/testimonials', [GuestTestimonialController::class, 'index'])->name('testimonials');
Route::post('/testimonials', [GuestTestimonialController::class, 'store'])->name('testimonials.store');



// Authentication Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post')->middleware('guest');

// User Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Safe GET fallback for users who open /logout directly: auto-submit POST with CSRF
Route::get('/logout', function () {
    if (!auth()->check()) {
        return redirect('/');
    }
    $action = route('logout');
    $token = csrf_token();
    return response("<form id='f' method='POST' action='{$action}'>
        <input type='hidden' name='_token' value='{$token}' />
    </form>
    <script>document.getElementById('f').submit();</script>
    <noscript>
      <p>Click the button to logout:</p>
      <button type='submit' form='f'>Logout</button>
    </noscript>", 200)->header('Content-Type', 'text/html');
})->name('logout.get');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard route utama - redirect ke homepage untuk user biasa
Route::get('/dashboard', function() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    // Jika admin, ke admin dashboard
    if ($user->is_admin || $user->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    
    // User biasa ke homepage yang sederhana
    return redirect('/')->with('welcome', 'Selamat datang, ' . $user->name . '!');
})->name('dashboard');

// FORCE user dashboard (bypass admin check)
Route::get('/force-user-dashboard', [UserDashboardController::class, 'index']);

// Route untuk serve foto dengan placeholder jika tidak ada
Route::get('/test/photo/{id}', function($id) {
    $photo = \App\Models\Photo::find($id);
    if (!$photo) {
        // Return placeholder image
        return response()->file(public_path('images/placeholder.jpg'));
    }
    
    // Cek apakah file foto ada
    $filePath = storage_path('app/public/' . $photo->path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    
    // Jika tidak ada, buat placeholder dengan warna random
    $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD'];
    $color = $colors[array_rand($colors)];
    
    // Generate SVG placeholder
    $svg = '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <rect width="100%" height="100%" fill="' . $color . '"/>
        <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="24" fill="white" text-anchor="middle" dy=".3em">' . $photo->title . '</text>
    </svg>';
    
    return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
});

// Route untuk generate foto sample
Route::get('/create-sample-photos', function() {
    // Buat kategori sample
    $categories = [
        ['name' => 'Kegiatan Sekolah', 'icon' => 'school'],
        ['name' => 'Olahraga', 'icon' => 'running'],
        ['name' => 'Seni & Budaya', 'icon' => 'palette'],
        ['name' => 'Prestasi', 'icon' => 'trophy'],
    ];
    
    foreach($categories as $catData) {
        \App\Models\Category::firstOrCreate(
            ['name' => $catData['name']], 
            ['icon' => $catData['icon']]
        );
    }
    
    // Buat foto sample
    $photos = [
        [
            'title' => 'Upacara Bendera Hari Senin',
            'description' => 'Kegiatan rutin upacara bendera yang diikuti seluruh siswa dengan khidmat',
            'category' => 'Kegiatan Sekolah'
        ],
        [
            'title' => 'Lomba Basket Antar Kelas',
            'description' => 'Pertandingan seru basket antar kelas dalam rangka pekan olahraga',
            'category' => 'Olahraga'
        ],
        [
            'title' => 'Pentas Seni Budaya',
            'description' => 'Pertunjukan tari tradisional dalam acara pentas seni sekolah',
            'category' => 'Seni & Budaya'
        ],
        [
            'title' => 'Juara Olimpiade Matematika',
            'description' => 'Siswa berprestasi meraih juara dalam olimpiade matematika tingkat kota',
            'category' => 'Prestasi'
        ],
        [
            'title' => 'Kegiatan Ekstrakurikuler Pramuka',
            'description' => 'Latihan rutin pramuka dengan berbagai kegiatan menarik',
            'category' => 'Kegiatan Sekolah'
        ]
    ];
    
    foreach($photos as $photoData) {
        $category = \App\Models\Category::where('name', $photoData['category'])->first();
        
        \App\Models\Photo::firstOrCreate(
            ['title' => $photoData['title']],
            [
                'description' => $photoData['description'],
                'path' => 'photos/sample-' . strtolower(str_replace(' ', '-', $photoData['title'])) . '.jpg',
                'category_id' => $category->id,
                'is_featured' => true,
                'views' => rand(50, 500),
                'favorites_count' => rand(5, 50)
            ]
        );
    }
    
    return 'Sample photos created successfully! <a href="/">Go to homepage</a>';
})->name('create-sample-photos');

// Route untuk dashboard kompleks (jika diperlukan)
Route::get('/my-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard.full')->middleware('auth');

// User Dashboard Routes
Route::prefix('dashboard')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/photos/{photo}/favorite', [UserDashboardController::class, 'toggleFavorite'])->name('photos.favorite');
    Route::post('/photos/{photo}/rate', [UserDashboardController::class, 'ratePhoto'])->name('photos.rate');
    Route::post('/collections', [UserDashboardController::class, 'createCollection'])->name('collections.create');
    Route::post('/collections/{collection}/photos/{photo}', [UserDashboardController::class, 'addToCollection'])->name('collections.add-photo');
    Route::get('/collections', [UserDashboardController::class, 'getCollections'])->name('collections.index');
    Route::get('/stats', [UserDashboardController::class, 'getStats'])->name('stats');
});

// Comment Routes
Route::prefix('photos/{photo}/comments')->name('comments.')->group(function () {
    Route::get('/', [CommentController::class, 'getComments'])->name('index');
    Route::post('/', [CommentController::class, 'store'])->name('store');
});

Route::prefix('comments')->name('comments.')->middleware(['auth'])->group(function () {
    Route::post('/{comment}/reaction', [CommentController::class, 'toggleReaction'])->name('reaction');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // Keep '/dashboard' working but avoid duplicate route name by redirecting to the named route
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    // Safe GET fallback for admins who open /admin/logout directly
    Route::get('/logout', function () {
        if (!\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        $action = route('admin.logout');
        $token = csrf_token();
        return response("<form id='f' method='POST' action='{$action}'>
            <input type='hidden' name='_token' value='{$token}' />
        </form>
        <script>document.getElementById('f').submit();</script>
        <noscript>
          <p>Click the button to logout:</p>
          <button type='submit' form='f'>Logout</button>
        </noscript>", 200)->header('Content-Type', 'text/html');
    })->name('admin.logout.get');
    
    // Photos
    Route::resource('photos', PhotoController::class);
    Route::post('/photos/upload', [PhotoController::class, 'upload'])->name('photos.upload');
    Route::post('/photos/{photo}/toggle-featured', [PhotoController::class, 'toggleFeatured'])->name('photos.toggle-featured');
    Route::post('/photos/{photo}/toggle-active', [PhotoController::class, 'toggleActive'])->name('photos.toggle-active');
    Route::post('/photos/bulk-action', [PhotoController::class, 'bulkAction'])->name('photos.bulk-action');

    // Articles
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);

    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
    Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order');
    Route::post('/categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    
    // Tags
    Route::resource('tags', TagController::class)->except(['show']);
    Route::post('/tags/{tag}/toggle-active', [TagController::class, 'toggleActive'])->name('tags.toggle-active');
    Route::post('/tags/bulk-action', [TagController::class, 'bulkAction'])->name('tags.bulk-action');
    
    // Pages module removed
    
    // Testimonials
    Route::resource('testimonials', TestimonialController::class)->only(['index', 'destroy']);
    Route::post('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::post('/testimonials/bulk-action', [TestimonialController::class, 'bulkAction'])->name('testimonials.bulk-action');
    
    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/statistics/photos', [StatisticsController::class, 'photos'])->name('statistics.photos');
    Route::get('/statistics/categories', [StatisticsController::class, 'categories'])->name('statistics.categories');
    
    // Backup & Restore
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}/delete', [BackupController::class, 'delete'])->name('backup.delete');
    
    // Admin Management
    Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
    Route::post('/admins', [AdminController::class, 'createAdmin'])->name('admins.create');
    Route::delete('/admins/{user}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
    
    // Comment Management
    Route::get('comments', [PhotoCommentController::class, 'index'])->name('comments.index');
    Route::get('comments/{comment}', [PhotoCommentController::class, 'show'])->name('comments.show');
    Route::delete('comments/{comment}', [PhotoCommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('comments/{comment}/approve', [PhotoCommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reject', [PhotoCommentController::class, 'reject'])->name('comments.reject');
    Route::post('comments/{comment}/mark-spam', [PhotoCommentController::class, 'markSpam'])->name('comments.mark-spam');
    Route::post('comments/{comment}/unmark-spam', [PhotoCommentController::class, 'unmarkSpam'])->name('comments.unmark-spam');
    Route::post('comments/bulk-action', [PhotoCommentController::class, 'bulkAction'])->name('comments.bulk-action');
    Route::post('comments/auto-moderate', [PhotoCommentController::class, 'autoModerate'])->name('comments.auto-moderate');
});

// API Routes for Photos
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/photos', [GalleryController::class, 'apiIndex'])->name('photos.index');
    Route::get('/photos/{photo}', [GalleryController::class, 'apiShow'])->name('photos.show');
    Route::get('/photos/{photo}/image', [GalleryController::class, 'serveImage'])->name('photos.image');
    Route::get('/photos/{photo}/thumbnail', [GalleryController::class, 'serveThumbnail'])->name('photos.thumbnail');
});

// Test route untuk foto langsung
Route::get('/test/photo/{id}', function ($id) {
    $photo = \App\Models\Photo::find($id);
    if (!$photo) {
        return response('Photo not found', 404);
    }
    
    $imagePath = storage_path('app/public/' . $photo->path);
    if (!file_exists($imagePath)) {
        return response('Image file not found: ' . $photo->path, 404);
    }
    
    return response()->file($imagePath);
});

// Debug route untuk test foto
Route::get('/debug/photos', function () {
    $photos = \App\Models\Photo::active()->with(['category'])->take(5)->get();
    
    $debug = [
        'total_photos' => \App\Models\Photo::count(),
        'active_photos' => \App\Models\Photo::active()->count(),
        'sample_photos' => $photos->map(function($photo) {
            return [
                'id' => $photo->id,
                'title' => $photo->title,
                'path' => $photo->path,
                'thumbnail_path' => $photo->thumbnail_path,
                'file_exists' => file_exists(storage_path('app/public/' . $photo->path)),
                'thumbnail_exists' => $photo->thumbnail_path ? file_exists(storage_path('app/public/' . $photo->thumbnail_path)) : false,
                'api_thumbnail_url' => url('/api/photos/' . $photo->id . '/thumbnail'),
                'api_image_url' => url('/api/photos/' . $photo->id . '/image'),
            ];
        })
    ];
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});

// Storage file serving route (alternative to symbolic link)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    // Debug logging
    \Log::info('Storage route accessed', ['path' => $path, 'filePath' => $filePath, 'exists' => file_exists($filePath)]);
    
    if (!file_exists($filePath)) {
        \Log::warning('File not found in storage route', ['path' => $path, 'filePath' => $filePath]);
        abort(404, 'File not found');
    }
    
    $mimeType = mime_content_type($filePath);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline',
        'Cache-Control' => 'public, max-age=3600',
        'Expires' => gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT'
    ]);
})->where('path', '.*');
