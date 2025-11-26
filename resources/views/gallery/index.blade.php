@extends('layouts.app')

@section('title', 'Maravia - Galeri Foto & Momen Berharga')

@section('content')
<div class="min-h-screen relative overflow-hidden">
    <!-- Animated Background Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gradient-to-r from-coral-500/20 to-pink-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gradient-to-r from-sky-500/20 to-purple-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-gradient-to-r from-lemon-500/10 to-orange-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="relative py-20 lg:py-32 overflow-hidden z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8 animate-fade-in-up">
                    
                    <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 leading-tight">
                        <span class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent">
                        Dari Sekolah
                        </span>
                        <span class="block bg-gradient-to-r from-coral-500 via-pink-500 to-purple-500 bg-clip-text text-transparent animate-gradient">
                            untuk Semua
                        </span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 leading-relaxed max-w-xl">
                        Setiap momen, setiap senyum, setiap kenangan terekam dengan indah di sini! 📸
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('gallery') }}" 
                           class="group inline-flex items-center justify-center px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-semibold text-lg transition-all duration-300 hover-lift shadow-2xl border border-white/20 backdrop-blur-sm">
                            <i class="fas fa-images mr-3 group-hover:scale-110 transition-transform"></i>
                            Jelajahi Galeri
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#categories" 
                           class="group glass-card inline-flex items-center justify-center px-8 py-4 bg-white/80 hover:bg-white/90 backdrop-blur-xl text-gray-900 rounded-2xl font-semibold text-lg transition-all duration-300 hover-lift shadow-xl border border-gray-200/50 smooth-scroll">
                            <i class="fas fa-th-large mr-3 group-hover:rotate-12 transition-transform"></i>
                            Lihat Kategori
                        </a>
                    </div>
                    
                    <!-- Stats dengan Glassmorphism -->
                    <div class="grid grid-cols-3 gap-4 pt-8">
                        <div class="glass-card bg-white/60 backdrop-blur-xl rounded-2xl p-6 text-center border border-white/30 shadow-xl hover:scale-105 transition-all duration-300 group">
                            <div class="text-4xl font-bold bg-gradient-to-r from-coral-500 to-pink-500 bg-clip-text text-transparent group-hover:scale-110 transition-transform">{{ $stats['total_photos'] ?? '0' }}+</div>
                            <div class="text-sm text-gray-600 mt-2 font-medium">Foto</div>
                        </div>
                        <div class="glass-card bg-white/60 backdrop-blur-xl rounded-2xl p-6 text-center border border-white/30 shadow-xl hover:scale-105 transition-all duration-300 group">
                            <div class="text-4xl font-bold bg-gradient-to-r from-sky-500 to-blue-500 bg-clip-text text-transparent group-hover:scale-110 transition-transform">{{ $stats['categories'] ?? '0' }}+</div>
                            <div class="text-sm text-gray-600 mt-2 font-medium">Kategori</div>
                        </div>
                        <div class="glass-card bg-white/60 backdrop-blur-xl rounded-2xl p-6 text-center border border-white/30 shadow-xl hover:scale-105 transition-all duration-300 group">
                            <div id="totalViewsNumber" class="text-4xl font-bold bg-gradient-to-r from-lemon-500 to-orange-500 bg-clip-text text-transparent group-hover:scale-110 transition-transform">{{ $stats['total_views'] ?? '0' }}+</div>
                            <div class="text-sm text-gray-600 mt-2 font-medium">Views</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Image Slider - Futuristic 3D Carousel -->
                <div class="relative">
                    <!-- Slider Container dengan Glassmorphism -->
                    <div class="relative rounded-3xl" style="height: 520px; overflow: hidden;">
                        @if($recentPhotos->count() > 0)
                            <!-- Slider Foto dengan Swiper - Futuristic Style -->
                            <div class="swiper hero-swiper h-full rounded-3xl overflow-hidden shadow-2xl border border-white/20 backdrop-blur-sm">
                                <div class="swiper-wrapper">
                                    @foreach($recentPhotos as $photo)
                                        <div class="swiper-slide">
                                            <div class="relative h-full">
                                                <img src="{{ url('/api/photos/' . $photo->id . '/image') }}" 
                                                     alt="{{ $photo->title }}"
                                                     class="w-full h-full object-cover rounded-3xl">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        @else
                            <div class="h-full relative flex items-center justify-center" style="perspective: 2000px; overflow: visible; padding: 20px;">
                                @if($recentPhotos->count() > 0)
                                    <div class="relative flex items-center justify-center pointer-events-none select-none" style="width: 80%; height: 85%; max-width: 600px; max-height: 500px; overflow: visible;">
                                        @foreach($recentPhotos->take(7) as $index => $photo)
                                            @php
                                                $total = min($recentPhotos->count(), 7);
                                                $position = $index;
                                                $centerIndex = floor($total / 2);
                                                $offset = $position - $centerIndex;
                                                $baseScale = 1 - (abs($offset) * 0.15);
                                                $translateX = $offset * 80;
                                                $translateY = abs($offset) * 10;
                                                $rotateY = $offset * 25;
                                                $opacity = 1 - (abs($offset) * 0.15);
                                                $zIndex = $total - abs($offset);
                                                $side = $offset < 0 ? 'left' : ($offset > 0 ? 'right' : 'center');
                                            @endphp
                                            <div class="carousel-photo absolute rounded-3xl overflow-hidden shadow-2xl border border-white/30 transition-all duration-700 hover:scale-110 hover:z-50 cursor-default group glass-card"
                                                 data-position="{{ $position }}"
                                                 data-offset="{{ $offset }}"
                                                 data-side="{{ $side }}"
                                                 data-scale="{{ $baseScale }}"
                                                 data-translate-y="{{ $translateY }}"
                                                 data-translate-x="{{ $translateX }}"
                                                 data-rotate-y="{{ $rotateY }}"
                                                 style="width: 100%; height: 100%; transform: translateY(-{{ $translateY }}px) translateX({{ $translateX }}px) scale({{ $baseScale }}) rotateY({{ $rotateY }}deg); opacity: {{ $opacity }}; z-index: {{ $zIndex }}; transform-origin: center center;"
                                                 data-photo-id="{{ $photo->id }}" data-photo-url="{{ url('/api/photos/' . $photo->id . '/image') }}" data-photo-title="{{ addslashes($photo->title) }}" data-photo-category="{{ $photo->category?->name ?? 'Umum' }}" data-photo-views="{{ $photo->view_count ?? 0 }}" data-photo-date="{{ $photo->created_at->format('d M Y') }}" data-photo-description="{{ addslashes($photo->description ?? 'Tidak ada deskripsi') }}" data-photo-href="{{ route('gallery.photo', $photo) }}"
                                                 >
                                                <img src="{{ url('/api/photos/' . $photo->id . '/image') }}" alt="{{ $photo->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 rounded-3xl">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl">
                                                    <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                                        <div class="glass-card bg-white/15 backdrop-blur-xl rounded-2xl p-3 border border-white/20 mx-4 mb-4">
                                                            <h3 class="text-lg font-bold text-white mb-2 drop-shadow-lg line-clamp-1">{{ $photo->title }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 rounded-3xl pointer-events-none"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center">
                                            <div class="text-6xl mb-4">📸</div>
                                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Galeri Sedang Disiapkan</h3>
                                            <p class="text-gray-600">Foto-foto menarik akan segera hadir!</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Photos Section dengan Glassmorphism -->
    @if($featuredPhotos->count() > 0)
    <section id="featured" class="py-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent mb-4">
                    Foto Unggulan
                </h2>
                <p class="text-xl text-gray-600">Momen-momen terbaik yang patut kamu lihat!</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredPhotos as $photo)
                <a href="{{ route('gallery.photo', $photo) }}" 
                   class="group block rounded-3xl overflow-hidden transition-all duration-500 cursor-pointer">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-t-3xl">
                        <img src="{{ url('/api/photos/' . $photo->id . '/image') }}" 
                             alt="{{ $photo->title }}"
                             class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        
                        
                        <!-- Stats dengan Glassmorphism -->
                        <div class="absolute bottom-4 left-4 right-4 text-white opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0">
                            <div class="glass-card bg-white/15 backdrop-blur-xl rounded-2xl p-4 border border-white/20 flex items-center justify-between text-sm">
                                <span class="flex items-center"><i class="fas fa-eye mr-2 text-blue-300"></i>{{ $photo->view_count }}</span>
                                <span class="flex items-center"><i class="fas fa-heart mr-2 text-red-300"></i>{{ $photo->favorites_count }}</span>
                            </div>
                        </div>
                        
                        <!-- Shine effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="#recent" 
                   class="inline-flex items-center px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-semibold transition-all duration-300 hover-lift smooth-scroll border border-white/20">
                    Lihat Foto Terbaru
                    <i class="fas fa-arrow-down ml-3"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Top Categories Section dengan Futuristic Design -->
    @if($categories->count() > 0)
    <section id="categories" class="py-20 scroll-mt-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent mb-4">
                    Kategori Populer
                </h2>
                <p class="text-xl text-gray-600">Jelajahi foto berdasarkan kategori favoritmu</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                @php
                    // Get first photo from category as background
                    $categoryPhoto = $category->activePhotos()->first();
                    
                    // Use category color if available, otherwise use default gradients
                    if ($category->color) {
                        // Category has custom color from admin
                        $categoryColor = $category->color;
                    } else {
                        // Fallback to default colors based on index
                        $defaultColors = ['#F62731', '#3b82f6', '#eab308', '#a855f7'];
                        $categoryColor = $defaultColors[$loop->index % 4];
                    }

        // moved: JS initialization placed below in <script> block
                @endphp
                <a href="{{ route('gallery.category', $category->slug) }}" 
                   class="group relative block rounded-3xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover-lift cursor-pointer h-72 border border-white/20 backdrop-blur-sm">
                    
                    <!-- Background Image -->
                    @if($categoryPhoto)
                        <img src="{{ url('/api/photos/' . $categoryPhoto->id . '/image') }}" 
                             alt="{{ $category->name }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                    @endif
                    
                    <!-- Gradient Overlay dengan Glassmorphism -->
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-70 group-hover:opacity-80" 
                         style="background: linear-gradient(135deg, {{ $categoryColor }}CC 0%, {{ $categoryColor }}AA 50%, {{ $categoryColor }}CC 100%);"></div>
                    
                    <!-- Dark Overlay for better text readability -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>
                    
                    <!-- Animated border glow -->
                    <div class="absolute inset-0 border-2 border-white/0 group-hover:border-white/30 rounded-3xl transition-all duration-500"></div>
                    
                    <!-- Content -->
                    <div class="relative h-full flex flex-col justify-between p-6 text-white">
                        <!-- Icon dengan Glassmorphism -->
                        <div class="flex justify-start">
                            <div class="glass-card w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 shadow-xl border border-white/30">
                                <i class="fas fa-{{ $category->icon ?? 'folder' }} text-2xl text-white drop-shadow-lg"></i>
                            </div>
                        </div>
                        
                        <!-- Title & Count -->
                        <div>
                            <h3 class="font-bold text-2xl text-white mb-3 drop-shadow-lg group-hover:translate-y-[-4px] transition-transform duration-300">{{ $category->name }}</h3>
                            <div class="flex items-center justify-between">
                                <div class="glass-card inline-flex items-center px-4 py-2 bg-white/25 backdrop-blur-xl rounded-full shadow-lg border border-white/30">
                                    <i class="fas fa-images text-white/90 text-xs mr-2"></i>
                                    <span class="text-sm font-semibold">{{ $category->active_photos_count ?? $category->photos_count ?? 0 }} foto</span>
                                </div>
                                <!-- Arrow dengan animasi -->
                                <div class="glass-card w-12 h-12 bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 shadow-xl border border-white/30">
                                    <i class="fas fa-arrow-right text-white text-sm group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shine effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 rounded-3xl"></div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recent Photos Section dengan Futuristic Grid -->
    @if($recentPhotos->count() > 0)
    <section id="recent" class="py-20 scroll-mt-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent mb-4">
                    Foto Terbaru
                </h2>
                <p class="text-xl text-gray-600">Update terbaru dari galeri kami</p>
            </div>
            
            <!-- Single-row slider: centered, 3 cards on desktop, arrows -->
            <div class="relative">
                <div class="recent-swiper swiper px-2 max-w-6xl mx-auto">
                    <div class="swiper-wrapper">
                        @foreach($recentPhotos as $photo)
                        <div class="swiper-slide">
                            <div class="group relative">
                                <a href="javascript:void(0)"
                                   data-photo-id="{{ $photo->id }}" data-photo-url="{{ url('/api/photos/' . $photo->id . '/image') }}" data-photo-title="{{ addslashes($photo->title) }}" data-photo-category="{{ $photo->category?->name ?? 'Umum' }}" data-photo-views="{{ $photo->view_count ?? 0 }}" data-photo-date="{{ $photo->created_at->format('d M Y') }}" data-photo-description="{{ addslashes($photo->description ?? 'Tidak ada deskripsi') }}"
                                   class="recent-card block cursor-default">
                                    <div class="media relative aspect-square rounded-2xl overflow-hidden">
                                        <img src="{{ url('/api/photos/' . $photo->id . '/thumbnail') }}" alt="{{ $photo->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ url('/api/photos/' . $photo->id . '/image') }}'">
                                        <!-- top pills -->
                                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between">
                                            <span class="pill-brand text-[11px] font-semibold pointer-events-none">{{ $photo->category?->name ?? 'Umum' }}</span>
                                        </div>
                                        <!-- bottom info -->
                                        <div class="absolute bottom-3 left-3 right-3 flex items-end justify-between pointer-events-none">
                                            <div class="flex items-center gap-2">
                                                <span class="pill-brand-strong text-[11px] font-bold">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>
                                                <span class="pill-brand text-[11px] font-semibold"><i class="fas fa-heart mr-1"></i>{{ $photo->favorites_count ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-white font-bold text-sm md:text-base drop-shadow-lg max-w-[56%] line-clamp-1">{{ $photo->title }}</h3>
                                                <span class="pill-brand text-[10px] font-semibold whitespace-nowrap"><i class="fas fa-clock mr-1"></i>{{ $photo->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- CTA: All Photos -->
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow border border-white/20 transition">
                        Lihat Semua Foto <i class="fas fa-images"></i>
                    </a>
                </div>
                <!-- arrows -->
                <button class="recent-prev glass-card hidden md:flex items-center justify-center absolute -left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 border border-white/60 shadow"><i class="fas fa-chevron-left text-gray-800"></i></button>
                <button class="recent-next glass-card hidden md:flex items-center justify-center absolute -right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 border border-white/60 shadow"><i class="fas fa-chevron-right text-gray-800"></i></button>
            </div>
        </div>
    </section>
    @endif

    {{-- Berita Terbaru --}}
    @if(isset($recentArticles) && $recentArticles->count() > 0)
    <section id="news" class="py-20 scroll-mt-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex items-end justify-between gap-4 flex-wrap">
                <div class="text-center md:text-left">
                    <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent mb-2">Berita</h2>
                    <p class="text-xl text-gray-600">Kabar terbaru dan informasi penting</p>
                </div>
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow border border-white/20 transition">
                    Lihat Semua Berita <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($recentArticles->take(6) as $article)
                <article class="group rounded-2xl overflow-hidden border border-white/20 bg-white/80 backdrop-blur-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="block p-5">
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 border text-gray-700"><i class="fas fa-folder"></i> {{ $article->category?->name ?? 'Umum' }}</span>
                            <span class="inline-flex items-center gap-1 text-gray-500"><i class="fas fa-clock"></i> {{ $article->created_at?->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $article->title }}</h3>
                        @if($article->excerpt)
                            <p class="text-gray-700 line-clamp-3 mb-3">{{ $article->excerpt }}</p>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Ringkasan</span>
                            <button type="button" class="inline-flex items-center gap-2 text-coral-600 font-semibold hover:underline open-news"
                                    data-title="{{ addslashes($article->title) }}"
                                    data-category="{{ $article->category?->name ?? 'Umum' }}"
                                    data-date="{{ $article->created_at?->diffForHumans() }}"
                                    data-excerpt="{{ addslashes($article->excerpt ?? '') }}"
                                    data-content="{{ addslashes($article->content ?? '') }}">
                                Baca selengkapnya <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif



    <!-- CTA Section dengan Futuristic Design -->
    <section class="py-20 relative overflow-hidden z-10">
        <!-- Animated background -->
        <div class="absolute inset-0 bg-gradient-to-br from-coral-500/10 via-pink-500/10 to-purple-500/10"></div>
        <div class="absolute top-10 left-10 w-64 h-64 bg-pink-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-orange-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="glass-card bg-white/60 backdrop-blur-xl rounded-3xl p-12 border border-white/30 shadow-2xl">
                <h2 class="text-5xl font-bold mb-6 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent">
                    Siap Menjelajahi Lebih Banyak?
                </h2>
            <p class="text-xl mb-8 text-gray-700">
                Ribuan foto menanti untuk kamu eksplorasi. Temukan momen-momen berharga!
            </p>
            <a href="#home" 
                   class="group inline-flex items-center px-10 py-5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-lg transition-all duration-300 hover-lift shadow-2xl border border-white/20 smooth-scroll">
                    <i class="fas fa-arrow-up mr-3 group-hover:-translate-y-1 transition-transform"></i>
                Kembali ke Atas
            </a>
            </div>
        </div>
    </section>
</div>

<!-- Photo Modal Lightbox dengan Futuristic Design -->
<div id="photoModal" class="fixed inset-0 bg-white/10 backdrop-blur-xl z-50 z-[1000] hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <!-- Close Button dengan Glassmorphism -->
        <button onclick="closePhotoModal()" class="glass-card absolute top-6 right-6 w-14 h-14 bg-white/10 hover:bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center transition-all duration-300 z-10 group border border-white/20 shadow-xl hover:scale-110">
            <i class="fas fa-times text-white text-xl group-hover:rotate-90 transition-transform duration-300"></i>
        </button>

        <!-- Prev/Next / Play Controls -->
        <div class="absolute inset-x-0 top-6 flex items-center justify-center gap-3 z-10">
            <button id="modalPrev" class="glass-card px-4 py-2 bg-white/10 hover:bg-white/20 rounded-full text-white border border-white/20"><i class="fas fa-chevron-left"></i></button>
            <button id="modalPlayPause" class="glass-card px-4 py-2 bg-white/10 hover:bg-white/20 rounded-full text-white border border-white/20"><i class="fas fa-play"></i></button>
            <button id="modalNext" class="glass-card px-4 py-2 bg-white/10 hover:bg-white/20 rounded-full text-white border border-white/20"><i class="fas fa-chevron-right"></i></button>
        </div>
        
        <!-- Modal Content dengan Glassmorphism -->
        <div class="max-w-6xl w-full grid md:grid-cols-2 gap-6 transform scale-95 transition-transform duration-500" id="modalContent">
            <!-- Photo Container -->
            <div class="relative rounded-3xl overflow-hidden modal-pane">
                <img id="modalPhoto" src="" alt="" class="w-full h-full object-contain bg-black/40">
                <div class="absolute inset-x-12 -bottom-2 h-1 rounded-full bg-white/20 overflow-hidden">
                    <div id="slideshowProgress" class="h-full bg-gradient-to-r from-coral-500 to-pink-500 w-0"></div>
                </div>
            </div>
            
            <!-- Info Container dengan Glassmorphism -->
            <div class="rounded-3xl p-8 text-gray-900 flex flex-col justify-between bg-transparent border-transparent shadow-none modal-pane">
                <div class="flex items-center justify-between mb-6">
                    <div class="inline-flex bg-white/60 backdrop-blur-xl rounded-full p-1 border border-white/40">
                        <button id="tabInfo" class="px-4 py-2 text-sm font-semibold rounded-full bg-white shadow-sm">Info</button>
                        <button id="tabDownload" class="px-4 py-2 text-sm font-semibold rounded-full text-gray-600">Download</button>
                        <button id="tabComments" class="px-4 py-2 text-sm font-semibold rounded-full text-gray-600">Komentar</button>
                    </div>
                </div>
                <!-- Header -->
                <div>
                    <div id="panelInfo">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex-1">
                                <h2 id="modalTitle" class="text-3xl font-bold mb-2"></h2>
                                <div class="flex items-center space-x-3 text-gray-700 text-sm flex-wrap gap-2">
                                    <span class="inline-flex items-center bg-white/70 backdrop-blur-md px-3 py-1 rounded-full border border-white/60"><i class="fas fa-calendar mr-2 text-coral-500"></i><span id="modalDate"></span></span>
                                    <span class="inline-flex items-center bg-white/70 backdrop-blur-md px-3 py-1 rounded-full border border-white/60"><i class="fas fa-eye mr-2 text-blue-500"></i><span id="modalViews"></span> views</span>
                                </div>
                            </div>
                        </div>
                        <div class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-coral-500 to-pink-500 text-white rounded-full mb-6 shadow">
                            <i class="fas fa-folder mr-2"></i>
                            <span id="modalCategory" class="font-semibold"></span>
                        </div>
                        <div class="mb-2">
                            <div class="rounded-2xl p-4 border border-white/60 bg-white/70"><p id="modalDescription" class="text-gray-800 leading-relaxed"></p></div>
                        </div>
                    </div>

                    <div id="panelDownload" class="hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Download</h3>
                            <div class="flex items-center gap-3">
                                <select id="downloadSize" class="bg-white/70 border border-white/60 rounded-xl px-3 py-2">
                                    <option value="original">Original</option>
                                    <option value="large">Large (1920w)</option>
                                    <option value="medium">Medium (1200w)</option>
                                    <option value="small">Small (800w)</option>
                                </select>
                                <label class="inline-flex items-center gap-2"><input id="downloadWatermark" type="checkbox" checked> <span>Watermark</span></label>
                                <button id="downloadBtn" class="px-4 py-2 bg-gradient-to-r from-coral-500 to-pink-500 text-white rounded-xl shadow"><i class="fas fa-download mr-2"></i>Download</button>
                            </div>
                        </div>
                    </div>

                    <div id="panelComments" class="hidden">
                        <div id="commentsList" class="space-y-3 mb-4"></div>
                        <form id="commentForm" class="space-y-3">
                            <input type="hidden" id="commentPhotoId" value="">
                            <div class="grid grid-cols-2 gap-3">
                                <input id="commentName" type="text" placeholder="Nama" class="bg-white/70 border border-white/60 rounded-xl px-3 py-2 w-full">
                                <input id="commentEmail" type="email" placeholder="Email" class="bg-white/70 border border-white/60 rounded-xl px-3 py-2 w-full">
                            </div>
                            <textarea id="commentText" rows="3" placeholder="Tulis komentar..." class="bg-white/70 border border-white/60 rounded-xl px-3 py-2 w-full"></textarea>
                            <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-xl border border-gray-800">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- News Modal (text-only) -->
<div id="newsModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[1000] hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="max-w-3xl w-full bg-white rounded-[16px] overflow-hidden shadow-2xl border border-gray-200 transform scale-95 transition-transform duration-300" id="newsModalCard">
            <div class="p-6 relative max-h-[80vh] flex flex-col">
                <button type="button" data-close-news class="absolute top-3 right-3 w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center shadow">
                    <i class="fas fa-times"></i>
                </button>
                <div class="flex items-start justify-between mb-3 pr-10">
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
                <div class="mt-4 flex justify-end shrink-0">
                    <button type="button" data-close-news class="px-4 py-2 rounded-full bg-primary-600 text-white hover:bg-primary-700 shadow-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function(){
            function openNewsModal(data){
                const modal = document.getElementById('newsModal');
                const card = document.getElementById('newsModalCard');
                document.getElementById('newsTitle').textContent = data.title || '';
                document.getElementById('newsCategory').textContent = data.category || 'Umum';
                document.getElementById('newsDate').innerHTML = '<i class="fas fa-clock"></i> ' + (data.date || '');
                document.getElementById('newsExcerpt').textContent = data.excerpt || '';
                document.getElementById('newsContent').innerHTML = data.content || '';
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
                const btn = e.target.closest('.open-news');
                if (btn){
                    e.preventDefault();
                    openNewsModal({
                        title: btn.dataset.title,
                        category: btn.dataset.category,
                        date: btn.dataset.date,
                        excerpt: btn.dataset.excerpt,
                        content: btn.dataset.content
                    });
                    return;
                }
                const modal = document.getElementById('newsModal');
                const card = document.getElementById('newsModalCard');
                if (!modal.classList.contains('hidden')){
                    // Close if click on backdrop or any [data-close-news]
                    if (e.target === modal || (!card.contains(e.target)) || e.target.closest('[data-close-news]')){
                        closeNewsModal();
                    }
                }
            });
            document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeNewsModal(); });
            // Ensure direct click on close button works
            document.querySelectorAll('#newsModal [data-close-news]').forEach(function(btn){
                btn.addEventListener('click', function(ev){ ev.preventDefault(); closeNewsModal(); });
            });
        })();
    </script>
