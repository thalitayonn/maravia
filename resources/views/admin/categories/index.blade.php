@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="flex-1 overflow-auto">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20 sticky top-0 z-10">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Manage Categories</h1>
                    <p class="text-blue-200 mt-1">Organize photos into categories</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Category
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Search and Filters -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 mb-6">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search categories..." 
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="order" {{ request('sort') === 'order' ? 'selected' : '' }}>Display Order</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="photos" {{ request('sort') === 'photos' ? 'selected' : '' }}>Most Photos</option>
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 mb-6" id="bulk-actions" style="display: none;">
            <form method="POST" action="{{ route('admin.categories.bulk-action') }}" id="bulk-form">
                @csrf
                <div class="flex items-center space-x-4">
                    <span class="text-white font-medium">With selected:</span>
                    <select name="action" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Choose action...</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply Action
                    </button>
                    <span class="text-blue-200" id="selected-count">0 categories selected</span>
                </div>
            </form>
        </div>

        <!-- Categories List -->
        @if($categories->count() > 0)
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 overflow-hidden">
                <div class="p-4 border-b border-white/20">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Categories</h3>
                        @if(request('sort', 'order') === 'order')
                            <p class="text-blue-200 text-sm">Drag to reorder categories</p>
                        @endif
                    </div>
                </div>

                <div id="categories-list" class="divide-y divide-white/10">
                    @foreach($categories as $category)
                        <div class="category-item p-4 hover:bg-white/5 transition-colors" data-id="{{ $category->id }}">
                            <div class="flex items-center space-x-4">
                                <!-- Checkbox -->
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" 
                                       class="category-checkbox w-5 h-5 text-green-600 bg-white/20 border-white/40 rounded focus:ring-green-500">

                                <!-- Drag Handle (only show when sorting by order) -->
                                @if(request('sort', 'order') === 'order')
                                    <div class="drag-handle cursor-move text-gray-400 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Color & Icon -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" 
                                         style="background-color: {{ $category->color }}">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} text-white text-sm"></i>
                                        @else
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Category Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="text-white font-semibold">{{ $category->name }}</h4>
                                        @if(!$category->is_active)
                                            <span class="inline-block bg-red-500 text-white text-xs px-2 py-1 rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    @if($category->description)
                                        <p class="text-blue-200 text-sm mt-1">{{ Str::limit($category->description, 100) }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-400">
                                        <span>{{ $category->photos_count }} photos</span>
                                        <span>Created {{ $category->created_at->format('M j, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="flex items-center space-x-2">
                                    <button onclick="toggleActive({{ $category->id }})" 
                                            class="bg-{{ $category->is_active ? 'red' : 'green' }}-500 hover:bg-{{ $category->is_active ? 'red' : 'green' }}-600 text-white p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if($category->is_active)
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM4 10a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    </button>

                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <button onclick="deleteCategory({{ $category->id }})" 
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zM8 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No categories found</h3>
                <p class="text-gray-400 mb-4">Create your first category to organize photos.</p>
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add First Category
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');
    const categoriesList = document.getElementById('categories-list');

    // Bulk actions functionality
    function updateBulkActions() {
        const selected = document.querySelectorAll('.category-checkbox:checked');
        const count = selected.length;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `${count} categor${count > 1 ? 'ies' : 'y'} selected`;
            
            // Add hidden inputs for selected categories
            const existingInputs = bulkForm.querySelectorAll('input[name="categories[]"]');
            existingInputs.forEach(input => input.remove());
            
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'categories[]';
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
            if (!confirm('Are you sure you want to delete the selected categories? Categories with photos cannot be deleted.')) {
                e.preventDefault();
                return;
            }
        }
    });

    // Sortable functionality (only when sorting by order)
    @if(request('sort', 'order') === 'order')
        if (categoriesList) {
            const sortable = Sortable.create(categoriesList, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: function(evt) {
                    const categoryIds = [];
                    const items = categoriesList.querySelectorAll('.category-item');
                    
                    items.forEach((item, index) => {
                        categoryIds.push({
                            id: parseInt(item.dataset.id),
                            order: index + 1
                        });
                    });

                    // Update order via AJAX
                    fetch('{{ route("admin.categories.update-order") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ categories: categoryIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error('Failed to update order:', data.message);
                            // Revert the change
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order:', error);
                        location.reload();
                    });
                }
            });
        }
    @endif
});

function toggleActive(categoryId) {
    fetch(`/admin/categories/${categoryId}/toggle-active`, {
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

function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category? Categories with photos cannot be deleted.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${categoryId}`;
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
