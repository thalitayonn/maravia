@extends('layouts.app')

@section('title', 'GaGaleri - Dokumentasi Kegiatan & Prestasi Sekolah')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background with animated gradient -->
    <div class="absolute inset-0 school-gradient opacity-90"></div>
    <div class="absolute inset-0 bg-black/20"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-white/10 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-20 w-24 h-24 bg-white/10 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 right-10 w-12 h-12 bg-white/10 rounded-full animate-float" style="animation-delay: 0.5s;"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <div class="animate-fade-in">
            <div class="mb-8">
                <div class="w-24 h-24 mx-auto mb-6 bg-white/20 rounded-full flex items-center justify-center animate-glow">
                    <i class="fas fa-camera text-4xl text-white"></i>
                </div>
                <h1 class="font-display font-bold text-5xl md:text-7xl mb-6 leading-tight">
                    <span class="block">Galeri</span>
                    <span class="block text-yellow-300">Sekolah Digital</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-100 max-w-3xl mx-auto leading-relaxed">
                    Dokumentasi perjalanan pendidikan, dari kegiatan OSIS hingga prestasi gemilang. 
                    <span class="text-yellow-300 font-semibold">Setiap momen berharga terekam di sini!</span>
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="{{ route('gallery') }}" class="group bg-white text-primary-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all duration-300 hover-lift flex items-center">
                    <i class="fas fa-images mr-3 group-hover:scale-110 transition-transform"></i>
                    Jelajahi Galeri
                </a>
                <a href="#featured" class="group border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-primary-600 transition-all duration-300 hover-lift flex items-center">
                    <i class="fas fa-star mr-3 group-hover:scale-110 transition-transform"></i>
                    Foto Unggulan
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <div class="glass-effect rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold text-yellow-300 mb-2">{{ $recentPhotos->count() }}+</div>
                    <div class="text-sm text-gray-200">Foto Terbaru</div>
                </div>
                <div class="glass-effect rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold text-yellow-300 mb-2">{{ $categories->count() }}</div>
                    <div class="text-sm text-gray-200">Kategori</div>
                </div>
                <div class="glass-effect rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold text-yellow-300 mb-2">{{ $featuredPhotos->count() }}</div>
                    <div class="text-sm text-gray-200">Foto Unggulan</div>
                </div>
                <div class="glass-effect rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold text-yellow-300 mb-2">∞</div>
                    <div class="text-sm text-gray-200">Kenangan</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#featured" class="text-white/80 hover:text-white transition-colors">
            <i class="fas fa-chevron-down text-2xl"></i>
        </a>
    </div>
</section>

<!-- Featured Photos Section -->
<section id="featured" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-display font-bold text-4xl md:text-5xl text-gray-900 mb-6">
                <i class="fas fa-star text-yellow-500 mr-4"></i>
                Foto Unggulan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Momen-momen terbaik yang menginspirasi dan membanggakan. Dari prestasi akademik hingga kegiatan ekstrakurikuler yang memukau.
            </p>
        </div>
        
        @if($featuredPhotos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredPhotos as $photo)
                    <div class="group relative overflow-hidden rounded-2xl hover-lift bg-white shadow-lg">
                        <div class="aspect-w-16 aspect-h-12 overflow-hidden">
                            <img src="{{ $photo->thumbnail_url }}" 
                                 alt="{{ $photo->title }}" 
                                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h3 class="font-semibold text-lg mb-2">{{ $photo->title }}</h3>
                                <p class="text-sm text-gray-200 mb-3">{{ Str::limit($photo->description, 80) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $photo->category->color ?? 'blue' }}-500 text-white">
                                        <i class="{{ $photo->category->icon ?? 'fas fa-folder' }} mr-1"></i>
                                        {{ $photo->category->name }}
                                    </span>
                                    <a href="{{ route('photo.show', $photo) }}" class="inline-flex items-center text-yellow-300 hover:text-yellow-200 transition-colors">
                                        <span class="mr-2">Lihat</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Featured badge -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold flex items-center">
                                <i class="fas fa-star mr-1"></i>
                                Unggulan
                            </div>
                        </div>
                        
                        <!-- View count -->
                        <div class="absolute top-4 left-4">
                            <div class="glass-effect text-white px-3 py-1 rounded-full text-xs flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $photo->view_count }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('gallery') }}" class="inline-flex items-center bg-primary-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-primary-700 transition-colors hover-lift">
                    <i class="fas fa-images mr-3"></i>
                    Lihat Semua Foto
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-camera text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Foto Unggulan</h3>
                <p class="text-gray-600 mb-8">Admin sedang menyiapkan foto-foto terbaik untuk ditampilkan di sini.</p>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-display font-bold text-4xl md:text-5xl text-gray-900 mb-6">
                <i class="fas fa-folder-open text-primary-600 mr-4"></i>
                Kategori Kegiatan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Jelajahi berbagai kegiatan sekolah yang telah terdokumentasi dengan rapi dalam kategori-kategori berikut.
            </p>
        </div>
        
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('gallery.category', $category) }}" class="group block">
                        <div class="bg-white rounded-2xl p-6 hover-lift shadow-sm border border-gray-100 group-hover:border-primary-200 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background-color: {{ $category->color }}20;">
                                    <i class="{{ $category->icon ?? 'fas fa-folder' }} text-xl" style="color: {{ $category->color }};"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 group-hover:text-primary-600 transition-colors">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $category->active_photos_count }} foto</p>
                                </div>
                            </div>
                            
                            @if($category->description)
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($category->description, 100) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center text-sm text-gray-500">
                                    <i class="fas fa-images mr-2"></i>
                                    {{ $category->active_photos_count }} foto
                                </span>
                                <i class="fas fa-arrow-right text-primary-600 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-folder text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Kategori</h3>
                <p class="text-gray-600">Admin sedang menyiapkan kategori untuk mengorganisir foto-foto sekolah.</p>
            </div>
        @endif
    </div>
</section>

<!-- Recent Photos Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-display font-bold text-4xl md:text-5xl text-gray-900 mb-6">
                <i class="fas fa-clock text-green-600 mr-4"></i>
                Foto Terbaru
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Update terkini dari berbagai kegiatan sekolah. Selalu ada yang baru untuk dilihat dan dinikmati!
            </p>
        </div>
        
        @if($recentPhotos->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($recentPhotos as $photo)
                    <a href="{{ route('photo.show', $photo) }}" class="group block">
                        <div class="aspect-square overflow-hidden rounded-xl hover-lift">
                            <img src="{{ $photo->thumbnail_url }}" 
                                 alt="{{ $photo->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="mt-3">
                            <h4 class="font-medium text-sm text-gray-900 group-hover:text-primary-600 transition-colors truncate">
                                {{ $photo->title }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $photo->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('gallery') }}" class="inline-flex items-center border-2 border-primary-600 text-primary-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-primary-600 hover:text-white transition-all duration-300 hover-lift">
                    <i class="fas fa-images mr-3"></i>
                    Jelajahi Galeri Lengkap
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Foto</h3>
                <p class="text-gray-600">Galeri sedang dipersiapkan. Segera akan ada foto-foto menarik!</p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 school-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto text-white">
            <h2 class="font-display font-bold text-4xl md:text-5xl mb-6">
                Bagikan Momen Berharga Anda!
            </h2>
            <p class="text-xl mb-8 text-gray-100">
                Punya foto kegiatan sekolah yang menarik? Bagikan testimoni atau saran untuk galeri sekolah kita!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('testimonials') }}" class="inline-flex items-center bg-white text-primary-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-colors hover-lift">
                    <i class="fas fa-comment-dots mr-3"></i>
                    Kirim Testimoni
                </a>
                <a href="{{ route('gallery') }}" class="inline-flex items-center border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-primary-600 transition-all duration-300 hover-lift">
                    <i class="fas fa-search mr-3"></i>
                    Cari Foto
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-slide-up');
            }
        });
    }, observerOptions);

    // Observe all sections
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });
</script>
@endpush
