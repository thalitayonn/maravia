@extends('layouts.admin')

@section('title', 'Manage Comments')

@section('content')
<div class="flex-1 overflow-auto">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20 sticky top-0 z-10">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Manage Comments</h1>
                    <p class="text-blue-200 mt-1">Moderate photo comments and feedback</p>
                </div>
                <button onclick="autoModerate()" class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Auto Moderate
                </button>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total</p>
                        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm">Pending</p>
                        <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Approved</p>
                        <p class="text-2xl font-bold">{{ $stats['approved'] }}</p>
                    </div>
                    <svg class="w-8 h-8 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm">Rejected</p>
                        <p class="text-2xl font-bold">{{ $stats['rejected'] }}</p>
                    </div>
                    <svg class="w-8 h-8 text-red-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-100 text-sm">Spam</p>
                        <p class="text-2xl font-bold">{{ $stats['spam'] }}</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM4 10a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 mb-6">
            <form method="GET" action="{{ route('admin.comments.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search comments..." 
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                        </select>
                    </div>

                    <!-- Photo Filter -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Photo</label>
                        <select name="photo" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Photos</option>
                            @foreach($photos as $photo)
                                <option value="{{ $photo->id }}" {{ request('photo') == $photo->id ? 'selected' : '' }}>
                                    {{ Str::limit($photo->title, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.comments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 mb-6" id="bulk-actions" style="display: none;">
            <form method="POST" action="{{ route('admin.comments.bulk-action') }}" id="bulk-form">
                @csrf
                <div class="flex items-center space-x-4">
                    <span class="text-white font-medium">With selected:</span>
                    <select name="action" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Choose action...</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                        <option value="spam">Mark as Spam</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply Action
                    </button>
                    <span class="text-blue-200" id="selected-count">0 comments selected</span>
                </div>
            </form>
        </div>

        <!-- Comments List -->
        @if($comments->count() > 0)
            <div class="space-y-4">
                @foreach($comments as $comment)
                    <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Checkbox -->
                                <input type="checkbox" name="comment_ids[]" value="{{ $comment->id }}" 
                                       class="comment-checkbox w-5 h-5 text-purple-600 bg-white/20 border-white/40 rounded focus:ring-purple-500 mt-1">

                                <!-- Comment Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">{{ substr($comment->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="text-white font-semibold">{{ $comment->name }}</h4>
                                                <p class="text-blue-200 text-sm">{{ $comment->email }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <div class="flex items-center space-x-2">
                                            @if($comment->is_spam)
                                                <span class="inline-block bg-gray-500 text-white text-xs px-3 py-1 rounded-full">SPAM</span>
                                            @endif
                                            
                                            @switch($comment->status)
                                                @case('pending')
                                                    <span class="inline-block bg-yellow-500 text-white text-xs px-3 py-1 rounded-full">PENDING</span>
                                                    @break
                                                @case('approved')
                                                    <span class="inline-block bg-green-500 text-white text-xs px-3 py-1 rounded-full">APPROVED</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="inline-block bg-red-500 text-white text-xs px-3 py-1 rounded-full">REJECTED</span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>

                                    <!-- Comment Text -->
                                    <div class="bg-white/5 rounded-lg p-4 mb-4">
                                        <p class="text-white">{{ $comment->comment }}</p>
                                    </div>

                                    <!-- Photo Info -->
                                    <div class="flex items-center space-x-3 mb-4">
                                        <img src="{{ $comment->photo->thumbnail_url }}" alt="{{ $comment->photo->title }}" 
                                             class="w-12 h-12 rounded-lg object-cover">
                                        <div>
                                            <p class="text-white font-medium">{{ $comment->photo->title }}</p>
                                            <p class="text-blue-200 text-sm">{{ $comment->photo->category->name ?? 'No category' }}</p>
                                        </div>
                                    </div>

                                    <!-- Meta Info -->
                                    <div class="flex items-center justify-between text-xs text-gray-400">
                                        <div class="flex items-center space-x-4">
                                            <span>{{ $comment->created_at->format('M j, Y H:i') }}</span>
                                            <span>IP: {{ $comment->ip_address }}</span>
                                            @if($comment->approved_at)
                                                <span>Approved {{ $comment->approved_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col space-y-2">
                                    @if($comment->status === 'pending')
                                        <button onclick="approveComment({{ $comment->id }})" 
                                                class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <button onclick="rejectComment({{ $comment->id }})" 
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(!$comment->is_spam)
                                        <button onclick="markSpam({{ $comment->id }})" 
                                                class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM4 10a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button onclick="unmarkSpam({{ $comment->id }})" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button onclick="deleteComment({{ $comment->id }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zM8 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $comments->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No comments found</h3>
                <p class="text-gray-400">Comments will appear here when visitors leave feedback on photos.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const selected = document.querySelectorAll('.comment-checkbox:checked');
        const count = selected.length;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `${count} comment${count > 1 ? 's' : ''} selected`;
            
            // Add hidden inputs for selected comments
            const existingInputs = bulkForm.querySelectorAll('input[name="comments[]"]');
            existingInputs.forEach(input => input.remove());
            
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'comments[]';
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
            if (!confirm('Are you sure you want to delete the selected comments? This action cannot be undone.')) {
                e.preventDefault();
                return;
            }
        }
    });
});

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

function unmarkSpam(commentId) {
    fetch(`/admin/comments/${commentId}/unmark-spam`, {
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
    if (confirm('Run automatic moderation? This will detect spam and auto-approve trusted users.')) {
        fetch('/admin/comments/auto-moderate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection
