@extends('layouts.admin')

@section('title', 'Tags Management')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Tags Management</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Organize and manage photo tags</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.tags.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Tag
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Organize and categorize your photos with smart tags</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-tags mr-2" style="color: #FEEA77;"></i>
                        {{ $stats['total_tags'] }} Tags
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-images mr-2" style="color: #FEEA77;"></i>
                        {{ $stats['tagged_photos'] }} Tagged Photos
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-tags"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Tags</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tags'] }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Active Tags</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_tags'] }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Tagged Photos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tagged_photos'] }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Most Used</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['most_used'] }}</p>
                </div>
                <div class="text-3xl" style="color: #FEEA77;">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>

        <!-- Search and Filters -->
        <div class="modern-card mb-8 hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-amber-50">
            <div class="modern-card-header border-b border-amber-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="modern-card-title text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-amber-400 rounded-full mr-3"></div>
                            Search & Filter Tags
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Find and organize your tags efficiently</p>
                    </div>
                    <div class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-search mr-1"></i>
                        Smart Filter
                    </div>
                </div>
            </div>
            <div class="modern-card-content pt-8">
                <form method="GET" action="{{ route('admin.tags.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Search -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Tags</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search by name..." 
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent transition-all duration-300 group-hover:border-amber-200">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent transition-all duration-300 group-hover:border-amber-200">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent transition-all duration-300 group-hover:border-amber-200">
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="photos" {{ request('sort') === 'photos' ? 'selected' : '' }}>Most Used</option>
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-500 hover:to-orange-500 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.tags.index') }}" class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-700 px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if($tags->count() > 0)
        <div class="modern-card mb-8 hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50" id="bulk-actions" style="display: none;">
            <div class="modern-card-content">
                <form method="POST" action="{{ route('admin.tags.bulk-action') }}" id="bulk-form">
                    @csrf
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                            <span class="text-gray-700 font-semibold">Bulk Actions:</span>
                        </div>
                        <select name="action" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-6 py-2 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                            Apply Action
                        </button>
                        <span class="text-gray-600 bg-orange-100 px-3 py-1 rounded-full text-sm font-medium" id="selected-count">0 tags selected</span>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Tags List -->
        @if($tags->count() > 0)
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
                <div class="modern-card-header border-b border-orange-100 pb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="modern-card-title text-xl font-bold flex items-center">
                                <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                                All Tags
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Manage your photo organization tags</p>
                        </div>
                    </div>
                </div>

                <div class="modern-card-content pt-8">
                    <div class="space-y-4">
                        @foreach($tags as $tag)
                            <div class="tag-item group relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-200 to-amber-200 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                                <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-6 border border-orange-100 hover:border-orange-200 transition-all duration-300">
                                    <div class="flex items-center space-x-6">
                                        <!-- Checkbox -->
                                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" 
                                               class="tag-checkbox w-5 h-5 text-orange-600 bg-white/80 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">

                                        <!-- Tag Color & Name -->
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300" 
                                                 style="background: linear-gradient(135deg, {{ $tag->color }}CC, {{ $tag->color }});">
                                                <i class="fas fa-tag text-white text-lg"></i>
                                            </div>
                                        </div>

                                        <!-- Tag Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="text-gray-800 font-bold text-lg">{{ $tag->name }}</h4>
                                                @if(!$tag->is_active)
                                                    <span class="inline-block bg-gradient-to-r from-red-400 to-pink-400 text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                                                        <i class="fas fa-pause mr-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-6 text-sm">
                                                <span class="flex items-center bg-orange-100 text-orange-600 px-3 py-1 rounded-lg font-medium">
                                                    <i class="fas fa-images mr-2"></i>
                                                    {{ $tag->photos_count }} photos
                                                </span>
                                                <span class="flex items-center bg-gray-100 text-gray-600 px-3 py-1 rounded-lg">
                                                    <i class="fas fa-calendar mr-2"></i>
                                                    {{ $tag->created_at->format('M j, Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="flex items-center space-x-2">
                                            <button onclick="toggleActive({{ $tag->id }})" 
                                                    class="bg-gradient-to-r from-{{ $tag->is_active ? 'red' : 'amber' }}-400 to-{{ $tag->is_active ? 'pink' : 'orange' }}-400 hover:from-{{ $tag->is_active ? 'red' : 'amber' }}-500 hover:to-{{ $tag->is_active ? 'pink' : 'orange' }}-500 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg">
                                                @if($tag->is_active)
                                                    <i class="fas fa-pause text-sm"></i>
                                                @else
                                                    <i class="fas fa-play text-sm"></i>
                                                @endif
                                            </button>

                                            <a href="{{ route('admin.tags.show', $tag) }}" 
                                               class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>

                                            <a href="{{ route('admin.tags.edit', $tag) }}" 
                                               class="bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>

                                            <button onclick="deleteTag({{ $tag->id }})" 
                                                    class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $tags->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-tags text-6xl text-orange-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">No Tags Found</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Start by creating your first tag to organize your photos efficiently.</p>
                <a href="{{ route('admin.tags.create') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-orange-400 to-amber-400 text-white px-8 py-4 rounded-xl font-semibold hover:from-orange-500 hover:to-amber-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Tag
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.tag-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    // Bulk actions functionality
    function updateBulkActions() {
        const selected = document.querySelectorAll('.tag-checkbox:checked');
        const count = selected.length;
        
        if (count > 0 && bulkActions) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `${count} tag${count > 1 ? 's' : ''} selected`;
            
            // Add hidden inputs for selected tags
            const existingInputs = bulkForm.querySelectorAll('input[name="tags[]"]');
            existingInputs.forEach(input => input.remove());
            
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tags[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else if (bulkActions) {
            bulkActions.style.display = 'none';
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Bulk form submission
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const action = this.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action');
                return;
            }
            
            if (action === 'delete') {
                if (!confirm('Are you sure you want to delete the selected tags? Tags with photos cannot be deleted.')) {
                    e.preventDefault();
                    return;
                }
            }
        });
    }
});

function toggleActive(tagId) {
    fetch(`/admin/tags/${tagId}/toggle-active`, {
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

function deleteTag(tagId) {
    if (confirm('Are you sure you want to delete this tag? Tags with photos cannot be deleted.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/tags/${tagId}`;
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
