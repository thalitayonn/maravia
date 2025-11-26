@extends('layouts.app')

@section('title', 'Galeri Foto - ' . config('app.name'))

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center text-sm">
            <a href="{{ route('home') }}" class="text-coral-500 hover:text-coral-600 font-medium">
                <i class="fas fa-home mr-2"></i>Beranda
            </a>
            <i class="fas fa-chevron-right mx-3 text-gray-400"></i>
            <span class="text-gray-700 font-medium">Galeri Foto</span>
        </div>
    </div>
</div>

<!-- Smart Search & Filter Section -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Bar with Autosuggest -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="relative">
                <form action="{{ route('gallery') }}" method="GET" id="searchForm" class="flex">
                    <div class="flex-1 relative">
                        <input type="text" 
                               name="search" 
                               id="smartSearch"
                               placeholder="Cari foto berdasarkan nama..." 
                               class="w-full px-6 py-4 pl-14 pr-20 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-lg"
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-xl"></i>
                        </div>
                        <button type="submit" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <div class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </button>
                    </div>
                </form>
                
                <!-- Autosuggest Dropdown -->
                <div id="searchSuggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-2xl shadow-xl mt-2 z-50 hidden max-h-80 overflow-y-auto">
                    <!-- Suggestions will be populated by JavaScript -->
                </div>
            </div>
        </div>
        
        <!-- Active Filters (Chips) -->
        <div id="activeFilters" class="flex flex-wrap gap-3 justify-center mb-6">
            @if(request('category'))
                <span class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">
                    <i class="fas fa-folder mr-2"></i>
                    {{ optional($categories->where('id', request('category'))->first())->name ?? 'Kategori' }}
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-2 text-primary-600 hover:text-primary-800">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            @endif
            
            @if(request('search'))
                <span class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    "{{ request('search') }}"
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 text-purple-600 hover:text-purple-800">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            @endif
        </div>
    </div>
</section>

 

<!-- Photos Grid -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($photos->count() > 0)
            <div id="photosContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <div class="photo-item group" data-photo-id="{{ $photo->id }}">
                        <div class="relative overflow-hidden rounded-2xl bg-white shadow-lg hover-lift"
                             data-like-url="{{ route('photos.favorite.toggle', $photo) }}"
                             data-detail-url="{{ route('gallery.photo', $photo) }}"
                             data-download-url="{{ route('download.photo', $photo) }}?size=original">
                            <!-- Photo Image -->
                            <div class="aspect-w-16 aspect-h-12 overflow-hidden relative">
                                <img src="{{ url('/api/photos/' . $photo->id . '/thumbnail') }}" 
                                     alt="{{ $photo->title }}" 
                                     class="w-full h-64 object-cover transition-transform duration-500"
                                     onerror="this.onerror=null; this.src='{{ url('/api/photos/' . $photo->id . '/image') }}'; console.log('Thumbnail failed, using original via API');"
                                     onload="console.log('Image loaded successfully via API:', this.src);">
                                <!-- Click-through overlay to open detail page -->
                                <a href="{{ route('gallery.photo', $photo) }}" class="absolute inset-0 z-0 cursor-pointer" aria-label="Buka detail foto"></a>
                                <!-- Floating action buttons (top-right) -->
                                <div class="absolute top-3 right-3 flex items-center gap-2 z-10 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 pointer-events-auto" onclick="event.stopPropagation();">
                                    @php $liked = auth()->check() ? (($photo->is_favorited ?? 0) > 0) : false; @endphp
                                    <button type="button" class="action-btn w-10 h-10 rounded-full border border-white/70 flex items-center justify-center {{ $liked ? 'bg-red-500 text-white' : 'bg-white/90 text-gray-800 hover:bg-white' }}" title="Suka" onclick="likeFromCard(event, this)" data-no-lightbox="true">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button type="button" class="action-btn w-10 h-10 rounded-full bg-white/90 text-gray-800 hover:bg-white border border-white/70 flex items-center justify-center" title="Komentar" onclick="openDetailFromCard(event, this)" data-no-lightbox="true">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                                <!-- Views badge top-left -->
                                <div class="absolute top-3 left-3 z-10 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold bg-black/60 text-white backdrop-blur">
                                        <i class="fas fa-eye mr-1"></i>{{ $photo->view_count ?? 0 }} views
                                    </span>
                                </div>
                                <!-- Download button bottom-right (also appears on hover) -->
                                <button type="button" class="download-btn absolute bottom-4 right-4 w-10 h-10 rounded-full bg-white/95 text-yellow-600 hover:text-yellow-700 border border-white/70 flex items-center justify-center z-20 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 pointer-events-auto" title="Download" onclick="downloadFromCard(event, this)" onmousedown="event.stopPropagation();" onmouseup="event.stopPropagation();" data-no-lightbox="true">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                            
                            <!-- Bottom Info (only on hover on md+, always visible title on small) -->
                            <div class="absolute inset-0 flex items-end opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                                <div class="absolute bottom-0 left-0 right-0 p-4 pr-28 text-white">
                                    <div class="bg-black/55 rounded-xl p-3">
                                        <h3 class="text-base font-semibold text-white mb-1 line-clamp-1">{{ $photo->title }}</h3>
                                        <div class="flex items-center justify-start">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold bg-coral-500 text-white">{{ $photo->category?->name ?? 'Umum' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $photos->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Tidak Ada Foto Ditemukan</h3>
                <p class="text-gray-600 mb-8">Coba ubah filter atau kata kunci pencarian Anda.</p>
                <a href="{{ route('gallery') }}" class="inline-flex items-center bg-primary-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-primary-700 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Reset Filter
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Lightbox removed intentionally -->
@endsection

