<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::with('category')->latest();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q)=>$q->where('title','like',"%$s%")->orWhere('description','like',"%$s%"));
        }
        $videos = $query->paginate(12)->withQueryString();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'video' => 'required|file|mimes:mp4,webm,ogg|max:204800', // 200MB
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $video = new Video();
        $video->title = $request->title;
        $video->description = $request->description;
        $video->category_id = $request->category_id;
        $video->is_featured = $request->boolean('is_featured');
        $video->is_active = $request->boolean('is_active', true);
        $video->uploaded_by = auth()->id();

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $file = $request->file('video');
            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('videos', $filename, 'public');
            $video->file_path = $path;

            // Try to generate poster & duration via FFmpeg/FFprobe
            [$poster, $duration] = $this->generatePosterAndDuration(storage_path('app/public/'.$path));
            if ($poster) {
                $video->poster_path = 'videos/posters/'.$poster;
            }
            if ($duration) {
                $video->duration = $duration;
            }
        }

        $video->save();
        return redirect()->route('admin.videos.index')->with('success','Video uploaded successfully');
    }

    public function edit(Video $video)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.videos.edit', compact('video','categories'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'video' => 'nullable|file|mimes:mp4,webm,ogg|max:204800',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $video->title = $request->title;
        $video->description = $request->description;
        $video->category_id = $request->category_id;
        $video->is_featured = $request->boolean('is_featured');
        $video->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            // remove old
            if ($video->file_path) Storage::disk('public')->delete([$video->file_path]);
            $file = $request->file('video');
            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('videos', $filename, 'public');
            $video->file_path = $path;
            [$poster, $duration] = $this->generatePosterAndDuration(storage_path('app/public/'.$path));
            if ($poster) { $video->poster_path = 'videos/posters/'.$poster; }
            if ($duration) { $video->duration = $duration; }
        }

        $video->save();
        return redirect()->route('admin.videos.index')->with('success','Video updated');
    }

    public function destroy(Video $video)
    {
        if ($video->file_path) Storage::disk('public')->delete($video->file_path);
        if ($video->poster_path) Storage::disk('public')->delete($video->poster_path);
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success','Video deleted');
    }

    private function generatePosterAndDuration(string $fullVideoPath): array
    {
        try {
            $ffmpeg = env('FFMPEG_PATH', 'ffmpeg');
            $ffprobe = env('FFPROBE_PATH', 'ffprobe');

            // Duration via ffprobe
            $duration = null;
            $probeCmd = '"'.$ffprobe.'" -v error -show_entries format=duration -of default=nk=1:nw=1 "'.$fullVideoPath.'"';
            $out = @shell_exec($probeCmd);
            if ($out !== null) {
                $duration = (float)trim($out);
                if ($duration <= 0) $duration = null;
            }

            // Poster frame at 1s
            $posterName = 'thumb_'.pathinfo($fullVideoPath, PATHINFO_FILENAME).'.jpg';
            $posterFull = storage_path('app/public/videos/posters/'.$posterName);
            if (!is_dir(dirname($posterFull))) @mkdir(dirname($posterFull), 0755, true);
            $thumbCmd = '"'.$ffmpeg.'" -y -ss 00:00:01 -i "'.$fullVideoPath.'" -frames:v 1 -q:v 2 "'.$posterFull.'" 2>&1';
            @shell_exec($thumbCmd);

            if (file_exists($posterFull) && filesize($posterFull) > 0) {
                return [$posterName, $duration];
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }
        return [null, null];
    }
}
