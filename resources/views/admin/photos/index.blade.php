@extends('layouts.admin')

@section('title', 'Manage Photos')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header relative z-10">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">
                        Manage Photos
                    </h1>
                    <p class="text-gray-600 text-sm lg:text-base">
                        Upload, edit, and organize your beautiful gallery photos
                    </p>
                </div>
                <div class="flex space-x-3 relative z-20">
                    <a href="{{ route('admin.photos.create') }}"
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-plus mr-2"></i>
                        Add Photo
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
                    <p class="text-gray-600 text-sm mb-1">Total Photos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $photos->total() }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Featured</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['featured'] ?? 0 }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-check-circle"></i>
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
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.photos.index') }}" id="filterForm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-filter mr-2 text-coral-500"></i>
                            Filter & Search
                        </h3>
                        @if(request()->hasAny(['status', 'category', 'search']))
                            <a href="{{ route('admin.photos.index') }}" 
                               class="text-sm text-red-500 hover:text-red-700 font-semibold">
                                <i class="fas fa-times-circle mr-1"></i>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" 
                                    onchange="this.form.submit()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-coral-500 transition-all">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" 
                                    onchange="this.form.submit()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-coral-500 transition-all">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Featured Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                            <select name="featured" 
                                    onchange="this.form.submit()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-coral-500 transition-all">
                                <option value="">All</option>
                                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                                <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Non-Featured</option>
                            </select>
                        </div>
                        
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search photos..."
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-coral-500 transition-all">
                                <button type="submit" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-coral-500 hover:text-coral-700">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['status', 'category', 'featured', 'search']))
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="text-sm text-gray-600">Active filters:</span>
                            @if(request('status') !== null)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-coral-100 text-coral-800">
                                    Status: {{ request('status') == '1' ? 'Active' : 'Inactive' }}
                                    <a href="{{ route('admin.photos.index', array_merge(request()->except('status'))) }}" 
                                       class="ml-2 hover:text-coral-900">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('category'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                    Category: {{ $categories->find(request('category'))->name ?? 'Unknown' }}
                                    <a href="{{ route('admin.photos.index', array_merge(request()->except('category'))) }}" 
                                       class="ml-2 hover:text-sky-900">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('featured') !== null)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-lemon-100 text-yellow-800">
                                    {{ request('featured') == '1' ? 'Featured' : 'Non-Featured' }}
                                    <a href="{{ route('admin.photos.index', array_merge(request()->except('featured'))) }}" 
                                       class="ml-2 hover:text-yellow-900">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('search'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ route('admin.photos.index', array_merge(request()->except('search'))) }}" 
                                       class="ml-2 hover:text-gray-900">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Photos Grid -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Photos List</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Showing {{ $photos->count() }} of {{ $photos->total() }} photos</span>
            </div>
        </div>

        @if($photos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <div class="group relative bg-gray-50 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                        <div class="aspect-square overflow-hidden">
                            <img src="{{ $photo->url }}?v={{ $photo->updated_at->timestamp }}"
                                 alt="{{ $photo->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>

                        <!-- Status badges -->
                        <div class="absolute top-3 left-3 flex space-x-2">
                            @if($photo->is_featured)
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                            @if(!$photo->is_active)
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-eye-slash mr-1"></i>Inactive
                                </span>
                            @endif
                        </div>

                        <!-- Action buttons -->
                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.photos.show', $photo) }}"
                                   class="bg-white/90 hover:bg-white p-2 rounded-full shadow-md">
                                    <i class="fas fa-eye text-gray-600"></i>
                                </a>
                                <a href="{{ route('admin.photos.edit', $photo) }}"
                                   class="bg-white/90 hover:bg-white p-2 rounded-full shadow-md">
                                    <i class="fas fa-edit text-teal-600"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Photo info -->
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 truncate">{{ $photo->title }}</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ $photo->category->name ?? 'No Category' }}</p>

                            <div class="flex items-center justify-between mt-3 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ $photo->view_count ?? 0 }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-heart mr-1"></i>
                                    {{ $photo->favorites_count ?? 0 }}
                                </span>
                                <span>{{ $photo->created_at->format('M j') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $photos->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gradient-to-br from-teal-100 to-teal-200 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-images text-5xl text-teal-600"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-3">No photos found</h4>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Start building your amazing gallery by uploading your first photo</p>
                <a href="{{ route('admin.photos.create') }}"
                   class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Upload First Photo
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.photo-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const selected = document.querySelectorAll('.photo-checkbox:checked');
        const count = selected.length;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `${count} photo${count > 1 ? 's' : ''} selected`;
            
            // Add hidden inputs for selected photos
            const existingInputs = bulkForm.querySelectorAll('input[name="photos[]"]');
            existingInputs.forEach(input => input.remove());
            
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'photos[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Bulk form submission
    bulkForm.addEventListener('submit', function(e) {
        const action = this.querySelector('select[name="action"]').value;
        if (!action) {
            e.preventDefault();
            alert('Please select an action');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected photos? This action cannot be undone.')) {
                e.preventDefault();
                return;
            }
        }
    });
});

function toggleFeatured(photoId) {
    fetch(`/admin/photos/${photoId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function toggleActive(photoId) {
    fetch(`/admin/photos/${photoId}/toggle-active`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deletePhoto(photoId) {
    if (confirm('Are you sure you want to delete this photo? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/photos/${photoId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Clean empty form parameters before submit
document.getElementById('filterForm').addEventListener('submit', function(e) {
    // Remove empty parameters (but keep '0' values!)
    const inputs = this.querySelectorAll('input[name], select[name]');
    inputs.forEach(input => {
        // Only remove if truly empty, not if value is '0'
        const val = input.value.trim();
        if (val === '' || val === null || val === undefined) {
            input.disabled = true; // Disable instead of remove to preserve form structure
        }
    });
    
    // Debug log
    console.log('Form data before submit:', new FormData(this));
});

// Force reload images if coming from update/create
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        // Force reload all images to bypass cache
        const images = document.querySelectorAll('img[src*="storage/photos"]');
        images.forEach(img => {
            const src = img.src.split('?')[0]; // Remove existing query params
            img.src = src + '?v=' + Date.now(); // Add fresh timestamp
        });
        
        // Show success message with animation
        const successMsg = document.querySelector('.alert-success');
        if (successMsg) {
            successMsg.classList.add('animate-bounce');
            setTimeout(() => {
                successMsg.classList.remove('animate-bounce');
            }, 1000);
        }
    });
@endif
</script>
@endpush

@push('styles')
<style>
    /* Coral colors */
    .text-coral-500 { color: #FF6F61; }
    .text-coral-700 { color: #e55a4d; }
    .text-coral-800 { color: #cc4d3f; }
    .text-coral-900 { color: #b34035; }
    .bg-coral-100 { background-color: #ffebe9; }
    .focus\:ring-coral-500:focus { --tw-ring-color: #FF6F61; }
    .hover\:text-coral-700:hover { color: #e55a4d; }
    .hover\:text-coral-900:hover { color: #b34035; }
    
    /* Sky colors */
    .bg-sky-100 { background-color: #e8f4ff; }
    .text-sky-800 { color: #1e5a8e; }
    .text-sky-900 { color: #164570; }
    .hover\:text-sky-900:hover { color: #164570; }
    
    /* Lemon colors */
    .bg-lemon-100 { background-color: #fffacd; }
</style>
@endpush