</div>

@push('styles')
<style>
    /* Glassmorphism Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Color Utilities */
    .text-coral-500 { color: #FF6F61; }
    .bg-coral-500 { background-color: #FF6F61; }
    .bg-coral-600 { background-color: #e55a4d; }
    .border-coral-500 { border-color: #FF6F61; }
    .from-coral-500 { --tw-gradient-from: #FF6F61; }
    .to-coral-500 { --tw-gradient-to: #FF6F61; }
    .from-coral-100 { --tw-gradient-from: #ffebe9; }
    
    .text-sky-500 { color: #4A90E2; }
    .bg-sky-500 { background-color: #4A90E2; }
    .to-sky-500 { --tw-gradient-to: #4A90E2; }
    .from-sky-100 { --tw-gradient-from: #e8f4ff; }
    .bg-sky-300 { background-color: #6ba5e8; }
    
    .text-lemon-500 { color: #FFD93D; }
    .bg-lemon-400 { background-color: #FFD93D; }
    .bg-lemon-300 { background-color: #ffe566; }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Futuristic Animations */
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes gradient-shift {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out;
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
    }
    
    .animate-spin-slow {
        animation: spin-slow 3s linear infinite;
    }
    
    /* Shadow Utilities */
    .shadow-coral-500\/50 {
        box-shadow: 0 20px 40px rgba(255, 111, 97, 0.5);
    }
    
    /* Stacked Photos Rotation Animation - Menggunakan transform pada elemen */
    .stacked-rotate {
        animation: stacked-rotate-y 5s ease-in-out infinite;
    }
    
    @keyframes stacked-rotate-y {
        0%, 100% { 
            transform: rotateY(0deg);
        }
        50% { 
            transform: rotateY(10deg);
        }
    }
    
    .stacked-rotate[data-position="1"] {
        animation-duration: 4s;
        animation-delay: -0.3s;
    }
    
    .stacked-rotate[data-position="2"] {
        animation-duration: 5s;
        animation-delay: -0.6s;
    }
    
    .stacked-rotate[data-position="3"] {
        animation-duration: 6s;
        animation-delay: -0.9s;
    }
    
    .stacked-rotate[data-position="4"] {
        animation-duration: 7s;
        animation-delay: -1.2s;
    }
    
    /* Custom Animations untuk Placeholder */
    @keyframes slide-fade {
        0%, 100% { 
            opacity: 0.3; 
            transform: translateX(-20px) scale(0.95);
        }
        50% { 
            opacity: 1; 
            transform: translateX(0) scale(1);
        }
    }
    
    @keyframes float {
        0%, 100% { 
            transform: translateY(0) rotate(0deg);
        }
        25% { 
            transform: translateY(-10px) rotate(5deg);
        }
        50% { 
            transform: translateY(-15px) rotate(-5deg);
        }
        75% { 
            transform: translateY(-10px) rotate(5deg);
        }
    }
    
    @keyframes fade-in {
        from { 
            opacity: 0; 
            transform: translateY(20px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }
    
    .animate-slide-fade {
        animation: slide-fade 3s ease-in-out infinite;
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
    
    .perspective-1000 {
        perspective: 1000px;
    }
    
    .transform-gpu {
        transform: translateZ(0);
        will-change: transform;
    }
    
    /* Hover lift effect */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    /* Swiper custom styles */
    .swiper-pagination-bullet {
        background: white !important;
        opacity: 0.5 !important;
        width: 12px !important;
        height: 12px !important;
        transition: all 0.3s ease !important;
    }
    
    .swiper-pagination-bullet-active {
        opacity: 1 !important;
        width: 32px !important;
        border-radius: 6px !important;
    }

    /* Swiper defaults preserved; no forced widths to avoid layout issues */
    /* Hero slider side bleed */
    .hero-swiper { overflow: visible !important; }
  /* Unified frosted shell behind modal content */
  #modalContent{ position: relative; }
  #modalContent::before{
      content: "";
      position: absolute;
      inset: -12px; /* little breathing space */
      border-radius: 1.75rem; /* ~ rounded-3xl */
      background: rgba(255,255,255,0.65);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      box-shadow: 0 30px 80px rgba(0,0,0,0.25);
      border: 1px solid rgba(255,255,255,0.6);
      z-index: 0;
  }
  /* Make inner panes transparent so they sit on top of the shell */
  .modal-pane{ position: relative; z-index: 1; background: transparent !important; border: 0 !important; box-shadow: none !important; }
  /* Keep recent cards medium size */
  .recent-swiper .swiper-slide { max-width: 360px; }
  @media (min-width:1024px){ .recent-swiper .swiper-slide { max-width: 380px; } }
  /* Edge fade so sides look clean */
  .recent-swiper{ overflow: visible; -webkit-mask-image: linear-gradient(to right, transparent 0, black 32px, black calc(100% - 32px), transparent 100%); mask-image: linear-gradient(to right, transparent 0, black 32px, black calc(100% - 32px), transparent 100%); }
  /* Card wrapper with gradient border like reference (no harsh line) */
  .recent-card{ position:relative; border-radius:28px; padding:8px; background:
    linear-gradient(180deg,rgba(255,255,255,.78),rgba(255,255,255,.78)) padding-box,
    linear-gradient(135deg, rgba(255,111,97,.75), rgba(255,175,163,.6)) border-box;
    border:1px solid transparent; box-shadow:0 12px 28px rgba(17,24,39,.08); }
  .recent-card .media{ border-radius:20px; }
  /* Hover lift + soft glow */
  .recent-swiper .group{ transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s; }
  .recent-swiper .group:hover{ transform: translateY(-6px); box-shadow: 0 18px 40px rgba(0,0,0,.14), 0 8px 20px rgba(255,111,97,.14); }
  /* Arrows polish */
  .recent-prev,.recent-next{ transition: transform .2s, box-shadow .2s, background-color .2s; }
  .recent-prev:hover,.recent-next:hover{ transform: scale(1.08); box-shadow: 0 12px 24px rgba(0,0,0,.15); background-color: rgba(255,255,255,.85); }
  /* Neutral gray pills */
  .pill-brand{ background: linear-gradient(180deg,#6B7280,#4B5563); color:#ffffff; padding:.35rem .7rem; border-radius:9999px; box-shadow:0 6px 14px rgba(0,0,0,.18); border:1px solid rgba(255,255,255,.22); }
  .pill-brand-strong{ background: linear-gradient(180deg,#9CA3AF,#6B7280); color:#ffffff; padding:.35rem .6rem; border-radius:14px; box-shadow:0 8px 16px rgba(0,0,0,.2); border:1px solid rgba(255,255,255,.22); }
  .pill-brand-icon{ width:28px; height:28px; display:flex; align-items:center; justify-content:center; border-radius:12px; background: linear-gradient(180deg,#6B7280,#4B5563); color:#ffffff; box-shadow:0 6px 14px rgba(0,0,0,.22); border:1px solid rgba(255,255,255,.22); }
  /* Emphasize active slide (soft) */
  .recent-swiper .swiper-slide{ transition: transform .35s ease, box-shadow .35s ease, opacity .35s ease; }
  .recent-swiper .swiper-slide-active .group{ transform: translateY(-8px) scale(1.04); box-shadow: 0 20px 50px rgba(0,0,0,.16), 0 10px 30px rgba(255,111,97,.18); }
  .recent-swiper .swiper-slide-next .group,
  .recent-swiper .swiper-slide-prev .group{ transform: translateY(-2px) scale(0.99); opacity:.98; }
  .recent-swiper .swiper-slide:not(.swiper-slide-active) .group img{ filter: saturate(.98) contrast(.99); }
.video-swiper .swiper-slide { max-width: 360px; }
@media (min-width:1024px){ .video-swiper .swiper-slide { max-width: 380px; } }
</style>
@endpush

@push('scripts')
<script>
    // Hero Slider Functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.slider-indicator');
    const totalSlides = slides.length;
    let autoSlideInterval;

    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => {
            slide.classList.remove('opacity-100');
            slide.classList.add('opacity-0');
        });
        
        // Update indicators
        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.classList.add('bg-white', 'w-8');
                indicator.classList.remove('bg-white/50', 'w-3');
            } else {
                indicator.classList.remove('bg-white', 'w-8');
                indicator.classList.add('bg-white/50', 'w-3');
            }
        });
        
        // Show current slide
        if (slides[index]) {
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');
        }
        
        currentSlide = index;
    }

    function nextSlide() {
        let next = (currentSlide + 1) % totalSlides;
        showSlide(next);
        resetAutoSlide();
    }

    function prevSlide() {
        let prev = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(prev);
        resetAutoSlide();
    }

    function goToSlide(index) {
        showSlide(index);
        resetAutoSlide();
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            nextSlide();
        }, 5000); // Change slide every 5 seconds
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Start auto-slide when page loads
    if (totalSlides > 1) {
        startAutoSlide();
    }
    
    // 3D Carousel - Foto muncul dari kiri dan kanan bergantian dengan rotasi
    document.addEventListener('DOMContentLoaded', function() {
        const carouselPhotos = document.querySelectorAll('.carousel-photo');
        if (carouselPhotos.length === 0) return;
        
        let isAnimating = false;
        let isHovered = false;
        const animationHandles = [];
        
        // Function untuk setup animasi rotasi
        function setupRotationAnimation(photo) {
            const offset = parseInt(photo.dataset.offset || 0);
            const side = photo.dataset.side || 'center';
            
            // Hapus animasi sebelumnya jika ada
            if (photo._animationHandle) {
                cancelAnimationFrame(photo._animationHandle);
            }
            
            // Jika foto di kiri atau kanan (bukan tengah), tambahkan animasi rotasi
            if (side !== 'center' && !photo._isHovered) {
                let startTime = Date.now();
                const animate = () => {
                    if (!photo._isHovered && !isAnimating) {
                        const elapsed = (Date.now() - startTime) / 1000;
                        const currentOffset = parseInt(photo.dataset.offset || 0);
                        const baseScale = parseFloat(photo.dataset.scale || 1);
                        const translateY = parseInt(photo.dataset.translateY || 0);
                        const translateX = parseInt(photo.dataset.translateX || 0);
                        const baseRotateY = parseInt(photo.dataset.rotateY || 0);
                        
                        // Rotasi ±12 derajat dengan kecepatan berbeda per foto
                        const speed = 0.4 + (Math.abs(currentOffset) * 0.1);
                        const rotationOffset = Math.sin(elapsed * speed) * 12; // ±12 derajat
                        
                        // Update transform dengan rotasi animasi
                        photo.style.transform = `translateY(-${translateY}px) translateX(${translateX}px) scale(${baseScale}) rotateY(${baseRotateY + rotationOffset}deg)`;
                    }
                    photo._animationHandle = requestAnimationFrame(animate);
                };
                animate();
            }
        }
        
        // Setup animasi rotasi untuk semua foto
        carouselPhotos.forEach((photo, index) => {
            const offset = parseInt(photo.dataset.offset || 0);
            const side = photo.dataset.side || 'center';
            const baseScale = parseFloat(photo.dataset.scale || 1);
            const translateY = parseInt(photo.dataset.translateY || 0);
            const translateX = parseInt(photo.dataset.translateX || 0);
            const baseRotateY = parseInt(photo.dataset.rotateY || 0);
            
            // Simpan data original
            photo._baseData = { baseScale, translateY, translateX, baseRotateY, offset, side };
            photo._isHovered = false;
            
            // Setup animasi rotasi
            setupRotationAnimation(photo);
        });
        
        // Auto-rotate carousel: foto bergantian muncul dari kiri dan kanan
        function rotateCarousel() {
            if (isAnimating || isHovered) return;
            
            isAnimating = true;
            const photos = Array.from(carouselPhotos);
            
            // Geser semua foto ke kiri (offset -1)
            photos.forEach((photo) => {
                const currentOffset = parseInt(photo.dataset.offset || 0);
                const newOffset = currentOffset - 1;
                
                // Jika foto sudah terlalu ke kiri, pindah ke kanan
                const centerIndex = Math.floor(photos.length / 2);
                const maxOffset = centerIndex;
                const finalOffset = newOffset < -maxOffset ? maxOffset : newOffset;
                
                // Update data
                photo.dataset.offset = finalOffset;
                const side = finalOffset < 0 ? 'left' : (finalOffset > 0 ? 'right' : 'center');
                photo.dataset.side = side;
                
                // Update transform
                const baseScale = 1 - (Math.abs(finalOffset) * 0.15);
                const translateX = finalOffset * 80;
                const translateY = Math.abs(finalOffset) * 10;
                const rotateY = finalOffset * 25;
                const opacity = 1 - (Math.abs(finalOffset) * 0.15);
                const zIndex = photos.length - Math.abs(finalOffset);
                
                // Update base data
                photo._baseData = { 
                    baseScale, 
                    translateY, 
                    translateX, 
                    baseRotateY: rotateY, 
                    offset: finalOffset, 
                    side 
                };
                
                // Update dataset untuk animasi rotasi
                photo.dataset.scale = baseScale;
                photo.dataset.translateY = translateY;
                photo.dataset.translateX = translateX;
                photo.dataset.rotateY = rotateY;
                
                // Animate dengan transition
                photo.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                photo.style.transform = `translateY(-${translateY}px) translateX(${translateX}px) scale(${baseScale}) rotateY(${rotateY}deg)`;
                photo.style.opacity = opacity;
                photo.style.zIndex = zIndex;
            });
            
            setTimeout(() => {
                isAnimating = false;
                // Restart animasi rotasi untuk semua foto setelah carousel bergerak
                carouselPhotos.forEach((photo) => {
                    setupRotationAnimation(photo);
                });
            }, 800);
        }
        
        // Auto-rotate setiap 3 detik
        setInterval(rotateCarousel, 3000);
        
        // Hover Effect
        carouselPhotos.forEach((photo) => {
            photo.addEventListener('mouseenter', function() {
                photo._isHovered = true;
                isHovered = true;
            });
            
            photo.addEventListener('mouseleave', function() {
                photo._isHovered = false;
                isHovered = false;
            });
        });
    });

    // CSRF
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Playlist from DOM
    function buildPlaylist() {
        const nodes = Array.from(document.querySelectorAll('.photo-item'));
        return nodes.map(n => ({
            id: parseInt(n.dataset.photoId),
            url: n.dataset.photoUrl,
            title: n.dataset.photoTitle,
            category: n.dataset.photoCategory,
            views: n.dataset.photoViews,
            date: n.dataset.photoDate,
            description: n.dataset.photoDescription
        }));
    }

    let playlist = [];
    let currentIndex = 0;
    let slideshowTimer = null;

    function renderModalFromItem(item) {
        const modal = document.getElementById('photoModal');
        const modalPhoto = document.getElementById('modalPhoto');
        const modalTitle = document.getElementById('modalTitle');
        const modalCategory = document.getElementById('modalCategory');
        const modalViews = document.getElementById('modalViews');
        const modalDate = document.getElementById('modalDate');
        const modalDescription = document.getElementById('modalDescription');
        const commentPhotoId = document.getElementById('commentPhotoId');

        modalPhoto.src = item.url;
        modalTitle.textContent = item.title || '';
        modalCategory.textContent = item.category || '';
        modalViews.textContent = item.views || 0;
        modalDate.textContent = item.date || '';
        modalDescription.textContent = item.description || '';
        commentPhotoId.value = item.id || '';

        // load comments
        fetch(`/gallery/photo/${item.id}/comments`).then(r=>r.json()).then(data=>{
            const list = document.getElementById('commentsList');
            if (!list) return;
            list.innerHTML = '';
            if (data.success && data.comments) {
                data.comments.forEach(c => {
                    const row = document.createElement('div');
                    row.className = 'glass-card bg-white/5 rounded-2xl p-3 border border-white/10 flex gap-3';
                    row.innerHTML = `<img src="${c.author_avatar}" class="w-8 h-8 rounded-full"/> <div><div class=\"text-white font-semibold\">${c.author_name}</div><div class=\"text-white/80 text-sm\">${c.comment}</div><div class=\"text-white/60 text-xs\">${c.created_at}</div></div>`;
                    list.appendChild(row);
                });
            }
        }).catch(()=>{});

        // realtime view increment
        fetch(`/gallery/photo/${item.id}/view`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
        }).then(r=>r.json()).then(data=>{
            if (data.success) {
                modalViews.textContent = data.views;
                const idx = playlist.findIndex(p => p.id === item.id);
                if (idx >= 0) playlist[idx].views = data.views;
                const totalViewsEl = document.getElementById('totalViewsNumber');
                if (totalViewsEl) {
                    const current = parseInt((totalViewsEl.textContent || '0').replace(/\D/g, '')) || 0;
                    totalViewsEl.textContent = (current + 1) + '+';
                }
            }
        }).catch(()=>{});
    

    function openPhotoModal(item) {
        try { console.log('[PhotoModal] open', item && item.id); } catch(e) {}
        if (!playlist.length) playlist = buildPlaylist();
        const idx = playlist.findIndex(p => p.id === item.id);
        currentIndex = idx >= 0 ? idx : 0;
        renderModalFromItem(item);

        const modal = document.getElementById('photoModal');
        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                const modalContent = document.getElementById('modalContent');
                if (modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            }, 10);
            document.body.style.overflow = 'hidden';
        } else {
            try { console.warn('[PhotoModal] element not found'); } catch(e) {}
        }
    }
    // Ensure globally accessible for inline onclick
    try { window.openPhotoModal = openPhotoModal; } catch(e) {}

    function showByIndex(i){
        if (!playlist.length) return;
        if (i < 0) i = playlist.length - 1;
        if (i >= playlist.length) i = 0;
        currentIndex = i;
        renderModalFromItem(playlist[currentIndex]);
    }

    function nextPhoto(){ showByIndex(currentIndex + 1); }
    function prevPhoto(){ showByIndex(currentIndex - 1); }

    function startSlideshow(){
        stopSlideshow();
        const btn = document.getElementById('modalPlayPause');
        if (btn) btn.innerHTML = '<i class="fas fa-pause"></i>';
        slideshowTimer = setInterval(nextPhoto, 4000);
    }
    function stopSlideshow(){
        if (slideshowTimer){ clearInterval(slideshowTimer); slideshowTimer = null; }
        const btn = document.getElementById('modalPlayPause');
        if (btn) btn.innerHTML = '<i class="fas fa-play"></i>';
    }

    document.addEventListener('DOMContentLoaded', () => {
        playlist = buildPlaylist();
        const prevBtn = document.getElementById('modalPrev');
        const nextBtn = document.getElementById('modalNext');
        const playBtn = document.getElementById('modalPlayPause');
        const dlBtn = document.getElementById('downloadBtn');
        const tabInfo = document.getElementById('tabInfo');
        const tabDownload = document.getElementById('tabDownload');
        const tabComments = document.getElementById('tabComments');
        const panelInfo = document.getElementById('panelInfo');
        const panelDownload = document.getElementById('panelDownload');
        const panelComments = document.getElementById('panelComments');
        const progress = document.getElementById('slideshowProgress');

        function setActive(panel){
            [panelInfo, panelDownload, panelComments].forEach(p=>p.classList.add('hidden'));
            [tabInfo, tabDownload, tabComments].forEach(b=>b.classList.remove('bg-white','shadow-sm','text-gray-900'));
            if(panel==='info'){ panelInfo.classList.remove('hidden'); tabInfo.classList.add('bg-white','shadow-sm','text-gray-900'); }
            if(panel==='download'){ panelDownload.classList.remove('hidden'); tabDownload.classList.add('bg-white','shadow-sm','text-gray-900'); }
            if(panel==='comments'){ panelComments.classList.remove('hidden'); tabComments.classList.add('bg-white','shadow-sm','text-gray-900'); }
        }
        if (tabInfo) tabInfo.addEventListener('click', ()=>setActive('info'));
        if (tabDownload) tabDownload.addEventListener('click', ()=>setActive('download'));
        if (tabComments) tabComments.addEventListener('click', ()=>setActive('comments'));
        setActive('info');

        function resetProgress(){ if(progress){ progress.style.transition='none'; progress.style.width='0%'; requestAnimationFrame(()=>{ progress.style.transition='width 4s linear'; progress.style.width='100%'; }); } }

        if (prevBtn) prevBtn.addEventListener('click', ()=>{ prevPhoto(); resetProgress(); });
        if (nextBtn) nextBtn.addEventListener('click', ()=>{ nextPhoto(); resetProgress(); });
        if (playBtn) playBtn.addEventListener('click', () => { slideshowTimer ? stopSlideshow() : startSlideshow(); resetProgress(); });
        if (dlBtn) dlBtn.addEventListener('click', () => {
            const id = document.getElementById('commentPhotoId').value;
            const size = document.getElementById('downloadSize').value;
            const wm = document.getElementById('downloadWatermark').checked ? 'true' : 'false';
            window.open(`/download/photo/${id}?size=${encodeURIComponent(size)}&watermark=${wm}`, '_blank');
        });

        const form = document.getElementById('commentForm');
        if (form) form.addEventListener('submit', function(e){
            e.preventDefault();
            const id = document.getElementById('commentPhotoId').value;
            const name = document.getElementById('commentName').value;
            const email = document.getElementById('commentEmail').value;
            const text = document.getElementById('commentText').value;
            fetch(`/gallery/photo/${id}/comments`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ name: name, email: email, comment: text })
            }).then(r=>r.json()).then(data=>{
                if (data.success && data.comment){
                    const list = document.getElementById('commentsList');
                    const c = data.comment;
                    const row = document.createElement('div');
                    row.className = 'rounded-2xl p-3 border border-white/60 bg-white/70 flex gap-3';
                    row.innerHTML = `<img src="${c.author_avatar}" class="w-8 h-8 rounded-full"/> <div><div class=\"font-semibold\">${c.author_name}</div><div class=\"text-sm\">${c.comment}</div><div class=\"text-xs text-gray-600\">${c.created_at}</div></div>`;
                    list.prepend(row);
                    form.reset();
                }
            }).catch(()=>{});
        });
    });
    
    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        if (modal) {
            modal.classList.add('opacity-0');
            const modalContent = document.getElementById('modalContent');
            if (modalContent) {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePhotoModal();
        }
    });
    
    // Close modal on background click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('photoModal');
        if (modal && e.target === modal) {
            closePhotoModal();
        }
    });
</script>
@endpush

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Swipers - Simple and Direct
    window.addEventListener('load', function() {
        // Wait for Swiper
        function waitForSwiper(callback) {
            if (typeof Swiper !== 'undefined') {
                callback();
            } else {
                setTimeout(() => waitForSwiper(callback), 50);
            }
        }
        
        waitForSwiper(function() {
            console.log('✅ Swiper loaded, starting initialization...');
            
            // Hero Swiper
            const heroEl = document.querySelector('.hero-swiper');
            if (heroEl) {
                console.log('🔄 Initializing Hero Swiper...');
                
                // Destroy if exists
                if (heroEl.swiper) {
                    heroEl.swiper.destroy(true, true);
                }
                
                // Check slide count
                let slideCount = heroEl.querySelectorAll('.swiper-slide').length;
                console.log('📊 Hero Swiper slide count (before):', slideCount);

                // If only 1 slide, clone it to enable sliding/looping
                if (slideCount === 1) {
                    const wrapper = heroEl.querySelector('.swiper-wrapper');
                    const first = wrapper.querySelector('.swiper-slide');
                    const clone1 = first.cloneNode(true);
                    const clone2 = first.cloneNode(true);
                    wrapper.appendChild(clone1);
                    wrapper.appendChild(clone2);
                    slideCount = 3;
                    console.log('➕ Cloned slides to enable autoplay. New slideCount:', slideCount);
                }
                
                window.heroSwiper = new Swiper('.hero-swiper', {
                    loop: slideCount > 1,
                    autoplay: { 
                        delay: 4000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: false
                    },
                    // navigation removed per design request
                    pagination: { 
                        el: '.hero-swiper .swiper-pagination', 
                        clickable: true 
                    },
                    centeredSlides: true,
                    slidesPerView: 1.2,
                    speed: 800,
                    spaceBetween: 0,
                    effect: 'coverflow',
                    coverflowEffect: { 
                        rotate: 12, 
                        stretch: 0, 
                        depth: 120, 
                        modifier: 1.05, 
                        slideShadows: true 
                    },
                    watchSlidesProgress: true,
                    on: {
                        init: function(){
                            console.log('✅ Hero Swiper initialized');
                            console.log('📊 Total slides:', this.slides.length);
                            // Force start autoplay multiple times to ensure it works
                            setTimeout(() => { this.autoplay && this.autoplay.start(); }, 100);
                            setTimeout(() => { this.autoplay && this.autoplay.start(); }, 500);
                            setTimeout(() => { this.autoplay && this.autoplay.start(); }, 1000);
                        },
                        slideChange: function() {
                            console.log('🔄 Hero Swiper slide changed to:', this.realIndex);
                        }
                    }
                });
                
            } else {
                console.error('❌ Hero Swiper element not found!');
            }
            
            // Recent Photos Swiper
            const recentEl = document.querySelector('.recent-swiper');
            if (recentEl) {
                console.log('🔄 Initializing Recent Photos Swiper...');
                
                // Destroy if exists
                if (recentEl.swiper) {
                    recentEl.swiper.destroy(true, true);
                }
                
                // Check slide count
                const recentSlideCount = recentEl.querySelectorAll('.swiper-slide').length;
                console.log('📊 Recent Swiper slide count:', recentSlideCount);
                
                window.recentSwiper = new Swiper('.recent-swiper', {
                    loop: recentSlideCount > 1,
                    speed: 650,
                    autoplay: recentSlideCount > 1 ? { 
                        delay: 3000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: false,
                        enabled: true
                    } : false,
                    centeredSlides: true,
                    slidesPerView: 1.2,
                    slidesPerGroup: 1,
                    spaceBetween: 20,
                    breakpoints: {
                        640: { slidesPerView: 2, spaceBetween: 20 },
                        1024: { slidesPerView: 3, spaceBetween: 24 }
                    },
                    effect: 'coverflow',
                    coverflowEffect: { 
                        rotate: 8, 
                        stretch: 0, 
                        depth: 100, 
                        modifier: 1, 
                        slideShadows: false 
                    },
                    navigation: { 
                        nextEl: '.recent-next', 
                        prevEl: '.recent-prev' 
                    },
                    watchSlidesProgress: true,
                    on: {
                        init: function() {
                            console.log('✅ Recent Photos Swiper initialized');
                            console.log('📊 Total slides:', this.slides.length);
                            if (recentSlideCount > 1) {
                                // Force start autoplay multiple times
                                setTimeout(() => {
                                    if (this.autoplay) {
                                        this.autoplay.start();
                                        console.log('▶️ Recent Photos Swiper autoplay STARTED (attempt 1)');
                                    }
                                }, 100);
                                setTimeout(() => {
                                    if (this.autoplay) {
                                        this.autoplay.start();
                                        console.log('▶️ Recent Photos Swiper autoplay STARTED (attempt 2)');
                                    }
                                }, 500);
                                setTimeout(() => {
                                    if (this.autoplay) {
                                        this.autoplay.start();
                                        console.log('▶️ Recent Photos Swiper autoplay STARTED (attempt 3)');
                                    }
                                }, 1000);
                            } else {
                                console.warn('⚠️ Only 1 slide, autoplay disabled');
                            }
                        },
                        slideChange: function() {
                            console.log('🔄 Recent Photos Swiper slide changed to:', this.realIndex);
                        }
                    }
                });
                
                // Hover pause
                recentEl.addEventListener('mouseenter', () => {
                    if (window.recentSwiper && window.recentSwiper.autoplay) {
                        window.recentSwiper.autoplay.stop();
                    }
                });
                recentEl.addEventListener('mouseleave', () => {
                    if (window.recentSwiper && window.recentSwiper.autoplay) {
                        window.recentSwiper.autoplay.start();
                    }
                });
            } else {
                console.error('❌ Recent Swiper element not found!');
            }
        });
    });
    
    // Also try on DOMContentLoaded as backup
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined' && !window.heroSwiper) {
            setTimeout(() => {
                if (typeof Swiper !== 'undefined') {
                    const heroEl = document.querySelector('.hero-swiper');
                    if (heroEl && !heroEl.swiper) {
                        window.heroSwiper = new Swiper('.hero-swiper', {
                            loop: true,
                            autoplay: { delay: 4000, disableOnInteraction: false },
                            // navigation removed per design request
                            pagination: { el: '.hero-swiper .swiper-pagination', clickable: true },
                            centeredSlides: true,
                            slidesPerView: 1.2,
                            speed: 800,
                            spaceBetween: 0,
                            effect: 'coverflow',
                            coverflowEffect: { rotate: 12, stretch: 0, depth: 120, modifier: 1.05, slideShadows: true }
                        });
                        setTimeout(() => window.heroSwiper.autoplay.start(), 500);
                    }
                }
            }, 1000);
        }
    });
</script>
@endpush
@endsection

 