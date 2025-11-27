@extends('layouts.app')

@section('content')
<div class="relative">
    <section class="py-20 scroll-mt-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between gap-4 flex-wrap">
                <div class="text-center md:text-left">
                    <h1 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent mb-2">Berita</h1>
                    <p class="text-xl text-gray-600">Kabar terbaru dan informasi penting</p>
                </div>
                <form method="get" class="flex items-center gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita..." class="px-4 py-2 rounded-xl border bg-white/80">
                    <button class="px-4 py-2 rounded-xl text-white" style="background:#FF6F61">Cari</button>
                </form>
            </div>

            @if($articles->count())
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                <article class="group rounded-2xl overflow-hidden border border-white/20 bg-white/80 backdrop-blur-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="relative w-full overflow-hidden bg-gray-100 flex items-center justify-center" data-article-cover-wrapper>
                        <img src="{{ url('/api/articles/'.$article->id.'/cover') }}"
                             alt="{{ $article->title }}"
                             class="w-full object-cover h-40 sm:h-44 md:h-48"
                             onerror="this.onerror=null; this.closest('[data-article-cover-wrapper]').classList.add('hidden');">
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 border text-gray-700"><i class="fas fa-folder"></i> {{ $article->category->name ?? 'Umum' }}</span>
                            <span class="inline-flex items-center gap-1 text-gray-500"><i class="fas fa-clock"></i> {{ $article->created_at?->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $article->title }}</h3>
                        @if($article->excerpt)
                            <p class="text-gray-700 line-clamp-3 mb-3">{{ $article->excerpt }}</p>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Ringkasan</span>
                            <button type="button"
                                    class="inline-flex items-center gap-2 text-coral-600 font-semibold hover:underline open-news"
                                    data-title="{{ addslashes($article->title) }}"
                                    data-category="{{ $article->category->name ?? 'Umum' }}"
                                    data-date="{{ $article->created_at?->diffForHumans() }}"
                                    data-excerpt="{{ addslashes($article->excerpt ?? '') }}"
                                    data-content="{{ addslashes($article->content ?? '') }}"
                                    data-cover="{{ url('/api/articles/'.$article->id.'/cover') }}">
                                Baca selengkapnya <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $articles->links() }}</div>
            @else
                <div class="text-center text-gray-500 py-20">Belum ada berita.</div>
            @endif
        </div>
    </section>

    <!-- News Modal (text-only) -->
    <div id="newsModal" class="fixed inset-0 bg-black/30 backdrop-blur-md z-[1000] hidden opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="max-w-3xl w-full bg-white/90 backdrop-blur-xl rounded-[20px] overflow-hidden shadow-2xl border border-white/60 transform scale-95 transition-transform duration-300" id="newsModalCard">
                <div class="p-6 relative max-h-[80vh] flex flex-col">
                    <div id="newsCoverWrapper" class="mb-4 rounded-2xl overflow-hidden bg-gray-100 max-h-[70vh] hidden">
                        <img id="newsCover" src="" alt="" class="w-full object-cover h-40 sm:h-44 md:h-48">
                    </div>
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 id="newsTitle" class="text-[22px] font-extrabold text-gray-900 mb-1"></h3>
                            <div class="flex items-center gap-3 text-xs text-gray-600">
                                <span id="newsCategory" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 border text-gray-700"></span>
                                <span id="newsDate" class="inline-flex items-center gap-1 text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                    <div class="h-px bg-gray-200/70 mb-4"></div>
                    <div class="overflow-y-auto pr-1" style="scrollbar-gutter: stable;">
                        <p id="newsExcerpt" class="text-gray-700 mb-4"></p>
                        <div id="newsContent" class="prose max-w-none"></div>
                    </div>
                    <div class="mt-6 flex justify-end shrink-0">
                        <button type="button" data-close-news class="px-4 py-2 rounded-full bg-primary-600 text-white hover:bg-primary-700 shadow-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            function openNewsModal(data){
                const modal = document.getElementById('newsModal');
                const card = document.getElementById('newsModalCard');
                const coverWrapper = document.getElementById('newsCoverWrapper');
                const coverImg = document.getElementById('newsCover');
                document.getElementById('newsTitle').textContent = data.title || '';
                document.getElementById('newsCategory').textContent = data.category || 'Umum';
                document.getElementById('newsDate').innerHTML = '<i class="fas fa-clock"></i> ' + (data.date || '');
                document.getElementById('newsExcerpt').textContent = data.excerpt || '';
                document.getElementById('newsContent').innerHTML = data.content || '';

                if (data.cover){
                    coverImg.src = data.cover;
                    coverImg.alt = data.title || '';
                    coverWrapper.classList.remove('hidden');
                } else {
                    coverWrapper.classList.add('hidden');
                    coverImg.src = '';
                }
                modal.classList.remove('hidden');
                setTimeout(()=>{ modal.classList.remove('opacity-0'); card.classList.remove('scale-95'); card.classList.add('scale-100'); }, 10);
                document.body.style.overflow = 'hidden';
            }
            function closeNewsModal(){
                const modal = document.getElementById('newsModal');
                const card = document.getElementById('newsModalCard');
                modal.classList.add('opacity-0');
                card.classList.remove('scale-100'); card.classList.add('scale-95');
                setTimeout(()=>{ modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
            }
            document.addEventListener('click', function(e){
                const btn = e.target.closest('.open-news, [data-open-news]');
                if (btn){
                    e.preventDefault();
                    openNewsModal({
                        title: btn.dataset.title,
                        category: btn.dataset.category,
                        date: btn.dataset.date,
                        excerpt: btn.dataset.excerpt,
                        content: btn.dataset.content,
                        cover: btn.dataset.cover
                    });
                }
                const modal = document.getElementById('newsModal');
                if (modal && modal.contains(e.target)){
                    // Only close when clicking any element with [data-close-news]
                    if (e.target.closest('[data-close-news]')) {
                        closeNewsModal();
                    }
                }
            });
            // Prevent clicks inside the card from closing modal, but allow [data-close-news]
            document.getElementById('newsModalCard').addEventListener('click', function(e){
                if (!e.target.closest('[data-close-news]')) {
                    e.stopPropagation();
                }
            }, true);
            // Ensure direct click on close button works even if propagation is stopped elsewhere
            document.querySelectorAll('#newsModal [data-close-news]').forEach(function(btn){
                btn.addEventListener('click', function(ev){ ev.preventDefault(); closeNewsModal(); });
            });
            document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeNewsModal(); });
            // Also handle explicit close button(s) via delegated listener above
        })();
    </script>
</div>
@endsection
