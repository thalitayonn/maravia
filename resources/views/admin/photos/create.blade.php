@extends('layouts.admin')

@section('title', 'Add New Photo')

@section('content')
<div class="flex-1 overflow-auto">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20 sticky top-0 z-10">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Add New Photo</h1>
                    <p class="text-blue-200 mt-1">Upload and organize photos for the gallery</p>
                </div>
                <a href="{{ route('admin.photos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Photos
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.photos.store') }}" enctype="multipart/form-data" id="photo-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Photo Upload -->
                <div class="space-y-6">
                    <!-- Drag & Drop Upload Area -->
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <h3 class="text-lg font-semibold text-white mb-4">Photo Upload</h3>
                        
                        <div id="drop-zone" class="relative border-2 border-dashed border-white/30 rounded-xl p-8 text-center hover:border-blue-400 transition-colors cursor-pointer">
                            <input type="file" id="photo-input" name="image" accept="image/*" class="hidden" required>
                            
                            <div id="upload-placeholder" class="space-y-4">
                                <div class="mx-auto w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-2">Drop your photo here or click to browse</p>
                                    <p class="text-blue-200 text-sm">Supports: JPG, PNG, GIF (Max: 10MB)</p>
                                </div>
                            </div>

                            <!-- Preview Area -->
                            <div id="preview-area" class="hidden">
                                <div class="relative">
                                    <img id="preview-image" src="" alt="Preview" class="max-w-full max-h-64 mx-auto rounded-lg shadow-lg">
                                    <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                <div id="file-info" class="mt-4 text-sm text-blue-200 space-y-1">
                                    <p><span class="font-medium">File:</span> <span id="file-name"></span></p>
                                    <p><span class="font-medium">Size:</span> <span id="file-size"></span></p>
                                    <p><span class="font-medium">Dimensions:</span> <span id="file-dimensions"></span></p>
                                </div>
                            </div>

                            <!-- Upload Progress -->
                            <div id="upload-progress" class="hidden mt-4">
                                <div class="bg-white/10 rounded-full h-2">
                                    <div id="progress-bar" class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p class="text-blue-200 text-sm mt-2">Processing image...</p>
                            </div>
                        </div>

                        @error('image')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Multiple Upload Option -->
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <h3 class="text-lg font-semibold text-white mb-4">Bulk Upload</h3>
                        <div class="space-y-4">
                            <input type="file" id="bulk-upload" multiple accept="image/*" class="hidden">
                            <button type="button" onclick="document.getElementById('bulk-upload').click()" class="w-full bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                                Select Multiple Photos
                            </button>
                            <p class="text-blue-200 text-sm">Upload multiple photos at once with the same settings</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Photo Details -->
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <h3 class="text-lg font-semibold text-white mb-4">Photo Details</h3>
                        
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-white mb-2">Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Enter photo title">
                                @error('title')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                          class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Enter photo description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-white mb-2">Category *</label>
                                <select id="category_id" name="category_id" required
                                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div>
                                <label for="tags" class="block text-sm font-medium text-white mb-2">Tags</label>
                                <div class="space-y-2">
                                    <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto">
                                        @foreach($tags as $tag)
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                       {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                                       class="rounded bg-white/10 border-white/20 text-blue-600 focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-white">{{ $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @error('tags')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <h3 class="text-lg font-semibold text-white mb-4">Settings</h3>
                        
                        <div class="space-y-4">
                            <!-- Featured -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="rounded bg-white/10 border-white/20 text-blue-600 focus:ring-blue-500">
                                <label for="is_featured" class="ml-3 text-white">
                                    <span class="font-medium">Featured Photo</span>
                                    <p class="text-sm text-blue-200">Display this photo prominently on the homepage</p>
                                </label>
                            </div>

                            <!-- Active -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded bg-white/10 border-white/20 text-blue-600 focus:ring-blue-500">
                                <label for="is_active" class="ml-3 text-white">
                                    <span class="font-medium">Active</span>
                                    <p class="text-sm text-blue-200">Make this photo visible to visitors</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                            Upload Photo
                        </button>
                        <a href="{{ route('admin.photos.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold text-center transition-colors">
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
    const dropZone = document.getElementById('drop-zone');
    const photoInput = document.getElementById('photo-input');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewArea = document.getElementById('preview-area');
    const previewImage = document.getElementById('preview-image');
    const removeImageBtn = document.getElementById('remove-image');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileDimensions = document.getElementById('file-dimensions');
    const titleInput = document.getElementById('title');
    const bulkUpload = document.getElementById('bulk-upload');

    // Drag and drop functionality
    dropZone.addEventListener('click', () => photoInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-500/5');
    });
    
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-500/5');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-500/5');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });

    photoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    removeImageBtn.addEventListener('click', () => {
        photoInput.value = '';
        showUploadPlaceholder();
    });

    // Bulk upload handling
    bulkUpload.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleBulkUpload(e.target.files);
        }
    });

    function handleFileSelect(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }

        if (file.size > 10 * 1024 * 1024) { // 10MB
            alert('File size must be less than 10MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            showPreview(file);
            
            // Auto-generate title from filename if empty
            if (!titleInput.value) {
                const name = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
                titleInput.value = name.charAt(0).toUpperCase() + name.slice(1);
            }
        };
        reader.readAsDataURL(file);
    }

    function showPreview(file) {
        uploadPlaceholder.classList.add('hidden');
        previewArea.classList.remove('hidden');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Get image dimensions
        const img = new Image();
        img.onload = () => {
            fileDimensions.textContent = `${img.width} × ${img.height}px`;
        };
        img.src = previewImage.src;
    }

    function showUploadPlaceholder() {
        uploadPlaceholder.classList.remove('hidden');
        previewArea.classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function handleBulkUpload(files) {
        if (files.length === 0) return;
        
        const formData = new FormData();
        const categoryId = document.getElementById('category_id').value;
        const isActive = document.getElementById('is_active').checked;
        const isFeatured = document.getElementById('is_featured').checked;
        
        if (!categoryId) {
            alert('Please select a category first.');
            return;
        }

        // Show confirmation
        if (!confirm(`Upload ${files.length} photos with current settings?`)) {
            return;
        }

        // Process each file
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024) {
                const fileName = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
                const title = fileName.charAt(0).toUpperCase() + fileName.slice(1);
                
                // Create individual form for each photo
                setTimeout(() => {
                    uploadSinglePhoto(file, title, categoryId, isActive, isFeatured);
                }, index * 500); // Stagger uploads
            }
        });
    }

    function uploadSinglePhoto(file, title, categoryId, isActive, isFeatured) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('title', title);
        formData.append('category_id', categoryId);
        formData.append('is_active', isActive ? '1' : '0');
        formData.append('is_featured', isFeatured ? '1' : '0');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch('{{ route("admin.photos.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Upload successful:', title);
        })
        .catch(error => {
            console.error('Upload failed:', title, error);
        });
    }

    // Form validation
    document.getElementById('photo-form').addEventListener('submit', function(e) {
        if (!photoInput.files.length) {
            e.preventDefault();
            alert('Please select a photo to upload.');
            return;
        }
        
        if (!document.getElementById('title').value.trim()) {
            e.preventDefault();
            alert('Please enter a title for the photo.');
            return;
        }
        
        if (!document.getElementById('category_id').value) {
            e.preventDefault();
            alert('Please select a category.');
            return;
        }
    });
});
</script>
@endpush
@endsection
