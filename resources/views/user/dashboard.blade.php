@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Welcome Header -->
    <div class="bg-primary-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full border-4 border-white shadow-lg">
                <h1 class="text-2xl lg:text-3xl font-bold">Selamat datang, {{ $user->name }}! 👋</h1>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Recent Favorites -->
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
                        @if($recentFavorites->count() > 0)
                        @foreach($recentFavorites as $photo)
                            <div class="group relative overflow-hidden rounded-xl aspect-square cursor-pointer hover-lift"
                                 onclick="openPhotoModal({{ $photo->id }})">
                                <img src="{{ route('api.photos.thumbnail', $photo) }}" alt="{{ $photo->title }}"
                                     onerror="this.onerror=null; this.src='{{ route('api.photos.image', $photo) }}'"
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
                        @else
                            <div class="col-span-6">
                                <div class="w-full py-10 text-center text-gray-500">
                                    <i class="fas fa-heart text-red-400 mr-2"></i>
                                    <span>Belum ada foto favorit. Mulai like foto di galeri!</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Komentar Saya -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-comments text-primary-600 mr-2"></i>
                        Komentar Saya
                    </h3>
                    @if($userComments->count())
                        <div class="space-y-4">
                            @foreach($userComments as $c)
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                                        @if($c->photo)
                                            <img src="{{ route('api.photos.thumbnail', $c->photo) }}" alt="{{ $c->photo->title }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800">{{ $c->comment }}</p>
                                        @if($c->photo)
                                            <a href="{{ route('gallery.photo', $c->photo) }}" class="text-xs text-primary-600 hover:underline">{{ $c->photo->title }}</a>
                                        @endif
                                        <div class="text-xs text-gray-400">{{ $c->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-gray-500">Belum ada komentar.</div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">

                <!-- Testimoni Saya -->
                @if($userTestimonial)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-quote-left text-primary-600 mr-2"></i>
                        Testimoni Saya
                    </h3>
                    <p class="text-sm text-gray-700">{{ $userTestimonial->message }}</p>
                    <div class="mt-3 text-xs">
                        @if($userTestimonial->is_approved)
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">Disetujui</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">Menunggu persetujuan</span>
                        @endif
                        <span class="text-gray-400 ml-2">{{ $userTestimonial->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endif

                <!-- Collections Preview -->
                @if($collections->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-folder text-primary-600 mr-2"></i>
                            Koleksi Saya
                        </h3>
                        <a href="{{ route('user.collections.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($collections as $collection)
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer"
                                 onclick="viewCollection({{ $collection->id }})">
                                <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center text-white mr-3">
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
                        <i class="fas fa-clock text-primary-600 mr-2"></i>
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
                        <i class="fas fa-magic text-primary-600 mr-2"></i>
                        Rekomendasi Untuk Anda
                    </h2>
                    <button class="text-primary-600 hover:text-primary-700 font-semibold text-sm">
                        Refresh →
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach($recommendedPhotos as $photo)
                        <div class="group relative overflow-hidden rounded-xl aspect-square cursor-pointer hover-lift"
                             onclick="openPhotoModal({{ $photo->id }})">
                            <img src="{{ route('api.photos.thumbnail', $photo) }}" alt="{{ $photo->title }}"
                                 onerror="this.onerror=null; this.src='{{ route('api.photos.image', $photo) }}'"
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
    document.addEventListener('DOMContentLoaded', function() {
        // Basic card appearance animation can be added later if needed
    });

    // Interactive functions
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
