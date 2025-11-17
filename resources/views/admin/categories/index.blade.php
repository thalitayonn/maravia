@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Manage Categories</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Organize and structure your photo collections</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.categories.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-plus mr-2"></i>
                        Add Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Structure and organize your photo collections</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-folder mr-2" style="color: #FEEA77;"></i>
                        {{ $categories->total() }} Categories
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-sort mr-2" style="color: #FEEA77;"></i>
                        Smart Organization
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-folder"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-3 h-3 rounded-full mr-3" style="background: #FEEA77;"></div>
                    Search & Filter Categories
                </h3>
                <p class="text-gray-500 text-sm mt-1">Find and organize your categories efficiently</p>
            </div>
            <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                <i class="fas fa-search mr-1"></i>
                Smart Filter
            </div>
        </div>
        <div>
                <form method="GET" action="{{ route('admin.categories.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Search -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Categories</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search by name, description..." 
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="photos_count" {{ request('sort') === 'photos_count' ? 'selected' : '' }}>Most Photos</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg" style="background: #A3D5FF; color: #1C1C1C;">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-gray-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Grid -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <div class="group relative">
                        <div class="absolute inset-0 bg-orange-200 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-6 border border-orange-100 hover:border-orange-200 transition-all duration-300 transform hover:scale-105">
                            <!-- Category Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $category->name }}</h3>
                                    @if($category->description)
                                        <p class="text-gray-600 text-sm line-clamp-2">{{ $category->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($category->is_active)
                                        <span class="inline-block text-xs px-3 py-1 rounded-full font-medium" style="background: #9DE4B3; color: #1C1C1C;">
                                            <i class="fas fa-check mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-block text-xs px-3 py-1 rounded-full font-medium" style="background: #FF6F61; color: white;">
                                            <i class="fas fa-pause mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Category Stats -->
                            <div class="bg-orange-50 rounded-xl p-4 mb-6">
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <div class="text-2xl font-bold text-orange-600">{{ $category->photos_count ?? 0 }}</div>
                                        <div class="text-xs text-gray-600">Photos</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-amber-600">{{ $category->created_at->format('M Y') }}</div>
                                        <div class="text-xs text-gray-600">Created</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.show', $category) }}" 
                                   class="flex-1 bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white text-center py-2 px-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="flex-1 bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white text-center py-2 px-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="deleteCategory({{ $category->id }})" 
                                        class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white py-2 px-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-folder text-6xl text-orange-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">No categories found</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Start organizing your photos by creating your first category. Group similar photos together for better management!</p>
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-orange-400 to-amber-400 text-white px-8 py-4 rounded-xl font-semibold hover:from-orange-500 hover:to-amber-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Category
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone and will affect all photos in this category.')) {
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
