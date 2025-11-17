@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center text-sm">
            <a href="{{ route('home') }}" class="text-coral-500 hover:text-coral-600 font-medium">
                <i class="fas fa-home mr-2"></i>Beranda
            </a>
            <i class="fas fa-chevron-right mx-3 text-gray-400"></i>
            <a href="{{ route('gallery') }}" class="text-coral-500 hover:text-coral-600 font-medium">
                Galeri
            </a>
            <i class="fas fa-chevron-right mx-3 text-gray-400"></i>
            <span class="text-gray-700 font-medium">{{ $category->name }}</span>
        </div>
    </div>
</div>

<!-- Category Header -->
<section class="py-12 bg-gradient-to-br from-{{ $category->color }}-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-{{ $category->color }}-100 text-{{ $category->color }}-600 text-3xl mb-4">
                <i class="{{ $category->icon }}"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
            @endif
            <p class="text-gray-500 mt-4">{{ $photos->total() }} foto ditemukan</p>
        </div>
    </div>
</section>

<!-- Photos Grid -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($photos->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <a href="{{ route('gallery.photo', $photo) }}" class="group relative overflow-hidden rounded-xl aspect-square hover-lift">
                        <img src="{{ $photo->url }}" 
                             alt="{{ $photo->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-white font-semibold text-lg mb-1">{{ $photo->title }}</h3>
                                <div class="flex items-center text-white/80 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    <span>{{ $photo->view_count }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $photos->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">Belum ada foto di kategori ini</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .hover-lift:hover {
        @apply transform -translate-y-1 shadow-xl;
    }
</style>
@endpush
