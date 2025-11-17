@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">
                        Dashboard Admin
                    </h1>
                    <p class="text-gray-600 text-sm lg:text-base">
                        Selamat datang kembali! Berikut adalah ringkasan galeri Anda.
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.photos.create') }}"
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Foto
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-folder-plus mr-2"></i>
                        Tambah Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Foto</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_photos'] ?? 0 }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_categories'] ?? 0 }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-folder"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_views'] ?? 0) }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Downloads</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads'] ?? 0) }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-download"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.photos.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-900 p-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-center border border-gray-100">
                    <i class="fas fa-images text-xl mb-2 block" style="color: #3A3A3F;"></i>
                    Kelola Foto
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-900 p-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-center border border-gray-100">
                    <i class="fas fa-folder-open text-xl mb-2 block" style="color: #3A3A3F;"></i>
                    Kelola Kategori
                </a>
                <a href="{{ route('admin.tags.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-900 p-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-center border border-gray-100">
                    <i class="fas fa-tags text-xl mb-2 block" style="color: #3A3A3F;"></i>
                    Kelola Tags
                </a>
                <a href="{{ route('admin.comments.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-900 p-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-center border border-gray-100">
                    <i class="fas fa-comments text-xl mb-2 block" style="color: #3A3A3F;"></i>
                    Kelola Komentar
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4">System Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Database Status</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2 animate-pulse" style="background: #FEEA77;"></div>
                        <span class="text-sm font-medium" style="color: #FEEA77;">Connected</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Storage Status</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2 animate-pulse" style="background: #FEEA77;"></div>
                        <span class="text-sm font-medium" style="color: #FEEA77;">Available</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Cache Status</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm font-medium text-yellow-600">Optimizing</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Backup Status</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm font-medium text-blue-600">Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h3>
            <a href="#" class="text-teal-600 hover:text-teal-700 text-sm font-medium">Lihat Semua</a>
        </div>

        <div class="space-y-4">
            @forelse($recentActivities ?? [] as $activity)
                <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-{{ $activity['color'] ?? 'teal' }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $activity['icon'] ?? 'fa-activity' }} text-{{ $activity['color'] ?? 'teal' }}-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['title'] ?? 'Activity' }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['time'] ?? 'Recently' }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="bg-teal-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-teal-600"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Belum ada aktivitas</h4>
                    <p class="text-gray-500">Aktivitas terbaru akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