@push('styles')
<style>
    .zone-btn {
        @apply px-6 py-3 rounded-full font-semibold text-gray-700 bg-white border-2 border-gray-200 hover:border-primary-300 hover:text-primary-600 transition-all duration-300;
    }
    
    .zone-btn.active {
        @apply bg-primary-600 text-white border-primary-600;
    }
    
    .hover-lift:hover {
        @apply transform -translate-y-1 shadow-xl;
    }
    /* Actions overlay visibility toggled by parent .show-actions */
    .show-actions .actions-overlay { opacity: 1 !important; pointer-events: auto !important; }
</style>
@endpush

@push('scripts')
<script>
    window.AuthLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    
    function forceDownload(url, name){
        const a = document.createElement('a');
        a.href = url;
        a.setAttribute('download', name || 'photo.jpg');
        document.body.appendChild(a);
        a.click();
        a.remove();
    }

    function downloadFromCard(e, btn) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        const card = btn.closest('[data-download-url]');
        const url = card?.dataset.downloadUrl;
        if (url){
            const container = btn.closest('.photo-item');
            const titleEl = container ? container.querySelector('h3') : null;
            const title = titleEl?.textContent?.trim() || 'photo';
            try {
                forceDownload(url, `${title}.jpg`);
                // Fallback navigation (in case browser blocks programmatic click)
                setTimeout(()=>{ window.location.href = url; }, 150);
            } catch(err){
                console.error('Download error', err);
                showToast('Gagal mengunduh file');
            }
        }
    }
    
    // Lightbox functions removed
    // Like handler (auth only)
    function likeFromCard(e, btn){
        if (e) { e.preventDefault(); e.stopPropagation(); }
        const card = btn.closest('[data-like-url]');
        const url = card?.dataset.likeUrl;
        if (!url) return;
        if (!window.AuthLoggedIn){ window.location.href='{{ route('login') }}'; return; }
        fetch(url, { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }})
            .then(async r=>{
                if(r.status===401){ window.location.href='{{ route('login') }}'; return; }
                let data = {};
                try { data = await r.json(); } catch(_) {}
                const active = data && typeof data.favorited !== 'undefined' ? !!data.favorited : !btn.classList.contains('bg-red-500');
                btn.classList.toggle('bg-red-500', active);
                btn.classList.toggle('text-white', active);
                showToast(active ? 'Disukai' : 'Batal suka');
            })
            .catch((err)=>{ 
                console.error(err); 
                // Fallback: create a form and submit POST normally
                try{
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    const token = document.createElement('input');
                    token.type = 'hidden'; token.name = '_token';
                    token.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(token);
                    document.body.appendChild(form);
                    form.submit();
                } catch(_e) {
                    showToast('Aksi gagal');
                }
            });
    }
    // Open detail to comments section
    function openDetailFromCard(e, btn){
        if (e) { e.preventDefault(); e.stopPropagation(); }
        const card = btn.closest('[data-detail-url]');
        const url = card?.dataset.detailUrl;
        if (url) window.location.href = url + '#comments';
    }
    // No lightbox: global listeners not needed

    // Simple toast helper
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
</script>
@endpush
