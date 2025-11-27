<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $q = Article::query()->latest();
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn($qq)=>$qq->where('title','like',"%$s%")
                ->orWhere('excerpt','like',"%$s%")
                ->orWhere('content','like',"%$s%"));
        }
        $articles = $q->paginate(12)->withQueryString();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240', // 10MB
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        $article = new Article();
        $article->title = $request->title;
        $article->slug = Str::slug($request->title).'-'.Str::random(6);
        $article->excerpt = $request->excerpt;
        $article->content = $request->content;

        $isPublished = $request->boolean('is_published');
        $publishedAt = $request->published_at ? Carbon::parse($request->published_at) : null;
        // If publish now checked and no date provided, set now
        if ($isPublished && ! $publishedAt) {
            $publishedAt = now();
        }
        // If publish now checked but date is in the future, override to now (publish immediately)
        if ($isPublished && $publishedAt && $publishedAt->isFuture()) {
            $publishedAt = now();
        }
        // If not publish now and date is in future, keep as draft until the date passes
        if (! $isPublished && $publishedAt && $publishedAt->isFuture()) {
            $isPublished = false;
        }

        $article->is_published = $isPublished;
        $article->published_at = $publishedAt;
        $article->author_id = auth()->id();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('articles', 'public');
            $article->cover_image = $path;
        }

        $article->save();
        // Log activity
        if ($admin = Auth::guard('admin')->user()) {
            $admin->recordActivity('article_created', $article, [
                'title' => $article->title,
            ]);
        }
        return redirect()->route('admin.articles.index')->with('success','Article created');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240', // 10MB
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        $originalTitle = $article->title;
        $article->title = $request->title;
        if ($originalTitle !== $request->title) {
            $article->slug = Str::slug($request->title).'-'.Str::random(6);
        }
        $article->excerpt = $request->excerpt;
        $article->content = $request->content;

        $isPublished = $request->boolean('is_published');
        $publishedAt = $request->published_at ? Carbon::parse($request->published_at) : null;
        if ($isPublished && ! $publishedAt) {
            $publishedAt = now();
        }
        if ($isPublished && $publishedAt && $publishedAt->isFuture()) {
            $publishedAt = now();
        }
        if (! $isPublished && $publishedAt && $publishedAt->isFuture()) {
            $isPublished = false;
        }
        $article->is_published = $isPublished;
        $article->published_at = $publishedAt;

        if ($request->hasFile('cover')) {
            if ($article->cover_image) Storage::disk('public')->delete($article->cover_image);
            $path = $request->file('cover')->store('articles', 'public');
            $article->cover_image = $path;
        }

        $article->save();
        if ($admin = Auth::guard('admin')->user()) {
            $admin->recordActivity('article_updated', $article, [
                'title' => $article->title,
            ]);
        }
        return redirect()->route('admin.articles.index')->with('success','Article updated');
    }

    public function destroy(Article $article)
    {
        if ($article->cover_image) Storage::disk('public')->delete($article->cover_image);
        $title = $article->title;
        $article->delete();
        if ($admin = Auth::guard('admin')->user()) {
            $admin->recordActivity('article_deleted', null, [
                'title' => $title,
            ]);
        }
        return redirect()->route('admin.articles.index')->with('success','Article deleted');
    }
}
