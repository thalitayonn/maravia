@extends('layouts.admin')

@section('title', 'Manage Photos')

@section('content')
<div class="flex-1 overflow-auto">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20 sticky top-0 z-10">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Manage Photos</h1>
                    <p class="text-blue-200 mt-1">Upload, edit, and organize gallery photos</p>
                </div>
                <a href="{{ route('admin.photos.create') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Photo
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Search and Filters -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 mb-6">
            <form method="GET" action="{{ route('admin.photos.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search photos..." 
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Category</label>
                        <select name="category" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title A-Z</option>
                            <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Most Views</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.photos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 mb-6" id="bulk-actions" style="display: none;">
            <form method="POST" action="{{ route('admin.photos.bulk-action') }}" id="bulk-form">
                @csrf
                <div class="flex items-center space-x-4">
                    <span class="text-white font-medium">With selected:</span>
                    <select name="action" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose action...</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="feature">Mark as Featured</option>
                        <option value="unfeature">Remove from Featured</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply Action
                    </button>
                    <span class="text-blue-200" id="selected-count">0 photos selected</span>
                </div>
            </form>
        </div>

        <!-- Photos Grid -->
        @if($photos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <div class="bg-white/10 backdrop-blur-md rounded-xl overflow-hidden border border-white/20 hover:border-white/40 transition-all duration-200 transform hover:scale-105">
                        <!-- Checkbox -->
                        <div class="absolute top-3 left-3 z-10">
                            <input type="checkbox" name="photo_ids[]" value="{{ $photo->id }}" 
                                   class="photo-checkbox w-5 h-5 text-blue-600 bg-white/20 border-white/40 rounded focus:ring-blue-500">
                        </div>

                        <!-- Photo -->
                        <div class="relative">
                            <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" 
                                 class="w-full h-48 object-cover">
                            
                            <!-- Status Badges -->
                            <div class="absolute top-3 right-3 space-y-1">
                                @if($photo->is_featured)
                                    <span class="inline-block bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">Featured</span>
                                @endif
                                @if(!$photo->is_active)
                                    <span class="inline-block bg-red-500 text-white text-xs px-2 py-1 rounded-full">Inactive</span>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="flex space-x-1">
                                    <button onclick="toggleFeatured({{ $photo->id }})" 
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    <button onclick="toggleActive({{ $photo->id }})" 
                                            class="bg-{{ $photo->is_active ? 'red' : 'green' }}-500 hover:bg-{{ $photo->is_active ? 'red' : 'green' }}-600 text-white p-2 rounded-full transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if($photo->is_active)
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM4 10a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Info -->
                        <div class="p-4">
                            <h3 class="text-white font-semibold truncate mb-1">{{ $photo->title }}</h3>
                            <p class="text-blue-200 text-sm mb-2">{{ $photo->category->name ?? 'No category' }}</p>
                            
                            @if($photo->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($photo->tags->take(3) as $tag)
                                        <span class="inline-block bg-blue-500/20 text-blue-300 text-xs px-2 py-1 rounded">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                    @if($photo->tags->count() > 3)
                                        <span class="text-blue-300 text-xs">+{{ $photo->tags->count() - 3 }} more</span>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-xs text-gray-400 mb-3">
                                <span>{{ $photo->view_count }} views</span>
                                <span>{{ $photo->created_at->format('M j, Y') }}</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.photos.show', $photo) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                                    View
                                </a>
                                <a href="{{ route('admin.photos.edit', $photo) }}" 
                                   class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                                    Edit
                                </a>
                                <button onclick="deletePhoto({{ $photo->id }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zM8 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $photos->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No photos found</h3>
                <p class="text-gray-400 mb-4">Start by uploading your first photo to the gallery.</p>
                <a href="{{ route('admin.photos.create') }}" 
                   class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add First Photo
                </a>
            </div>
        @endif
    </div>
</div>

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
</script>
@endpush
@endsection
