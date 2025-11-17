@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-sky-50">
    <!-- Welcome Header with Level Progress -->
    <div class="bg-gradient-to-r from-blue-500 to-sky-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- User Profile Section -->
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <img src="{{ $user->avatar_url }}"
                             alt="{{ $user->name }}"
                             class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                        <div class="absolute -bottom-2 -right-2 bg-yellow-400 text-yellow-900 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold shadow-lg">
                            {{ $user->user_level }}
                        </div>
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-2xl lg:text-3xl font-bold">
                            Selamat datang, {{ $user->name }}! 👋
                        </h1>
                        <p class="text-blue-100 text-sm lg:text-base">
                            Level {{ $user->user_level }} Explorer
                        </p>

                        <!-- Level Progress Bar -->
                        <div class="mt-3">
                            <div class="bg-white/20 rounded-full h-3 w-48 lg:w-64">
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-400 h-3 rounded-full transition-all duration-500"
                                     style="width: {{ $user->progress_to_next_level }}%"></div>
                            </div>
                            <p class="text-xs text-blue-100 mt-1">
                                {{ $user->progress_to_next_level }}% menuju Level {{ $user->user_level + 1 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Section -->
                <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-4 lg:gap-6">
                    <div class="text-center">
                        <div class="text-xl lg:text-2xl font-bold">
                            {{ $user->stats->achievement_points ?? 0 }}
                        </div>
                        <div class="text-xs text-blue-100">Points</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl lg:text-2xl font-bold">
                            {{ $user->achievements->count() }}
                        </div>
                        <div class="text-xs text-blue-100">Badges</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl lg:text-2xl font-bold">
                            #{{ $leaderboardPosition['position'] ?? '?' }}
                        </div>
                        <div class="text-xs text-blue-100">Ranking</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Stats & Achievements -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Interactive Stats Cards -->
                <div id="stats-section" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Views Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Total Views</p>
                                <p class="text-2xl font-bold text-gray-900 counter" data-target="{{ $user->stats->total_views ?? 0 }}">0</p>
                            </div>
                            <div class="text-3xl text-blue-500">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-blue-600 text-xs font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span>+12% dari minggu lalu</span>
                        </div>
                    </div>

                    <!-- Favorites Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Favorites</p>
                                <p class="text-2xl font-bold text-gray-900 counter" data-target="{{ $user->stats->total_favorites ?? 0 }}">0</p>
                            </div>
                            <div class="text-3xl text-red-500">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-blue-600 text-xs font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            <span>{{ $recentFavorites->count() }} foto baru</span>
                        </div>
                    </div>

                    <!-- Downloads Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Downloads</p>
                                <p class="text-2xl font-bold text-gray-900 counter" data-target="{{ $user->stats->total_downloads ?? 0 }}">0</p>
                            </div>
                            <div class="text-3xl text-green-500">
                                <i class="fas fa-download"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-blue-600 text-xs font-medium">
                            <i class="fas fa-chart-line mr-1"></i>
                            <span>Trending naik</span>
                        </div>
                    </div>

                    <!-- Collections Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Collections</p>
                                <p class="text-2xl font-bold text-gray-900 counter" data-target="{{ $user->stats->total_collections ?? 0 }}">0</p>
                            </div>
                            <div class="text-3xl text-purple-500">
                                <i class="fas fa-folder"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-blue-600 text-xs font-medium">
                            <i class="fas fa-star mr-1"></i>
                            <span>{{ $collections->count() }} aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Achievement Showcase -->
                <div id="achievements-section" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                            Achievement Gallery
                        </h2>
                        <button class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            View All →
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($achievementProgress as $achievement)
                            <div class="achievement-card {{ $achievement['earned'] ? 'earned' : 'locked' }}"
                                 data-achievement="{{ $achievement['type'] }}">
                                <!-- Achievement Card Container -->
                                <div class="h-full min-h-[200px] p-4 rounded-xl transition-all duration-300 hover:scale-105 cursor-pointer border-2
                                           {{ $achievement['earned']
                                               ? 'bg-gradient-to-br from-yellow-50 to-orange-50 border-yellow-200 shadow-md'
                                               : 'bg-gray-50 border-gray-200 hover:border-gray-300' }}">

                                    <!-- Icon Section -->
                                    <div class="text-center mb-3">
                                        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2
                                                   {{ $achievement['earned'] ? 'bg-yellow-100' : 'bg-gray-200' }}">
                                            <i class="{{ $achievement['icon'] }} text-xl
                                                     {{ $achievement['earned'] ? 'text-yellow-600' : 'text-gray-400' }}"></i>
                                        </div>
                                    </div>

                                    <!-- Content Section -->
                                    <div class="text-center flex-1 flex flex-col justify-between">
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900 mb-1 leading-tight">
                                                {{ $achievement['name'] }}
                                            </h4>
                                            <p class="text-xs text-gray-600 mb-3 leading-relaxed">
                                                {{ $achievement['description'] }}
                                            </p>
                                        </div>

                                        <!-- Status Section -->
                                        <div class="mt-auto">
                                            @if(!$achievement['earned'])
                                                <!-- Progress Bar -->
                                                <div class="mb-2">
                                                    <div class="bg-gray-200 rounded-full h-2 mb-1">
                                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                                                             style="width: {{ $achievement['progress'] }}%"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-500">{{ $achievement['progress'] }}%</p>
                                                </div>
                                            @else
                                                <!-- Points Badge -->
                                                <div class="flex items-center justify-center">
                                                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                                        +{{ $achievement['points'] }} pts
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Favorites -->
                @if($recentFavorites->count() > 0)
                <div id="favorites-section" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <!-- Debug Info -->
                    <div class="hidden">
                        Debug: Found {{ $recentFavorites->count() }} favorites
                        @foreach($recentFavorites as $photo)
                            Photo ID: {{ $photo->id }}, Path: {{ $photo->path }}, Thumbnail: {{ $photo->thumbnail_path }}
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-heart text-red-500 mr-2"></i>
                            Foto Favorit Terbaru
                        </h2>
                        <a href="/dashboard" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach($recentFavorites as $photo)
                            <div class="group relative overflow-hidden rounded-xl aspect-square cursor-pointer hover-lift"
                                 onclick="openPhotoModal({{ $photo->id }})">
                                <img src="{{ $photo->url }}" alt="{{ $photo->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="absolute bottom-2 left-2 right-2">
                                        <p class="text-white text-xs font-semibold truncate">{{ $photo->title }}</p>
                                        <p class="text-white/80 text-xs">{{ $photo->category->name }}</p>
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-heart text-xs"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Activity & Quick Actions -->
            <div class="space-y-8">

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Quick Actions
                    </h3>

                    <div class="space-y-3">
                        <button onclick="createCollection()"
                                class="w-full bg-gradient-to-r from-blue-500 to-sky-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-600 hover:to-sky-700 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-folder-plus mr-2"></i>
                            Buat Koleksi Baru
                        </button>

                        <button onclick="explorePhotos()"
                                class="w-full bg-gradient-to-r from-blue-500 to-sky-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-600 hover:to-sky-700 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-compass mr-2"></i>
                            Jelajahi Foto
                        </button>

                        <button onclick="viewLeaderboard()"
                                class="w-full bg-gradient-to-r from-blue-500 to-sky-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-600 hover:to-sky-700 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-trophy mr-2"></i>
                            Leaderboard
                        </button>
                    </div>
                </div>

                <!-- Collections Preview -->
                @if($collections->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-folder text-blue-500 mr-2"></i>
                            Koleksi Saya
                        </h3>
                        <a href="{{ route('user.collections.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($collections as $collection)
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer"
                                 onclick="viewCollection({{ $collection->id }})">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-sky-500 rounded-lg flex items-center justify-center text-white mr-3">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $collection->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $collection->photos_count }} foto</p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        Aktivitas Terbaru
                    </h3>

                    <div class="space-y-4 max-h-64 overflow-y-auto custom-scrollbar">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-{{ $activity->activity_color }}-100 text-{{ $activity->activity_color }}-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="{{ $activity->activity_icon }} text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        Anda <span class="font-semibold">{{ $activity->activity_description }}</span>
                                        @if($activity->activityable)
                                            <span class="text-blue-600 font-medium">{{ $activity->activityable->title ?? $activity->activityable->name }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-history text-4xl mb-2"></i>
                                <p>Belum ada aktivitas</p>
                                <p class="text-sm">Mulai jelajahi foto untuk melihat aktivitas Anda!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Photos Section -->
        @if($recommendedPhotos->count() > 0)
        <div id="recommendations-section" class="mt-12">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-magic text-purple-500 mr-2"></i>
                        Rekomendasi Untuk Anda
                    </h2>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Refresh →
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach($recommendedPhotos as $photo)
                        <div class="group relative overflow-hidden rounded-xl aspect-square cursor-pointer hover-lift"
                             onclick="openPhotoModal({{ $photo->id }})">
                            <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-2 left-2 right-2">
                                    <p class="text-white text-xs font-semibold truncate">{{ $photo->title }}</p>
                                </div>
                                <div class="absolute top-2 right-2 space-x-1">
                                    <button onclick="event.stopPropagation(); toggleFavorite({{ $photo->id }})"
                                            class="bg-white/20 backdrop-blur-sm text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500 transition-colors">
                                        <i class="fas fa-heart text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-lift:hover {
        @apply transform -translate-y-1 shadow-xl;
    }

    .achievement-card.earned {
        animation: glow 2s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from { box-shadow: 0 0 5px rgba(251, 191, 36, 0.5); }
        to { box-shadow: 0 0 20px rgba(251, 191, 36, 0.8); }
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 2px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 16);
        });

        // Animate stat cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = `${Math.random() * 0.5}s`;
                    entry.target.classList.add('animate-bounce-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.bg-white.rounded-2xl').forEach(card => {
            observer.observe(card);
        });
    });

    // Interactive functions
    function createCollection() {
        // This will open a modal to create new collection
        alert('Feature akan segera hadir!');
    }

    function explorePhotos() {
        window.location.href = '{{ route("gallery") }}';
    }

    function viewLeaderboard() {
        alert('Leaderboard akan segera hadir!');
    }

    function viewCollection(id) {
        alert('View collection ' + id);
    }

    function openPhotoModal(id) {
        window.location.href = `/gallery/photo/${id}`;
    }

    function toggleFavorite(photoId) {
        fetch(`/dashboard/photos/${photoId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.favorited) {
                showNotification('❤️ Foto ditambahkan ke favorit!', 'success');
            } else {
                showNotification('💔 Foto dihapus dari favorit!', 'info');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan!', 'error');
        });
    }

    function showNotification(message, type) {
        // Simple notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endpush
