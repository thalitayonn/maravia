@extends('layouts.admin')

@section('title', 'View Photo')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 relative z-10">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $photo->title }}</h1>
                <p class="text-gray-600 mt-2">Photo Details & Preview</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.photos.edit', $photo) }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.photos.index') }}" 
                   class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Photo Preview - Large -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="{{ url('/api/photos/' . $photo->id . '/image') }}" 
                     alt="{{ $photo->title }}" 
                     class="w-full h-auto"
                     onerror="this.onerror=null; this.src='{{ $photo->url }}'; console.log('Admin view API failed for photo:', {{ $photo->id }});"
                     onload="console.log('Admin photo loaded via API:', {{ $photo->id }});">
            </div>
            
            <!-- Description -->
            @if($photo->description)
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="text-lg font-semibold mb-3">Description</h3>
                <p class="text-gray-700 leading-relaxed">{{ $photo->description }}</p>
            </div>
            @endif
        </div>

        <!-- Photo Details - Sidebar -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Photo Information</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Category</span>
                        <span class="font-semibold text-gray-900">{{ $photo->category->name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Status</span>
                        @if($photo->is_active)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Inactive</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Featured</span>
                        @if($photo->is_featured)
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-star mr-1"></i>Yes
                            </span>
                        @else
                            <span class="text-gray-500 text-sm">No</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Views</span>
                        <span class="font-semibold text-gray-900">
                            <i class="fas fa-eye mr-1 text-blue-500"></i>
                            {{ number_format($photo->view_count) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">File Size</span>
                        <span class="font-semibold text-gray-900">{{ $photo->file_size_human }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Dimensions</span>
                        <span class="font-semibold text-gray-900">{{ $photo->width }} × {{ $photo->height }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Uploaded</span>
                        <span class="text-gray-900 text-sm">{{ $photo->created_at->format('d M Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600 text-sm">Uploaded By</span>
                        <span class="text-gray-900 text-sm">{{ $photo->uploader->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            @if($photo->tags->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($photo->tags as $tag)
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ $photo->url }}" target="_blank" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View Full Size
                    </a>
                    
                    <a href="{{ $photo->url }}" download class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </a>
                    
                    <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Photo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styling for better photo preview */
    .bg-white {
        background-color: #ffffff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Foto?',
            text: "Foto ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6 py-3 font-semibold',
                cancelButton: 'rounded-lg px-6 py-3 font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush
