@props(['photo'])

<div class="photo-comments-section bg-white rounded-2xl shadow-lg p-6 mt-8" data-photo-id="{{ $photo->id }}">
    <!-- Comments Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-comments text-blue-500 mr-2"></i>
            Komentar (<span id="comments-count">{{ $photo->comments()->approved()->count() }}</span>)
        </h3>
        <button onclick="refreshComments()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            <i class="fas fa-sync-alt mr-1"></i>Refresh
        </button>
    </div>

    <!-- Comment Form -->
    <div class="comment-form mb-8">
        @auth
            <form id="comment-form" class="space-y-4">
                @csrf
                <div class="flex items-start space-x-4">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" 
                         class="w-10 h-10 rounded-full flex-shrink-0">
                    <div class="flex-1">
                        <textarea name="comment" id="comment-input" 
                                  placeholder="Tulis komentar Anda..." 
                                  class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  rows="3" maxlength="1000"></textarea>
                        <div class="flex items-center justify-between mt-3">
                            <div class="text-sm text-gray-500">
                                <span id="char-count">0</span>/1000 karakter
                            </div>
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <i class="fas fa-paper-plane mr-2"></i>Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <i class="fas fa-sign-in-alt text-gray-400 text-3xl mb-3"></i>
                <p class="text-gray-600 mb-4">Silakan login untuk memberikan komentar</p>
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-colors font-medium">
                    Login Sekarang
                </a>
            </div>
        @endauth
    </div>

    <!-- Comments List -->
    <div id="comments-container" class="space-y-6">
        <!-- Comments will be loaded here via JavaScript -->
    </div>

    <!-- Load More Button -->
    <div id="load-more-container" class="text-center mt-6 hidden">
        <button id="load-more-btn" 
                class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-200 transition-colors font-medium">
            <i class="fas fa-chevron-down mr-2"></i>Muat Lebih Banyak
        </button>
    </div>

    <!-- Loading Indicator -->
    <div id="comments-loading" class="text-center py-8 hidden">
        <div class="inline-flex items-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-3"></div>
            <span class="text-gray-600">Memuat komentar...</span>
        </div>
    </div>
</div>

<!-- Reaction Picker Modal -->
<div id="reaction-picker" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full">
            <h4 class="text-lg font-semibold text-gray-900 mb-4 text-center">Pilih Reaksi</h4>
            <div class="grid grid-cols-3 gap-4" id="reaction-options">
                <!-- Reaction options will be populated by JavaScript -->
            </div>
            <button onclick="closeReactionPicker()" 
                    class="w-full mt-4 bg-gray-100 text-gray-700 py-2 rounded-xl hover:bg-gray-200 transition-colors">
                Batal
            </button>
        </div>
    </div>
</div>

<style>
.comment-item {
    @apply bg-gray-50 rounded-xl p-4 transition-all duration-300;
}

.comment-item:hover {
    @apply bg-gray-100;
}

.reaction-btn {
    @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer;
}

.reaction-btn:hover {
    @apply bg-blue-100 text-blue-700;
}

.reaction-btn.active {
    @apply bg-blue-600 text-white;
}

.reaction-option {
    @apply flex flex-col items-center p-3 rounded-xl hover:bg-gray-100 cursor-pointer transition-colors;
}

.emoji-large {
    @apply text-3xl mb-2;
}

.comment-actions {
    @apply flex items-center space-x-4 mt-3;
}

.comment-meta {
    @apply flex items-center space-x-2 text-sm text-gray-500;
}
</style>

<script>
let currentPage = 1;
let currentCommentId = null;
const photoId = {{ $photo->id }};

document.addEventListener('DOMContentLoaded', function() {
    loadComments();
    setupCommentForm();
});

function setupCommentForm() {
    const form = document.getElementById('comment-form');
    const input = document.getElementById('comment-input');
    const submitBtn = form?.querySelector('button[type="submit"]');
    const charCount = document.getElementById('char-count');

    if (!form) return;

    // Character counter
    input.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        submitBtn.disabled = length === 0 || length > 1000;
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitComment();
    });
}

