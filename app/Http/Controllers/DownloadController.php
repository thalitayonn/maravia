<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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

        $size = $request->get('size', 'original');
        $watermark = $request->get('watermark', 'true') === 'true';

        try {
            $manager = new ImageManager(new Driver());
            
            // Get the original image path
            $imagePath = storage_path('app/public/' . $photo->image_path);
            
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
        $schoolName = config('app.name', 'School Gallery');
        $watermarkText = $schoolName . ' - ' . $photo->title;
        
        // Calculate font size based on image dimensions
        $fontSize = max(12, min(48, $width / 30));
        
        // Add semi-transparent overlay
        $image->drawRectangle(0, $height - 60, $width, $height, function ($draw) {
            $draw->background('rgba(0, 0, 0, 0.7)');
        });

        // Add main watermark text
        $image->text($watermarkText, $width / 2, $height - 35, function ($font) use ($fontSize) {
            $font->filename(public_path('fonts/arial.ttf')); // You may need to add a font file
            $font->size($fontSize);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add smaller copyright text
        $copyrightText = '© ' . date('Y') . ' ' . config('app.name', 'School Gallery') . ' - All Rights Reserved';
        $image->text($copyrightText, $width / 2, $height - 15, function ($font) use ($fontSize) {
            $font->filename(public_path('fonts/arial.ttf'));
            $font->size($fontSize * 0.6);
            $font->color('#cccccc');
            $font->align('center');
            $font->valign('middle');
        });

        // Add diagonal watermark for extra protection
        $diagonalText = strtoupper($schoolName);
        $image->text($diagonalText, $width / 2, $height / 2, function ($font) use ($fontSize, $width) {
            $font->filename(public_path('fonts/arial.ttf'));
            $font->size($fontSize * 2);
            $font->color('rgba(255, 255, 255, 0.1)');
            $font->align('center');
            $font->valign('middle');
            $font->angle(45);
        });

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
            'photo_ids' => 'required|array',
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

        $size = $request->get('size', 'medium');
        $watermark = $request->get('watermark', true);

        // Create a temporary zip file
        $zipFileName = 'gallery_photos_' . date('Y-m-d_H-i-s') . '.zip';
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
                $imagePath = storage_path('app/public/' . $photo->image_path);
                
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
}
