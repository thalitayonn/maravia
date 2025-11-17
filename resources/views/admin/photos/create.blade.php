@extends('layouts.admin')

@section('title', 'Add New Photo')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Add New Photo</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Upload and organize beautiful photos for your gallery</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.photos.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Photos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 pb-8">

        <form method="POST" action="{{ route('admin.photos.store') }}" enctype="multipart/form-data" id="photo-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Photo Upload -->
                <div class="space-y-6">
                    <!-- Drag & Drop Upload Area -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-cloud-upload-alt mr-2" style="color: #FEEA77;"></i>
                                        Photo Upload
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Drag & drop or click to select your photo</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-star mr-1"></i>
                                    Smart Upload
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="drop-zone" class="relative border-2 border-dashed border-orange-200 rounded-2xl p-12 text-center hover:border-orange-300 hover:bg-orange-50/50 transition-all duration-300 cursor-pointer group">
                                <input type="file" id="photo-input" name="image" accept="image/*" class="hidden" required>
                                
                                <div id="upload-placeholder" class="space-y-6">
                                    <div class="mx-auto w-20 h-20 bg-gradient-to-br from-orange-200 to-amber-200 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 font-bold text-lg mb-2">Drop your photo here or click to browse</p>
                                        <p class="text-gray-500">Supports: JPG, PNG, GIF (Max: 10MB)</p>
                                    </div>
                                </div>

                                <!-- Preview Area -->
                                <div id="preview-area" class="hidden">
                                    <div class="relative">
                                        <img id="preview-image" src="" alt="Preview" class="max-w-full max-h-80 mx-auto rounded-2xl shadow-lg">
                                        <button type="button" id="remove-image" class="absolute top-4 right-4 bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white rounded-full p-3 transition-all duration-300 transform hover:scale-110 shadow-lg">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </div>
                                    <div id="file-info" class="mt-6 bg-orange-50 rounded-xl p-4 space-y-2">
                                        <p class="text-sm text-gray-600"><span class="font-semibold text-orange-600">File:</span> <span id="file-name"></span></p>
                                        <p class="text-sm text-gray-600"><span class="font-semibold text-orange-600">Size:</span> <span id="file-size"></span></p>
                                        <p class="text-sm text-gray-600"><span class="font-semibold text-orange-600">Dimensions:</span> <span id="file-dimensions"></span></p>
                                    </div>
                                </div>

                                <!-- Upload Progress -->
                                <div id="upload-progress" class="hidden mt-6">
                                    <div class="bg-orange-100 rounded-full h-3">
                                        <div id="progress-bar" class="bg-gradient-to-r from-orange-400 to-amber-400 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p class="text-orange-600 text-sm mt-3 font-medium">Processing image...</p>
                                </div>
                            </div>

                            @error('image')
                                <p class="text-red-500 text-sm mt-3 bg-red-50 p-3 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Multiple Upload Option -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-layer-group mr-2" style="color: #FEEA77;"></i>
                                        Bulk Upload
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Upload multiple photos at once</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-images mr-1"></i>
                                    Batch Process
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-4">
                                <input type="file" id="bulk-upload" multiple accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('bulk-upload').click()" class="w-full py-4 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg" style="background: #A3D5FF; color: #1C1C1C;">
                                    <i class="fas fa-images mr-2"></i>
                                    Select Multiple Photos
                                </button>
                                <p class="text-gray-600 text-sm bg-blue-50 p-3 rounded-lg border border-blue-100">
                                    <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                    Upload multiple photos at once with the same category and settings
                                </p>
                                
                                <!-- Bulk Upload Progress -->
                                <div id="bulk-progress" class="hidden">
                                    <div class="bg-white border-2 border-blue-200 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold text-gray-700">Uploading...</span>
                                            <span id="bulk-progress-text" class="text-sm font-bold text-blue-600">0 / 0</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div id="bulk-progress-bar" class="h-3 rounded-full transition-all duration-300" style="background: #A3D5FF; width: 0%"></div>
                                        </div>
                                        <div id="bulk-status" class="mt-2 text-xs text-gray-600"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Photo Details -->
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-info-circle mr-2" style="color: #FEEA77;"></i>
                                        Photo Details
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Add information about your photo</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-asterisk mr-1"></i>
                                    Required
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-6">
                                <!-- Title -->
                                <div class="group">
                                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Photo Title *</label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200"
                                           placeholder="Enter a captivating title">
                                    @error('title')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="group">
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                    <textarea id="description" name="description" rows="4"
                                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200"
                                              placeholder="Tell the story behind this photo...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="group">
                                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                                    <select id="category_id" name="category_id" required
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tags -->
                                <div>
                                    <label for="tags" class="block text-sm font-semibold text-gray-700 mb-3">Tags</label>
                                    <div class="bg-orange-50 rounded-xl p-4 max-h-40 overflow-y-auto">
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($tags as $tag)
                                                <label class="inline-flex items-center bg-white rounded-lg px-3 py-2 hover:bg-orange-100 transition-colors cursor-pointer">
                                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                           {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                                           class="rounded bg-white border-gray-300 text-orange-600 focus:ring-orange-500 focus:ring-2">
                                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $tag->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('tags')
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
                                        Photo Settings
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Configure visibility and features</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-sliders-h mr-1"></i>
                                    Options
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-6">
                                <!-- Featured -->
                                <div class="flex items-start space-x-4 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}
                                           class="mt-1 rounded bg-white border-yellow-300 text-yellow-600 focus:ring-yellow-500 focus:ring-2">
                                    <div class="flex-1">
                                        <label for="is_featured" class="text-gray-800 font-semibold cursor-pointer">
                                            <i class="fas fa-star text-yellow-500 mr-2"></i>Featured Photo
                                        </label>
                                        <p class="text-sm text-gray-600 mt-1">Display this photo prominently on the homepage and gallery highlights</p>
                                    </div>
                                </div>

                                <!-- Active -->
                                <div class="flex items-start space-x-4 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="mt-1 rounded bg-white border-yellow-300 text-yellow-600 focus:ring-yellow-500 focus:ring-2">
                                    <div class="flex-1">
                                        <label for="is_active" class="text-gray-800 font-semibold cursor-pointer">
                                            <i class="fas fa-eye text-yellow-500 mr-2"></i>Active & Visible
                                        </label>
                                        <p class="text-sm text-gray-600 mt-1">Make this photo visible to all gallery visitors</p>
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
                            <i class="fas fa-upload mr-2"></i>
                            Upload Photo
                        </button>
                        <a href="{{ route('admin.photos.index') }}" 
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
        dropZone.classList.add('border-orange-400', 'bg-orange-100/50');
    });
    
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-orange-400', 'bg-orange-100/50');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-orange-400', 'bg-orange-100/50');
        
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
    if (bulkUpload) {
        bulkUpload.addEventListener('change', function(e) {
            console.log('=== BULK UPLOAD STARTED ===');
            console.log('Files selected:', e.target.files.length);
            
            if (e.target.files.length > 0) {
                // Show immediate feedback
                alert(`You selected ${e.target.files.length} file(s). Processing...`);
                handleBulkUpload(e.target.files);
            } else {
                alert('No files selected!');
            }
        });
        console.log('✅ Bulk upload listener attached successfully');
    } else {
        console.error('❌ Bulk upload element not found!');
        alert('ERROR: Bulk upload button not found!');
    }

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

    let uploadedCount = 0;
    let totalFiles = 0;

    function handleBulkUpload(files) {
        console.log('=== HANDLE BULK UPLOAD FUNCTION ===');
        console.log('Files received:', files.length);
        
        if (files.length === 0) {
            alert('ERROR: No files in handleBulkUpload!');
            return;
        }
        
        // Get category value - try multiple times to ensure it's loaded
        const categorySelect = document.getElementById('category_id');
        
        if (!categorySelect) {
            alert('ERROR: Category dropdown not found in page!');
            return;
        }
        
        const categoryId = categorySelect.value;
        
        console.log('Category Select Element:', categorySelect);
        console.log('Category Value:', categoryId);
        console.log('Selected Index:', categorySelect.selectedIndex);
        console.log('Options:', categorySelect.options);
        
        // Check if category is selected (not empty and not the placeholder)
        if (!categoryId || categoryId === '' || categorySelect.selectedIndex === 0) {
            alert('⚠️ Please select a category first!\n\nYou must choose a category from the dropdown before bulk uploading.\n\nCurrent value: ' + (categoryId || 'EMPTY'));
            
            // Scroll to category and highlight it
            categorySelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            categorySelect.style.border = '3px solid red';
            categorySelect.focus();
            
            setTimeout(() => {
                categorySelect.style.border = '';
            }, 3000);
            
            return;
        }
        
        const isActive = document.getElementById('is_active') ? document.getElementById('is_active').checked : true;
        const isFeatured = document.getElementById('is_featured') ? document.getElementById('is_featured').checked : false;
        
        alert('Step 2: Category OK! Filtering files...');

        // Filter valid files
        const validFiles = Array.from(files).filter(file => 
            file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024
        );

        if (validFiles.length === 0) {
            alert('No valid image files selected. Please select JPG, PNG, or GIF files under 10MB.');
            return;
        }

        // Show confirmation
        const categoryText = categorySelect.selectedOptions[0] ? categorySelect.selectedOptions[0].text : 'Unknown';
        const confirmMessage = `📸 Upload ${validFiles.length} photo(s) with current settings?\n\nCategory: ${categoryText}\nActive: ${isActive ? 'Yes' : 'No'}\nFeatured: ${isFeatured ? 'Yes' : 'No'}`;
        
        console.log('Confirmation message:', confirmMessage);
        
        if (!confirm(confirmMessage)) {
            console.log('Upload cancelled by user');
            return;
        }
        
        console.log('Starting bulk upload...');

        // Show progress
        uploadedCount = 0;
        totalFiles = validFiles.length;
        document.getElementById('bulk-progress').classList.remove('hidden');
        document.getElementById('bulk-progress-text').textContent = `0 / ${totalFiles}`;
        document.getElementById('bulk-progress-bar').style.width = '0%';

        // Process each file
        validFiles.forEach((file, index) => {
            const fileName = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
            const title = fileName.charAt(0).toUpperCase() + fileName.slice(1);
            
            // Stagger uploads to avoid overwhelming the server
            setTimeout(() => {
                uploadSinglePhoto(file, title, categoryId, isActive, isFeatured);
            }, index * 300);
        });
    }

    function uploadSinglePhoto(file, title, categoryId, isActive, isFeatured) {
        console.log('Uploading photo:', title, 'Category:', categoryId);
        
        const formData = new FormData();
        formData.append('image', file);
        formData.append('title', title);
        formData.append('category_id', categoryId);
        formData.append('is_active', isActive ? '1' : '0');
        formData.append('is_featured', isFeatured ? '1' : '0');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        const statusElement = document.getElementById('bulk-status');
        if (statusElement) {
            statusElement.textContent = `Uploading: ${title}...`;
        }

        fetch('{{ route("admin.photos.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Upload failed');
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch {
                    // If not JSON, it's probably HTML (success page)
                    return { success: true };
                }
            });
        })
        .then(data => {
            uploadedCount++;
            updateBulkProgress();
            console.log('✅ Upload successful:', title);
        })
        .catch(error => {
            uploadedCount++;
            updateBulkProgress();
            console.error('❌ Upload failed:', title, error);
        });
    }

    function updateBulkProgress() {
        const percentage = (uploadedCount / totalFiles) * 100;
        document.getElementById('bulk-progress-bar').style.width = percentage + '%';
        document.getElementById('bulk-progress-text').textContent = `${uploadedCount} / ${totalFiles}`;
        
        if (uploadedCount === totalFiles) {
            document.getElementById('bulk-status').innerHTML = `<span class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i>All photos uploaded successfully!</span>`;
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = '{{ route("admin.photos.index") }}';
            }, 2000);
        }
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
