<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        $article = new Article();
        $article->title = $request->title;
        $article->slug = Str::slug($request->title).'-'.Str::random(6);
        $article->excerpt = $request->excerpt;
        $article->content = $request->content;
        $article->is_published = $request->boolean('is_published');
        $article->published_at = $request->published_at;
        $article->author_id = auth()->id();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('articles', 'public');
            $article->cover_image = $path;
        }

        $article->save();
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
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        $article->title = $request->title;
        if ($article->title !== $request->title) {
            $article->slug = Str::slug($request->title).'-'.Str::random(6);
        }
        $article->excerpt = $request->excerpt;
        $article->content = $request->content;
        $article->is_published = $request->boolean('is_published');
        $article->published_at = $request->published_at;

        if ($request->hasFile('cover')) {
            if ($article->cover_image) Storage::disk('public')->delete($article->cover_image);
            $path = $request->file('cover')->store('articles', 'public');
            $article->cover_image = $path;
        }

        $article->save();
        return redirect()->route('admin.articles.index')->with('success','Article updated');
    }

    public function destroy(Article $article)
    {
        if ($article->cover_image) Storage::disk('public')->delete($article->cover_image);
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success','Article deleted');
    }
}
