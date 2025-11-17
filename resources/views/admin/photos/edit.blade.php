@extends('layouts.admin')

@section('title', 'Edit Photo')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Photo</h1>
                <p class="text-gray-600 mt-2">Update photo details and settings</p>
            </div>
            <a href="{{ route('admin.photos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Photos
            </a>
        </div>
    </div>

    <!-- Current Photo Preview -->
    <div class="bg-gradient-to-br from-coral-50 to-sky-50 rounded-lg shadow-md p-6 mb-6 border-2 border-coral-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-image mr-2 text-coral-500"></i>
                Current Photo
            </h3>
            <span class="bg-coral-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                ORIGINAL
            </span>
        </div>
        <div class="flex justify-center">
            <div class="relative inline-block">
                <img src="{{ $photo->url }}" 
                     alt="{{ $photo->title }}" 
                     class="max-w-md rounded-lg shadow-xl border-4 border-white"
                     onerror="this.onerror=null; this.src='{{ $photo->thumbnail_url }}';">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 rounded-b-lg">
                    <p class="text-white text-sm font-semibold">{{ $photo->filename }}</p>
                    <p class="text-white/80 text-xs">{{ $photo->width }} x {{ $photo->height }} px</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('admin.photos.update', $photo) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Photo Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $photo->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter photo title">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Describe your photo...">{{ old('description', $photo->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Replace Image (Optional) -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-image mr-2 text-coral-500"></i>
                        Replace Image (Optional)
                    </label>
                    
                    <!-- File Input with custom styling -->
                    <div class="relative">
                        <input type="file" name="image" id="imageInput" accept="image/*"
                               class="hidden"
                               onchange="previewNewImage(event)">
                        <label for="imageInput" 
                               class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-coral-500 hover:bg-coral-50 transition-all duration-300">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold text-coral-500">Click to upload</span> new photo
                                </p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Preview New Image -->
                    <div id="newImagePreview" class="hidden mt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">New Image Preview:</p>
                        <div class="relative inline-block">
                            <img id="previewImage" src="" alt="Preview" class="max-w-xs rounded-lg shadow-lg border-2 border-coral-500">
                            <button type="button" onclick="clearImagePreview()" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-green-600 mt-2">
                            <i class="fas fa-check-circle mr-1"></i>
                            New image selected! Click "Update Photo" to save changes.
                        </p>
                    </div>
                    
                    <p class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Leave empty to keep current image
                    </p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Category -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $photo->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tags</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3">
                        @foreach($tags as $tag)
                            <label class="flex items-center hover:bg-gray-50 p-2 rounded cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                       {{ $photo->tags->contains($tag->id) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Check multiple tags</p>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Status</label>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $photo->is_featured) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Featured Photo</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $photo->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active (Visible to public)</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update Photo
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .text-coral-500 { color: #FF6F61; }
    .bg-coral-50 { background-color: #ffebe9; }
    .bg-coral-500 { background-color: #FF6F61; }
    .border-coral-200 { border-color: #ffccc7; }
    .border-coral-500 { border-color: #FF6F61; }
    .hover\:border-coral-500:hover { border-color: #FF6F61; }
    .hover\:bg-coral-50:hover { background-color: #ffebe9; }
    .from-coral-50 { --tw-gradient-from: #ffebe9; }
    .to-sky-50 { --tw-gradient-to: #e8f4ff; }
</style>
@endpush

@push('scripts')
<script>
    // Preview new image before upload
    function previewNewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('newImagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Clear image preview
    function clearImagePreview() {
        document.getElementById('imageInput').value = '';
        document.getElementById('newImagePreview').classList.add('hidden');
        document.getElementById('previewImage').src = '';
    }
    
    // Show confirmation before submitting if image is changed
    document.querySelector('form').addEventListener('submit', function(e) {
        const imageInput = document.getElementById('imageInput');
        if (imageInput.files.length > 0) {
            if (!confirm('Are you sure you want to replace the current photo? This action cannot be undone.')) {
                e.preventDefault();
            }
        }
    });
</script>
@endpush
@endsection