async function submitComment() {
    const form = document.getElementById('comment-form');
    const input = document.getElementById('comment-input');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (!input.value.trim()) return;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

    try {
        const response = await fetch(`/photos/${photoId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                comment: input.value
            })
        });

        const data = await response.json();

        if (data.success) {
            input.value = '';
            document.getElementById('char-count').textContent = '0';
            showNotification(data.message, 'success');
            loadComments(); // Reload comments
        } else {
            showNotification('Gagal mengirim komentar', 'error');
        }
    } catch (error) {
        showNotification('Terjadi kesalahan', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim';
    }
}

async function loadComments(page = 1) {
    const container = document.getElementById('comments-container');
    const loading = document.getElementById('comments-loading');
    
    if (page === 1) {
        loading.classList.remove('hidden');
        container.innerHTML = '';
    }

    try {
        const response = await fetch(`/photos/${photoId}/comments?page=${page}`);
        const data = await response.json();

        if (data.success) {
            if (page === 1) {
                container.innerHTML = '';
            }

            data.comments.forEach(comment => {
                container.appendChild(createCommentElement(comment));
            });

            // Update comments count
            document.getElementById('comments-count').textContent = data.pagination.total;

            // Handle load more button
            const loadMoreContainer = document.getElementById('load-more-container');
            if (data.pagination.current_page < data.pagination.last_page) {
                loadMoreContainer.classList.remove('hidden');
                document.getElementById('load-more-btn').onclick = () => loadComments(page + 1);
            } else {
                loadMoreContainer.classList.add('hidden');
            }

            currentPage = page;
        }
    } catch (error) {
        showNotification('Gagal memuat komentar', 'error');
    } finally {
        loading.classList.add('hidden');
    }
}

function createCommentElement(comment) {
    const div = document.createElement('div');
    div.className = 'comment-item';
    div.dataset.commentId = comment.id;

    const reactionsHtml = Object.entries(comment.reaction_counts)
        .filter(([type, data]) => data.count > 0)
        .map(([type, data]) => 
            `<span class="reaction-btn ${comment.user_reaction === type ? 'active' : ''}" 
                   onclick="toggleReaction(${comment.id}, '${type}')">
                ${data.emoji} ${data.count}
             </span>`
        ).join('');

    div.innerHTML = `
        <div class="flex items-start space-x-4">
            <img src="${comment.author_avatar}" alt="${comment.author_name}" 
                 class="w-10 h-10 rounded-full flex-shrink-0">
            <div class="flex-1">
                <div class="comment-meta">
                    <span class="font-semibold text-gray-900">${comment.author_name}</span>
                    <span>•</span>
                    <span>${comment.created_at}</span>
                </div>
                <p class="text-gray-800 mt-2">${comment.comment}</p>
                <div class="comment-actions">
                    <button onclick="openReactionPicker(${comment.id})" 
                            class="text-gray-500 hover:text-blue-600 text-sm font-medium">
                        <i class="fas fa-smile mr-1"></i>Reaksi
                    </button>
                    ${reactionsHtml}
                </div>
            </div>
        </div>
    `;

    return div;
}

function openReactionPicker(commentId) {
    currentCommentId = commentId;
    const modal = document.getElementById('reaction-picker');
    const optionsContainer = document.getElementById('reaction-options');
    
    const reactions = {
        'like': '👍',
        'love': '❤️',
        'laugh': '😂',
        'wow': '😮',
        'sad': '😢',
        'angry': '😠'
    };

    optionsContainer.innerHTML = Object.entries(reactions)
        .map(([type, emoji]) => 
            `<div class="reaction-option" onclick="selectReaction('${type}')">
                <span class="emoji-large">${emoji}</span>
                <span class="text-xs text-gray-600 capitalize">${type}</span>
             </div>`
        ).join('');

    modal.classList.remove('hidden');
}

function closeReactionPicker() {
    document.getElementById('reaction-picker').classList.add('hidden');
    currentCommentId = null;
}

async function selectReaction(reactionType) {
    if (!currentCommentId) return;

    closeReactionPicker();
    await toggleReaction(currentCommentId, reactionType);
}

async function toggleReaction(commentId, reactionType) {
    try {
        const response = await fetch(`/comments/${commentId}/reaction`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reaction_type: reactionType
            })
        });

        const data = await response.json();

        if (data.success) {
            // Update the comment element with new reaction data
            updateCommentReactions(commentId, data.reaction_counts, data.user_reaction);
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Gagal memberikan reaksi', 'error');
        }
    } catch (error) {
        showNotification('Terjadi kesalahan', 'error');
    }
}

function updateCommentReactions(commentId, reactionCounts, userReaction) {
    const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
    if (!commentElement) return;

    const actionsContainer = commentElement.querySelector('.comment-actions');
    const reactionButton = actionsContainer.querySelector('button');
    
    // Remove existing reaction buttons
    actionsContainer.querySelectorAll('.reaction-btn').forEach(btn => btn.remove());

    // Add updated reaction buttons
    Object.entries(reactionCounts)
        .filter(([type, data]) => data.count > 0)
        .forEach(([type, data]) => {
            const span = document.createElement('span');
            span.className = `reaction-btn ${userReaction === type ? 'active' : ''}`;
            span.onclick = () => toggleReaction(commentId, type);
            span.innerHTML = `${data.emoji} ${data.count}`;
            actionsContainer.appendChild(span);
        });
}

function refreshComments() {
    currentPage = 1;
    loadComments();
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
