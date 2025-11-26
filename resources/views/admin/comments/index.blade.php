@extends('layouts.admin')

@section('title', 'Comments Management')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Comments Management</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Moderate and manage photo comments</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="autoModerate()" class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg" style="background: #FF6F61; color: white;">
                        <i class="fas fa-robot mr-2"></i>
                        Auto Moderate
                    </button>
                </div>
            </div>
        </div>
    </div>

    

        <!-- Search and Filters -->
        <div class="bg-white border border-gray-200 rounded-2xl">
            <div class="border-b border-gray-200 pb-6 px-6 pt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                            Search & Filter Comments
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Find and moderate comments efficiently</p>
                    </div>
                    <div class="text-gray-600 text-sm px-3 py-1 rounded">Smart Filter</div>
                </div>
            </div>
            <div class="px-6 pb-6 pt-6">
                <form method="GET" action="{{ route('admin.comments.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Search -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Comments</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search by content, name, email..." 
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.comments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Comments List -->
        @if(isset($comments) && $comments->count() > 0)
            <div class="bg-white border border-gray-200 rounded-2xl">
                <div class="border-b border-gray-200 pb-6 px-6 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold flex items-center">
                                <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                                All Comments
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Manage user comments and interactions</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-6">
                    <div class="space-y-6">
                        @foreach($comments as $comment)
                            <div class="comment-item group relative">
                                <div class="relative bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center">
                                                <span class="font-bold text-base">{{ substr($comment->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="text-gray-800 font-bold text-lg">{{ $comment->name }}</h4>
                                                <p class="text-gray-500 text-sm">{{ $comment->email }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    'spam' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$comment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($comment->status) }}
                                            </span>
                                            @if($comment->is_spam ?? false)
                                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Spam
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                        <p class="text-gray-700 leading-relaxed">{{ $comment->comment }}</p>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <span class="flex items-center bg-gray-100 px-3 py-1 rounded-lg">
                                                    <i class="fas fa-image mr-2 text-gray-500"></i>
                                                    {{ $comment->photo->title ?? 'Unknown Photo' }}
                                                </span>
                                                <span class="flex items-center bg-gray-100 px-3 py-1 rounded-lg">
                                                    <i class="fas fa-calendar mr-2 text-gray-500"></i>
                                                    {{ $comment->created_at->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            @if($comment->status === 'pending')
                                                <button onclick="approveComment({{ $comment->id }})" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                                <button onclick="rejectComment({{ $comment->id }})" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Reject
                                                </button>
                                            @endif
                                            <button onclick="markSpam({{ $comment->id }})" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Spam
                                            </button>
                                            <button onclick="deleteComment({{ $comment->id }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
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
            @if(method_exists($comments, 'links'))
                <div class="mt-12 flex justify-center">
                    {{ $comments->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="bg-gray-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-comments text-6xl text-gray-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">No Comments Found</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Comments will appear here when visitors interact with your photos.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function approveComment(commentId) {
    fetch(`/admin/comments/${commentId}/approve`, {
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

function rejectComment(commentId) {
    fetch(`/admin/comments/${commentId}/reject`, {
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

function markSpam(commentId) {
    if (confirm('Are you sure you want to mark this comment as spam?')) {
        fetch(`/admin/comments/${commentId}/mark-spam`, {
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
}

function deleteComment(commentId) {
    if (typeof showDeleteModal === 'function') {
        showDeleteModal({
            message: 'Are you sure you want to delete this comment? This action cannot be undone.',
            action: `/admin/comments/${commentId}`,
            method: 'DELETE'
        });
    }
}

function autoModerate() {
    if (confirm('Auto-moderate all pending comments using AI? This will automatically approve or reject comments based on content analysis.')) {
        fetch('/admin/comments/auto-moderate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection
