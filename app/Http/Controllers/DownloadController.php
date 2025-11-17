<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{
    public function downloadPhoto(Photo $photo, Request $request)
    {
        // Check if photo is active
        if (!$photo->is_active) {
            abort(404);
        }

        // Increment download count
        $photo->increment('download_count');

        // Record user activity if authenticated
        if (Auth::check()) {
            Auth::user()->recordActivity('download', $photo);
            
            // Update user stats
            if (Auth::user()->stats) {
                Auth::user()->stats->increment('total_downloads');
            }
        }

        $size = $request->get('size', 'original');
        $watermark = $request->get('watermark', 'true') === 'true';

        try {
            $manager = new ImageManager(new Driver());
            
            // Get the original image path
            $imagePath = storage_path('app/public/' . $photo->path);
            
            if (!file_exists($imagePath)) {
                abort(404, 'Image file not found');
            }

            // Load the image
            $image = $manager->read($imagePath);

            // Resize based on requested size
            switch ($size) {
                case 'small':
                    $image->scale(width: 800);
                    break;
                case 'medium':
                    $image->scale(width: 1200);
                    break;
                case 'large':
                    $image->scale(width: 1920);
                    break;
                case 'original':
                default:
                    // Keep original size
                    break;
            }

            // Add watermark if requested
            if ($watermark) {
                $this->addWatermark($image, $photo);
            }

            // Generate filename
            $filename = $this->generateFilename($photo, $size, $watermark);

            // Convert to JPEG and get binary data
            $imageData = $image->toJpeg(90);

            return Response::make($imageData, 200, [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($imageData),
            ]);

        } catch (\Exception $e) {
            abort(500, 'Error processing image: ' . $e->getMessage());
        }
    }

    private function addWatermark($image, Photo $photo)
    {
        $width = $image->width();
        $height = $image->height();

        // Create watermark text
        $schoolName = config('app.name', 'Cione Gallery');
        $watermarkText = $schoolName . ' - ' . $photo->title;
        
        // Calculate font size based on image dimensions
        $fontSize = max(16, min(48, $width / 25));
        
        // Add semi-transparent overlay at bottom
        $overlayHeight = 80;
        $image->drawRectangle(0, $height - $overlayHeight, $width, $height, function ($draw) {
            $draw->background('rgba(0, 0, 0, 0.8)');
        });

        // Add main watermark text
        $image->text($watermarkText, 20, $height - 50, function ($font) use ($fontSize) {
            $font->size($fontSize);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top');
        });

        // Add smaller copyright text
        $copyrightText = ' ' . date('Y') . ' ' . config('app.name', 'Cione Gallery') . ' - All Rights Reserved';
        $image->text($copyrightText, 20, $height - 25, function ($font) use ($fontSize) {
            $font->size($fontSize * 0.7);
            $font->color('#cccccc');
            $font->align('left');
            $font->valign('top');
        });

        // Add website URL
        $websiteUrl = request()->getSchemeAndHttpHost();
        $image->text($websiteUrl, $width - 20, $height - 25, function ($font) use ($fontSize) {
            $font->size($fontSize * 0.7);
            $font->color('#cccccc');
            $font->align('right');
            $font->valign('top');
        });

        // Add diagonal watermark for extra protection
        $diagonalText = strtoupper($schoolName);
        $image->text($diagonalText, $width / 2, $height / 2, function ($font) use ($fontSize, $width) {
            $font->size($fontSize * 3);
            $font->color('rgba(255, 255, 255, 0.08)');
            $font->align('center');
            $font->valign('middle');
            $font->angle(45);
        });

        // Add user info if authenticated
        if (Auth::check()) {
            $userText = 'Downloaded by: ' . Auth::user()->name;
            $image->text($userText, $width - 20, $height - 50, function ($font) use ($fontSize) {
                $font->size($fontSize * 0.6);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('top');
            });
        }

        return $image;
    }

    private function generateFilename(Photo $photo, string $size, bool $watermark): string
    {
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $photo->title);
        $basename = trim($basename, '_');
        
        if (empty($basename)) {
            $basename = 'photo_' . $photo->id;
        }

        $suffix = '';
        if ($size !== 'original') {
            $suffix .= '_' . $size;
        }
        if ($watermark) {
            $suffix .= '_watermarked';
        }

        return $basename . $suffix . '.jpg';
    }

    public function bulkDownload(Request $request)
    {
        $request->validate([
            'photo_ids' => 'required|array|max:50', // Limit bulk downloads
            'photo_ids.*' => 'exists:photos,id',
            'size' => 'in:small,medium,large,original',
            'watermark' => 'boolean'
        ]);

        $photos = Photo::whereIn('id', $request->photo_ids)
                      ->where('is_active', true)
                      ->get();

        if ($photos->isEmpty()) {
            abort(404, 'No valid photos found');
        }

        // Record bulk download activity
        if (Auth::check()) {
            Auth::user()->recordActivity('bulk_download', null, [
                'photo_count' => $photos->count(),
                'photo_ids' => $request->photo_ids
            ]);
            
            // Update user stats
            if (Auth::user()->stats) {
                Auth::user()->stats->increment('total_downloads', $photos->count());
            }
        }

        $size = $request->get('size', 'medium');
        $watermark = $request->get('watermark', true);

        // Create a temporary zip file
        $zipFileName = 'cione_gallery_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            abort(500, 'Cannot create zip file');
        }

        try {
            $manager = new ImageManager(new Driver());

            foreach ($photos as $photo) {
                $imagePath = storage_path('app/public/' . $photo->path);
                
                if (!file_exists($imagePath)) {
                    continue;
                }

                // Process image
                $image = $manager->read($imagePath);

                // Resize
                switch ($size) {
                    case 'small':
                        $image->scale(width: 800);
                        break;
                    case 'medium':
                        $image->scale(width: 1200);
                        break;
                    case 'large':
                        $image->scale(width: 1920);
                        break;
                }

                // Add watermark
                if ($watermark) {
                    $this->addWatermark($image, $photo);
                }

                // Generate filename and add to zip
                $filename = $this->generateFilename($photo, $size, $watermark);
                $imageData = $image->toJpeg(90);
                $zip->addFromString($filename, $imageData);

                // Increment download count
                $photo->increment('download_count');
            }

            $zip->close();

            // Return zip file for download
            return Response::download($zipPath, $zipFileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
            abort(500, 'Error creating zip file: ' . $e->getMessage());
        }
    }

    public function getDownloadStats()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $userStats = [
            'total_downloads' => $user->stats->total_downloads ?? 0,
            'downloads_this_month' => $user->activities()
                ->where('activity_type', 'download')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
            'downloads_this_week' => $user->activities()
                ->where('activity_type', 'download')
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $userStats
        ]);
    }
}
