@extends('layouts.app')

@section('title', $photo->title . ' - ' . config('app.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Photo -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="w-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                    <img src="{{ route('api.photos.image', $photo) }}" alt="{{ $photo->title }}" class="max-h-[70vh] md:max-h-[75vh] w-auto object-contain mx-auto">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $photo->title }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kategori: {{ $photo->category->name ?? '-' }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center text-gray-600 dark:text-gray-300 text-sm">
                                <i class="fas fa-eye mr-2"></i>{{ $photo->view_count }}
                            </span>
                            <button id="likeBtn" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 {{ auth()->check() && $photo->favoritedBy()->where('user_id', auth()->id())->exists() ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                                <i class="fas fa-heart mr-2"></i>
                                <span id="likeText">Like</span>
                                <span class="ml-2 bg-white/20 rounded-full px-2 py-0.5 text-xs" id="likeCount">{{ $photo->favorites_count }}</span>
                            </button>
                        </div>
                    </div>
                    @if($photo->description)
                        <p class="mt-4 text-gray-700 dark:text-gray-200 leading-relaxed">{{ $photo->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Related Photos -->
            @if($relatedPhotos->count())
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Foto terkait</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($relatedPhotos as $rp)
                    <a href="{{ route('gallery.photo', $rp) }}" class="group block bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition">
                        <img src="{{ route('api.photos.thumbnail', $rp) }}" alt="{{ $rp->title }}" class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="px-3 py-2">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $rp->title }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Comments -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"><i class="fas fa-comments mr-2"></i>Komentar</h3>

                @auth
                <form id="commentForm" method="POST" action="{{ route('gallery.photo.comment.store', $photo) }}" class="space-y-3">
                    @csrf
                    <textarea name="comment" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis komentar..."></textarea>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-xl font-semibold transition">Kirim</button>
                </form>
                @else
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-200 rounded-xl text-sm mb-4">
                    Silakan <a href="{{ route('login') }}" class="font-semibold underline">login</a> untuk memberi like dan komentar.
                </div>
                @endauth

                <div id="commentsList" class="mt-4 space-y-4"></div>
                <div id="commentsEmpty" class="mt-4 text-sm text-gray-500 dark:text-gray-400 hidden">Belum ada komentar.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const photoId = {{ $photo->id }};
    const likeBtn = document.getElementById('likeBtn');
    const likeCountEl = document.getElementById('likeCount');
    const likeText = document.getElementById('likeText');

    if (likeBtn) {
        likeBtn.addEventListener('click', async function(){
            @if(!auth()->check())
                window.location.href = '{{ route('login') }}';
                return;
            @endif
            try {
                const res = await fetch('{{ route('photos.favorite.toggle', $photo) }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (res.redirected || res.status === 401) {
                    window.location.href = res.url || '{{ route('login') }}';
                    return;
                }
                if (res.ok) {
                    // Try to read JSON, fallback to toggle UI
                    let data = {};
                    try { data = await res.json(); } catch(e) {}
                    // Update count if provided, else toggle +/- 1
                    if (data && typeof data.favorites_count !== 'undefined') {
                        likeCountEl.textContent = data.favorites_count;
                    } else {
                        const cur = parseInt(likeCountEl.textContent || '0', 10);
                        if (likeBtn.classList.contains('bg-red-500')) {
                            likeCountEl.textContent = Math.max(0, cur - 1);
                        } else {
                            likeCountEl.textContent = cur + 1;
                        }
                    }
                    likeBtn.classList.toggle('bg-red-500');
                    likeBtn.classList.toggle('text-white');
                    showToast('Aksi suka berhasil');
                } else if (res.status === 401) {
                    window.location.href = '{{ route('login') }}';
                }
            } catch (err) {
                console.error(err);
                showToast('Gagal memproses like');
            }
        });
    }

    // Comments
    const listEl = document.getElementById('commentsList');
    const emptyEl = document.getElementById('commentsEmpty');
    async function loadComments(page = 1){
        try {
            const url = new URL('{{ route('gallery.photo.comments', $photo) }}', window.location.origin);
            url.searchParams.set('page', page);
            const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            listEl.innerHTML = '';
            if (data.success && data.comments && data.comments.length) {
                emptyEl.classList.add('hidden');
                data.comments.forEach(c => {
                    const item = document.createElement('div');
                    item.className = 'rounded-xl border border-gray-100 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-700/40';
                    item.innerHTML = `
                        <div class="flex items-start space-x-3">
                            <div class="w-9 h-9 rounded-full bg-primary-600 text-white flex items-center justify-center">${(c.author_name||'?').substring(0,1).toUpperCase()}</div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="font-semibold text-gray-800 dark:text-gray-100">${c.author_name||'Anonim'}</div>
                                    <div class="text-xs text-gray-400">${c.created_at}</div>
                                </div>
                                <div class="mt-1 text-gray-700 dark:text-gray-200">${escapeHtml(c.comment)}</div>
                            </div>
                        </div>
                    `;
                    listEl.appendChild(item);
                });
            } else {
                emptyEl.classList.remove('hidden');
            }
        } catch (e) { console.error(e); }
    }

    function escapeHtml(str){
        return (str||'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;');
    }

    loadComments();

    const form = document.getElementById('commentForm');
    if (form) {
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            const fd = new FormData(form);
            const res = await fetch('{{ route('gallery.photo.comment.store', $photo) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' },
                body: fd
            });
            if (res.redirected || res.status === 401) { window.location.href = res.url || '{{ route('login') }}'; return; }
            if (res.ok) { form.reset(); showToast('Komentar terkirim'); loadComments(); }
            else { showToast('Komentar gagal dikirim'); }
        });
    }

    function showToast(message){
        let box = document.getElementById('toast-box');
        if(!box){
            box = document.createElement('div');
            box.id = 'toast-box';
            box.style.position = 'fixed';
            box.style.right = '16px';
            box.style.bottom = '16px';
            box.style.zIndex = '9999';
            document.body.appendChild(box);
        }
        const el = document.createElement('div');
        el.className = 'mb-2 px-4 py-2 rounded-xl text-white bg-primary-600 shadow-lg';
        el.textContent = message;
        box.appendChild(el);
        setTimeout(()=>{ el.remove(); if(!box.childElementCount) box.remove(); }, 1800);
    }
})();
</script>
@endpush
