@extends('layouts.app')

@section('title', 'Testimoni - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-sky-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-500 to-sky-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Testimoni</h1>
                        <p class="text-blue-100 mt-1">Suara dari komunitas Galeri Cione</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="hidden md:flex space-x-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $testimonials->total() }}</div>
                        <div class="text-xs text-blue-100">Total Testimoni</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $testimonials->count() }}</div>
                        <div class="text-xs text-blue-100">Ditampilkan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text-xs text-blue-100">Terverifikasi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Testimonials List -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Testimonials Grid -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-comments text-blue-500 mr-3"></i>
                            Testimoni Terbaru
                        </h2>
                        <button onclick="refreshTestimonials()" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            Refresh →
                        </button>
                    </div>

                    @forelse($testimonials as $item)
                        <div class="group bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer mb-4" onclick="showTestimonialDetail({{ $item->id }})">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-sky-500 flex items-center justify-center text-white flex-shrink-0">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item->name }}</h3>
                                            @if($item->role)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->role }}</p>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                    <blockquote class="mt-3 text-gray-700 dark:text-gray-200 leading-relaxed">
                                        {{ Str::limit($item->message, 200) }}
                                        @if(strlen($item->message) > 200)
                                            <span class="text-blue-600 dark:text-blue-400 cursor-pointer font-medium">... Selengkapnya</span>
                                        @endif
                                    </blockquote>
                                    <div class="mt-3 flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-full">
                                            <i class="fas fa-shield-alt mr-1"></i> Moderated
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p>Belum ada testimoni</p>
                            <p class="text-sm">Jadilah yang pertama memberikan testimoni!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($testimonials->hasPages())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-4 border border-gray-100 dark:border-gray-700">
                    {{ $testimonials->links() }}
                </div>
                @endif
            </div>

            <!-- Right Column - Submit Form & Stats -->
            <div class="space-y-8">

                <!-- Submit Testimonial Form -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-paper-plane text-blue-500 mr-2"></i>
                        Kirim Testimoni
                    </h3>

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('testimonials.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                   class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"/>
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                                   class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"/>
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peran (opsional)</label>
                            <input type="text" name="role" value="{{ old('role') }}" placeholder="Siswa / Guru / Orang Tua"
                                   class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"/>
                            @error('role')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pesan</label>
                            <textarea name="message" rows="4" required placeholder="Tulis pengalaman Anda menggunakan Galeri Cione..."
                                      class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-sky-600 text-white py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-sky-700 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Testimoni
                        </button>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Testimoni akan ditinjau terlebih dahulu oleh admin sebelum ditampilkan.</p>
                    </form>
                </div>

                <!-- Stats Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                        Statistik Testimoni
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Testimoni</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $testimonials->total() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Halaman Ini</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $testimonials->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                            <span class="inline-flex items-center bg-green-50 dark:bg-green-900/40 text-green-700 dark:text-green-300 px-2 py-1 rounded-full text-xs">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-bolt text-sky-500 mr-2"></i>
                        Quick Actions
                    </h3>

                    <div class="space-y-3">
                        <button onclick="shareTestimonials()" class="w-full bg-gradient-to-r from-blue-500 to-sky-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-600 hover:to-sky-700 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-share mr-2"></i>
                            Bagikan Testimoni
                        </button>

                        <button onclick="viewAllTestimonials()" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-sky-600 hover:to-blue-700 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Semua
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .group:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    function refreshTestimonials() {
        location.reload();
    }

    function showTestimonialDetail(id) {
        alert('Detail testimoni ' + id + ' - Feature coming soon!');
    }

    function shareTestimonials() {
        if (navigator.share) {
            navigator.share({
                title: 'Testimoni Galeri Cione',
                text: 'Lihat testimoni dari komunitas Galeri Cione',
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link berhasil disalin!');
        }
    }

    function viewAllTestimonials() {
        alert('Fitur akan segera hadir!');
    }
</script>
@endpush
