@extends('layouts.admin')

@section('title', 'Edit Tag')

@section('content')
<div class="flex-1 overflow-auto modern-content-bg">
    <!-- Header -->
    <div class="modern-header">
        <div class="px-6 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold modern-text-primary mb-2">Edit Tag</h1>
                    <p class="modern-text-secondary">Update tag information</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.tags.index') }}" class="bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Tags
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="p-8">
        <!-- Soft Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl mb-12">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-100 via-pink-50 to-indigo-100"></div>
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                <div class="absolute top-0 right-1/4 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
                <div class="absolute -bottom-8 left-1/3 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
            </div>
            <div class="relative z-10 p-8">
                <div class="backdrop-blur-sm bg-white/60 rounded-2xl p-6 border border-white/40">
                    <h2 class="text-3xl font-bold text-gray-700 mb-3 tracking-wide">
                        <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                            Edit Tag: {{ $tag->name }}
                        </span>
                        <span class="ml-3 text-2xl">🏷️</span>
                    </h2>
                    <div class="bg-white/50 rounded-xl p-4 mt-4 border border-gray-200">
                        <p class="text-gray-600 text-lg mb-3">Update tag details and organize your photo collection</p>
                        <div class="flex items-center space-x-8 text-sm text-gray-500">
                            <div class="flex items-center bg-white/60 px-3 py-2 rounded-lg">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-2 animate-pulse"></div>
                                <i class="fas fa-edit mr-2"></i>
                                Edit Mode
                            </div>
                            <div class="flex items-center bg-white/60 px-3 py-2 rounded-lg">
                                <div class="w-2 h-2 bg-pink-400 rounded-full mr-2 animate-pulse"></div>
                                <i class="fas fa-images mr-2"></i>
                                {{ $tag->photos_count ?? 0 }} Photos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-purple-50">
            <div class="modern-card-header border-b border-purple-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="modern-card-title text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-purple-400 rounded-full mr-3"></div>
                            Tag Information
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Update the tag details below</p>
                    </div>
                    <div class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Form
                    </div>
                </div>
            </div>

            <div class="modern-card-content pt-8">
                <form action="{{ route('admin.tags.update', $tag) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tag Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-tag mr-2 text-purple-400"></i>
                            Tag Name
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $tag->name) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-300 @error('name') border-red-300 ring-2 ring-red-200 @enderror"
                               placeholder="Enter tag name"
                               required>
                        @error('name')
                            <div class="flex items-center mt-2 text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tag Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-align-left mr-2 text-purple-400"></i>
                            Description
                            <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-300 @error('description') border-red-300 ring-2 ring-red-200 @enderror"
                                  placeholder="Enter tag description">{{ old('description', $tag->description) }}</textarea>
                        @error('description')
                            <div class="flex items-center mt-2 text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tag Color -->
                    <div class="space-y-2">
                        <label for="color" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-palette mr-2 text-purple-400"></i>
                            Tag Color
                        </label>
                        <div class="flex items-center space-x-4">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color', $tag->color ?? '#8B5CF6') }}"
                                   class="w-16 h-12 border border-gray-200 rounded-xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-300 transition-all duration-300">
                            <div class="flex-1">
                                <input type="text" 
                                       id="color-hex" 
                                       value="{{ old('color', $tag->color ?? '#8B5CF6') }}"
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-300"
                                       placeholder="#8B5CF6"
                                       readonly>
                            </div>
                        </div>
                        @error('color')
                            <div class="flex items-center mt-2 text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-toggle-on mr-2 text-purple-400"></i>
                            Status
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $tag->is_active) ? 'checked' : '' }}
                                       class="sr-only">
                                <div class="relative">
                                    <div class="block bg-gray-300 w-14 h-8 rounded-full transition-colors duration-300"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300"></div>
                                </div>
                                <span class="ml-3 text-gray-700">Active</span>
                            </label>
                        </div>
                        <p class="text-gray-500 text-sm">Inactive tags won't be visible to users</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                        <a href="{{ route('admin.tags.index') }}" 
                           class="bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        
                        <div class="flex space-x-4">
                            <button type="button" 
                                    onclick="resetForm()"
                                    class="bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            
                            <button type="submit" 
                                    class="bg-gradient-to-r from-purple-400 to-pink-400 hover:from-purple-500 hover:to-pink-500 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Update Tag
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tag Statistics (if available) -->
        @if(isset($tag->photos_count))
        <div class="mt-8 modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-indigo-50">
            <div class="modern-card-header border-b border-indigo-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="modern-card-title text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-indigo-400 rounded-full mr-3"></div>
                            Tag Statistics
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Usage information for this tag</p>
                    </div>
                    <div class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Statistics
                    </div>
                </div>
            </div>

            <div class="modern-card-content pt-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-images text-white text-2xl"></i>
                        </div>
                        <h4 class="text-gray-800 font-bold text-lg">{{ $tag->photos_count ?? 0 }}</h4>
                        <p class="text-gray-600 text-sm">Total Photos</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-calendar text-white text-2xl"></i>
                        </div>
                        <h4 class="text-gray-800 font-bold text-lg">{{ $tag->created_at->format('M Y') }}</h4>
                        <p class="text-gray-600 text-sm">Created</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-clock text-white text-2xl"></i>
                        </div>
                        <h4 class="text-gray-800 font-bold text-lg">{{ $tag->updated_at->diffForHumans() }}</h4>
                        <p class="text-gray-600 text-sm">Last Updated</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Color picker sync
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('color-hex').value = this.value;
});

// Toggle switch styling
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    toggles.forEach(toggle => {
        const updateToggle = () => {
            const wrapper = toggle.nextElementSibling;
            const bg = wrapper.querySelector('div:first-child');
            const dot = wrapper.querySelector('.dot');
            
            if (toggle.checked) {
                bg.classList.remove('bg-gray-300');
                bg.classList.add('bg-purple-400');
                dot.style.transform = 'translateX(24px)';
            } else {
                bg.classList.remove('bg-purple-400');
                bg.classList.add('bg-gray-300');
                dot.style.transform = 'translateX(0)';
            }
        };
        
        updateToggle();
        toggle.addEventListener('change', updateToggle);
    });
});

// Reset form function
function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        document.querySelector('form').reset();
        // Trigger toggle update
        document.querySelectorAll('input[type="checkbox"]').forEach(toggle => {
            toggle.dispatchEvent(new Event('change'));
        });
    }
}
</script>
@endpush
@endsection
