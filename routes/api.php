<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Debug API route
Route::get('/debug', function () {
    return response()->json([
        'status' => 'API Working!',
        'timestamp' => now(),
        'routes' => [
            'photos_list' => url('/api/photos'),
            'photo_detail' => url('/api/photos/1'),
            'photo_image' => url('/api/photos/1/image'),
            'photo_thumbnail' => url('/api/photos/1/thumbnail'),
        ]
    ]);
});

// Photos API Routes
Route::prefix('photos')->name('photos.')->group(function () {
    Route::get('/', [GalleryController::class, 'apiIndex'])->name('index');
    Route::get('/{photo}', [GalleryController::class, 'apiShow'])->name('show');
    Route::get('/{photo}/image', [GalleryController::class, 'serveImage'])->name('image');
    Route::get('/{photo}/thumbnail', [GalleryController::class, 'serveThumbnail'])->name('thumbnail');
});
