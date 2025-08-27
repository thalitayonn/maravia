<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\TestimonialController as GuestTestimonialController;
use App\Http\Controllers\PageController as GuestPageController;
use App\Http\Controllers\Admin\PhotoCommentController;
use App\Http\Controllers\DownloadController;

// Guest Routes
Route::get('/', [GalleryController::class, 'index'])->name('home');
Route::get('/gallery', [GalleryController::class, 'gallery'])->name('gallery');
Route::get('/gallery/photo/{photo}', [GalleryController::class, 'show'])->name('gallery.photo');
Route::get('/gallery/category/{category}', [GalleryController::class, 'category'])->name('gallery.category');
Route::get('/gallery/tag/{tag}', [GalleryController::class, 'tag'])->name('gallery.tag');
Route::get('/search', [GalleryController::class, 'search'])->name('gallery.search');
Route::post('/gallery/photo/{photo}/view', [GalleryController::class, 'trackView'])->name('gallery.track-view');

// Download routes
Route::get('/download/photo/{photo}', [DownloadController::class, 'downloadPhoto'])->name('download.photo');
Route::post('/download/bulk', [DownloadController::class, 'bulkDownload'])->name('download.bulk');

// Testimonials
Route::get('/testimonials', [GuestTestimonialController::class, 'index'])->name('testimonials');
Route::post('/testimonials', [GuestTestimonialController::class, 'store'])->name('testimonials.store');

// Dynamic Pages
Route::get('/page/{page}', [GuestPageController::class, 'show'])->name('page.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Photo Management
    Route::resource('photos', PhotoController::class);
    Route::post('/photos/upload', [PhotoController::class, 'upload'])->name('photos.upload');
    Route::post('/photos/{photo}/toggle-featured', [PhotoController::class, 'toggleFeatured'])->name('photos.toggle-featured');
    Route::post('/photos/{photo}/toggle-active', [PhotoController::class, 'toggleActive'])->name('photos.toggle-active');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Tags
    Route::resource('tags', TagController::class);
    
    // Pages
    Route::resource('pages', PageController::class);
    
    // Testimonials
    Route::resource('testimonials', TestimonialController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{testimonial}/feature', [TestimonialController::class, 'toggleFeatured'])->name('testimonials.feature');
    
    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/statistics/photos', [StatisticsController::class, 'photos'])->name('statistics.photos');
    Route::get('/statistics/categories', [StatisticsController::class, 'categories'])->name('statistics.categories');
    
    // Backup & Restore
    Route::get('/backup', [BackupController::class, 'index'])->name('backup');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    
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
