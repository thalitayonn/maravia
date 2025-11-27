@extends('layouts.app')

@section('content')
<div class="relative">
    <section class="py-16 scroll-mt-20 relative z-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6"><i class="fas fa-arrow-left"></i> Kembali ke Berita</a>

            <h1 class="text-4xl font-extrabold text-gray-900 mb-3">{{ $article->title }}</h1>
            <div class="flex items-center gap-3 text-sm text-gray-600 mb-6">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 border text-gray-700"><i class="fas fa-folder"></i> {{ $article->category->name ?? 'Umum' }}</span>
                <span class="inline-flex items-center gap-1"><i class="fas fa-clock"></i> {{ $article->published_at?->format('d M Y H:i') ?? $article->created_at?->diffForHumans() }}</span>
                @if($article->author)
                    <span class="inline-flex items-center gap-1"><i class="fas fa-user"></i> {{ $article->author->name }}</span>
                @endif
            </div>

            @php $cover = $article->cover_image ?? $article->thumbnail ?? null; @endphp
            @if($cover)
                <div class="rounded-2xl overflow-hidden mb-8 shadow bg-gray-100 max-h-[60vh]">
                    <img src="{{ url('/api/articles/'.$article->id.'/cover') }}"
                         alt="{{ $article->title }}"
                         class="w-full object-cover h-40 sm:h-44 md:h-48"
                         onerror="this.onerror=null; this.src='{{ asset('storage/'.$cover) }}';">
                </div>
            @endif

            @if($article->excerpt)
                <p class="text-lg text-gray-700 mb-6">{{ $article->excerpt }}</p>
            @endif

            <div class="prose max-w-none">
                {!! $article->content !!}
            </div>
            <div class="mt-8 flex justify-end">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white" style="background:#FF6F61;">
                    Tutup
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
