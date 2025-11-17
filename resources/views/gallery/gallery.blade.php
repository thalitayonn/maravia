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
                               placeholder="Cari foto berdasarkan nama acara, tahun, atau kategori..." 
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
                    {{ $categories->where('id', request('category'))->first()->name ?? 'Kategori' }}
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

<!-- ZONES Navigation -->
<section class="py-6 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('gallery') }}" 
               class="zone-btn {{ !request()->hasAny(['category', 'zone']) ? 'active' : '' }}">
                <i class="fas fa-th-large mr-2"></i>
                Semua ZONA
            </a>
            
            <a href="{{ route('gallery', ['zone' => 'ekskul']) }}" 
               class="zone-btn {{ request('zone') == 'ekskul' ? 'active' : '' }}">
                <i class="fas fa-star mr-2"></i>
                ZONA EKSKUL
            </a>
            
            <a href="{{ route('gallery', ['zone' => 'prestasi']) }}" 
               class="zone-btn {{ request('zone') == 'prestasi' ? 'active' : '' }}">
                <i class="fas fa-trophy mr-2"></i>
                ZONA PRESTASI
            </a>
            
            <a href="{{ route('gallery', ['zone' => 'class-moment']) }}" 
               class="zone-btn {{ request('zone') == 'class-moment' ? 'active' : '' }}">
                <i class="fas fa-users mr-2"></i>
                ZONA CLASS MOMENT
            </a>
            
            <a href="{{ route('gallery', ['zone' => 'acara-besar']) }}" 
               class="zone-btn {{ request('zone') == 'acara-besar' ? 'active' : '' }}">
                <i class="fas fa-calendar-alt mr-2"></i>
                ZONA ACARA BESAR
            </a>
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
                        <div class="relative overflow-hidden rounded-2xl bg-white shadow-lg hover-lift">
                            <!-- Photo Image -->
                            <div class="aspect-w-16 aspect-h-12 overflow-hidden">
                                <img src="{{ url('/api/photos/' . $photo->id . '/thumbnail') }}" 
                                     alt="{{ $photo->title }}" 
                                     class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer"
                                     onclick="openLightbox({{ $photo->id }})"
                                     onerror="this.onerror=null; this.src='{{ url('/api/photos/' . $photo->id . '/image') }}'; console.log('Thumbnail failed, using original via API');"
                                     onload="console.log('Image loaded successfully via API:', this.src);">
                            </div>
                            
                            <!-- Overlay Info -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                    <h3 class="font-semibold text-lg mb-2">{{ $photo->title }}</h3>
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-500 text-white">
                                            {{ $photo->category->name }}
                                        </span>
                                        <button onclick="downloadPhoto({{ $photo->id }})" 
                                                class="text-yellow-300 hover:text-yellow-200 transition-colors"
                                                title="Download dengan watermark">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Photo Info -->
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 mb-2 truncate">{{ $photo->title }}</h4>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $photo->created_at->format('d M Y') }}</span>
                                    <span>{{ $photo->view_count }} views</span>
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

<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 bg-black/95 z-50 hidden">
    <div class="relative w-full h-full flex items-center justify-center">
        <!-- Close Button -->
        <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white text-2xl hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Navigation Arrows -->
        <button onclick="previousPhoto()" class="absolute left-6 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="nextPhoto()" class="absolute right-6 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Photo Container -->
        <div class="max-w-6xl max-h-full mx-auto p-6">
            <img id="lightboxImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain mx-auto">
            
            <!-- Photo Info -->
            <div class="mt-6 text-center text-white">
                <h3 id="lightboxTitle" class="text-2xl font-bold mb-2"></h3>
                <div class="flex justify-center gap-4">
                    <button onclick="toggleSlideshow()" id="slideshowBtn" class="text-yellow-300 hover:text-yellow-200">
                        <i class="fas fa-play mr-2"></i>Slideshow
                    </button>
                    <button onclick="downloadCurrentPhoto()" class="text-green-300 hover:text-green-200">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
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
</style>
@endpush

@push('scripts')
<script>
    let currentPhotoIndex = 0;
    let photosData = @json($photos->items());
    let slideshowInterval = null;
    
    function openLightbox(photoId) {
        const photoIndex = photosData.findIndex(photo => photo.id === photoId);
        if (photoIndex !== -1) {
            currentPhotoIndex = photoIndex;
            showPhoto(currentPhotoIndex);
            document.getElementById('lightboxModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        if (slideshowInterval) clearInterval(slideshowInterval);
    }
    
    function showPhoto(index) {
        if (index < 0 || index >= photosData.length) return;
        const photo = photosData[index];
        document.getElementById('lightboxImage').src = photo.url;
        document.getElementById('lightboxTitle').textContent = photo.title;
    }
    
    function previousPhoto() {
        currentPhotoIndex = (currentPhotoIndex - 1 + photosData.length) % photosData.length;
        showPhoto(currentPhotoIndex);
    }
    
    function nextPhoto() {
        currentPhotoIndex = (currentPhotoIndex + 1) % photosData.length;
        showPhoto(currentPhotoIndex);
    }
    
    function toggleSlideshow() {
        if (slideshowInterval) {
            clearInterval(slideshowInterval);
            slideshowInterval = null;
            document.getElementById('slideshowBtn').innerHTML = '<i class="fas fa-play mr-2"></i>Slideshow';
        } else {
            slideshowInterval = setInterval(() => {
                nextPhoto();
            }, 3000);
            document.getElementById('slideshowBtn').innerHTML = '<i class="fas fa-pause mr-2"></i>Stop';
        }
    }
    
    function downloadPhoto(photoId) {
        window.open(`/photo/${photoId}/download`, '_blank');
    }
    
    function downloadCurrentPhoto() {
        if (photosData[currentPhotoIndex]) {
            downloadPhoto(photosData[currentPhotoIndex].id);
        }
    }
    
    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightboxModal').classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape': closeLightbox(); break;
                case 'ArrowLeft': previousPhoto(); break;
                case 'ArrowRight': nextPhoto(); break;
                case ' ': e.preventDefault(); toggleSlideshow(); break;
            }
        }
    });
</script>
@endpush
