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

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Monitor and moderate user interactions on your photos</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-comments mr-2" style="color: #FEEA77;"></i>
                        {{ $stats['total'] ?? 0 }} Comments
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-shield-alt mr-2" style="color: #FEEA77;"></i>
                        Smart Moderation
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50 min-h-[120px]">
                <div class="modern-card-content p-6">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Total Comments</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-yellow-50 min-h-[120px]">
                <div class="modern-card-content p-6">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Pending</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-amber-50 min-h-[120px]">
                <div class="modern-card-content p-6">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Approved</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['approved'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-red-50 min-h-[120px]">
                <div class="modern-card-content p-6">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Rejected</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['rejected'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-times-circle text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50 min-h-[120px]">
                <div class="modern-card-content p-6">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Spam</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['spam'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
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
                            Search & Filter Comments
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Find and moderate comments efficiently</p>
                    </div>
                    <div class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-search mr-1"></i>
                        Smart Filter
                    </div>
                </div>
            </div>
            <div class="modern-card-content pt-8">
                <form method="GET" action="{{ route('admin.comments.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Search -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Comments</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search by content, name, email..." 
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
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-500 hover:to-orange-500 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.comments.index') }}" class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-700 px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Comments List -->
        @if(isset($comments) && $comments->count() > 0)
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
                <div class="modern-card-header border-b border-orange-100 pb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="modern-card-title text-xl font-bold flex items-center">
                                <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                                All Comments
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Manage user comments and interactions</p>
                        </div>
                    </div>
                </div>

                <div class="modern-card-content pt-8">
                    <div class="space-y-6">
                        @foreach($comments as $comment)
                            <div class="comment-item group relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-200 to-amber-200 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                                <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-6 border border-orange-100 hover:border-orange-200 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                                <span class="text-white font-bold text-lg">{{ substr($comment->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="text-gray-800 font-bold text-lg">{{ $comment->name }}</h4>
                                                <p class="text-gray-500 text-sm">{{ $comment->email }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-gradient-to-r from-yellow-400 to-orange-400 text-white',
                                                    'approved' => 'bg-gradient-to-r from-amber-400 to-yellow-400 text-white',
                                                    'rejected' => 'bg-gradient-to-r from-red-400 to-pink-400 text-white',
                                                    'spam' => 'bg-gradient-to-r from-orange-400 to-red-400 text-white'
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-medium shadow-lg {{ $statusColors[$comment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($comment->status) }}
                                            </span>
                                            @if($comment->is_spam ?? false)
                                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-400 to-pink-400 text-white shadow-lg">
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
                                                <span class="flex items-center bg-orange-100 px-3 py-1 rounded-lg">
                                                    <i class="fas fa-image mr-2 text-orange-500"></i>
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
                                                <button onclick="approveComment({{ $comment->id }})" class="bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                                <button onclick="rejectComment({{ $comment->id }})" class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Reject
                                                </button>
                                            @endif
                                            <button onclick="markSpam({{ $comment->id }})" class="bg-gradient-to-r from-orange-400 to-red-400 hover:from-orange-500 hover:to-red-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Spam
                                            </button>
                                            <button onclick="deleteComment({{ $comment->id }})" class="bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
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
                <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-comments text-6xl text-orange-400"></i>
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
    if (confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/comments/${commentId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
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
