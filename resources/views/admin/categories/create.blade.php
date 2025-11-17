@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Create New Category</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Add a new category to organize your photo collections</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 pb-8">

        <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-4xl mx-auto">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Information -->
                <div class="space-y-6">
                    <!-- Category Details -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-info-circle mr-2" style="color: #FEEA77;"></i>
                                        Category Information
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Basic details about your category</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-asterisk mr-1"></i>
                                    Required
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-6">
                                <!-- Name -->
                                <div class="group">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200"
                                           placeholder="Enter category name">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="group">
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                    <textarea id="description" name="description" rows="4"
                                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200"
                                              placeholder="Describe what this category contains...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="group">
                                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">URL Slug</label>
                                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200"
                                           placeholder="auto-generated-from-name">
                                    <p class="text-gray-500 text-xs mt-2">Leave empty to auto-generate from category name</p>
                                    @error('slug')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Styling & Settings -->
                <div class="space-y-6">
                    <!-- Visual Styling -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-palette mr-2" style="color: #FEEA77;"></i>
                                        Visual Styling
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Customize appearance and icon</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-star mr-1"></i>
                                    Optional
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-6">
                                <!-- Color Picker -->
                                <div>
                                    <label for="color" class="block text-sm font-semibold text-gray-700 mb-3">Category Color</label>
                                    <div class="flex items-center space-x-4">
                                        <input type="color" id="color" name="color" value="{{ old('color', '#f97316') }}"
                                               class="w-16 h-12 border border-gray-200 rounded-xl cursor-pointer">
                                        <div class="flex-1">
                                            <input type="text" id="color-text" value="{{ old('color', '#f97316') }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent"
                                                   placeholder="#f97316">
                                        </div>
                                    </div>
                                    @error('color')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Icon Selection -->
                                <div>
                                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-3">Category Icon</label>
                                    <div class="grid grid-cols-6 gap-3 bg-amber-50 rounded-xl p-4 max-h-40 overflow-y-auto">
                                        @php
                                            $icons = ['fas fa-folder', 'fas fa-camera', 'fas fa-image', 'fas fa-heart', 'fas fa-star', 'fas fa-sun', 'fas fa-moon', 'fas fa-tree', 'fas fa-car', 'fas fa-home', 'fas fa-user', 'fas fa-users'];
                                        @endphp
                                        @foreach($icons as $icon)
                                            <label class="flex items-center justify-center w-12 h-12 bg-white rounded-lg border-2 border-transparent hover:border-amber-300 cursor-pointer transition-colors">
                                                <input type="radio" name="icon" value="{{ $icon }}" class="hidden" {{ old('icon', 'fas fa-folder') === $icon ? 'checked' : '' }}>
                                                <i class="{{ $icon }} text-lg text-amber-600"></i>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('icon')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-cog mr-2" style="color: #FEEA77;"></i>
                                        Category Settings
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Configure visibility and behavior</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-sliders-h mr-1"></i>
                                    Options
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-6">
                                <!-- Active Status -->
                                <div class="flex items-start space-x-4 bg-green-50 p-4 rounded-xl border border-green-200">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="mt-1 rounded bg-white border-green-300 text-green-600 focus:ring-green-500 focus:ring-2">
                                    <div class="flex-1">
                                        <label for="is_active" class="text-gray-800 font-semibold cursor-pointer">
                                            <i class="fas fa-eye text-green-500 mr-2"></i>Active & Visible
                                        </label>
                                        <p class="text-sm text-gray-600 mt-1">Make this category visible to all gallery visitors</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                style="background: #A3D5FF; color: #1C1C1C;">
                            <i class="fas fa-plus mr-2"></i>
                            Create Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg bg-gray-200 hover:bg-gray-300 text-gray-700">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color-text');
    const iconRadios = document.querySelectorAll('input[name="icon"]');

    // Auto-generate slug from name
    nameInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });

    // Mark slug as manually edited
    slugInput.addEventListener('input', function() {
        slugInput.dataset.manual = 'true';
    });

    // Sync color picker and text input
    colorInput.addEventListener('change', function() {
        colorTextInput.value = this.value;
    });

    colorTextInput.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            colorInput.value = this.value;
        }
    });

    // Icon selection styling
    iconRadios.forEach(radio => {
        const label = radio.closest('label');
        
        radio.addEventListener('change', function() {
            // Remove selection from all labels
            iconRadios.forEach(r => {
                r.closest('label').classList.remove('border-amber-400', 'bg-amber-100');
            });
            
            // Add selection to current label
            if (this.checked) {
                label.classList.add('border-amber-400', 'bg-amber-100');
            }
        });

        // Set initial state
        if (radio.checked) {
            label.classList.add('border-amber-400', 'bg-amber-100');
        }
    });
});
</script>
@endpush
@endsection
