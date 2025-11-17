@extends('layouts.admin')

@section('title', 'Manage Testimonials')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Manage Testimonials</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Review and moderate user testimonials</p>
                </div>
                <div class="flex space-x-3">
                    <div class="px-4 py-2 rounded-lg font-medium text-sm" style="background: #FEEA77; color: #1C1C1C;">
                        <i class="fas fa-quote-left mr-2"></i>
                        {{ $testimonials->total() }} total testimonials
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Review and moderate user feedback and testimonials</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-star mr-2" style="color: #FEEA77;"></i>
                        User Reviews
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-shield-alt mr-2" style="color: #FEEA77;"></i>
                        Spam Detection
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-quote-left"></i>
            </div>
        </div>
    </div>

        <!-- Search and Filters -->
        <div class="modern-card mb-8 hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
            <div class="modern-card-header border-b border-orange-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="modern-card-title text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                            Search & Filter Testimonials
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Find and moderate testimonials efficiently</p>
                    </div>
                    <div class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-filter mr-1"></i>
                        Smart Filter
                    </div>
                </div>
            </div>
            <div class="modern-card-content pt-8">
                <form method="GET" action="{{ route('admin.testimonials.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Search -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search testimonials..." 
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>

                        <!-- Spam Filter -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Spam Risk</label>
                            <select name="spam" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <option value="">All Levels</option>
                                <option value="low" {{ request('spam') === 'low' ? 'selected' : '' }}>Low Risk</option>
                                <option value="medium" {{ request('spam') === 'medium' ? 'selected' : '' }}>Medium Risk</option>
                                <option value="high" {{ request('spam') === 'high' ? 'selected' : '' }}>High Risk</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="spam" {{ request('sort') === 'spam' ? 'selected' : '' }}>Highest Spam Risk</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.testimonials.index') }}" class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-700 px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="modern-card mb-8 hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50" id="bulk-actions" style="display: none;">
            <div class="modern-card-content p-6">
                <form method="POST" action="{{ route('admin.testimonials.bulk-action') }}" id="bulk-form">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                            <span class="text-gray-700 font-semibold">With selected:</span>
                        </div>
                        <select name="action" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300">
                            <option value="">Choose action...</option>
                            <option value="approve">Approve</option>
                            <option value="reject">Reject</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-play mr-2"></i>
                            Apply Action
                        </button>
                        <span class="text-gray-600 bg-gray-100 px-3 py-2 rounded-lg text-sm font-medium" id="selected-count">0 testimonials selected</span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Testimonials List -->
        @if($testimonials->count() > 0)
            <div class="space-y-6">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-item group relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-200 to-amber-200 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-6 border border-orange-100 hover:border-orange-200 transition-all duration-300">
                            <div class="flex items-start space-x-4">
                                <!-- Checkbox -->
                                <input type="checkbox" name="testimonial_ids[]" value="{{ $testimonial->id }}" 
                                       class="testimonial-checkbox w-5 h-5 text-orange-600 bg-white border-orange-200 rounded focus:ring-orange-300 mt-2 transition-all duration-300">

                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-gray-800 font-bold text-lg">{{ $testimonial->name }}</h3>
                                            @if($testimonial->is_approved)
                                                <span class="bg-gradient-to-r from-amber-400 to-yellow-400 text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                                                    <i class="fas fa-check mr-1"></i>Approved
                                                </span>
                                            @else
                                                <span class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                            
                                            <!-- Spam Score Badge -->
                                            @if($testimonial->spam_score >= 0.7)
                                                <span class="bg-gradient-to-r from-red-400 to-pink-400 text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>High Risk
                                                </span>
                                            @elseif($testimonial->spam_score >= 0.3)
                                                <span class="bg-gradient-to-r from-orange-400 to-yellow-400 text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                                                    <i class="fas fa-exclamation mr-1"></i>Medium Risk
                                                </span>
                                            @else
                                                <span class="bg-gradient-to-r from-amber-400 to-yellow-400 text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                                                    <i class="fas fa-shield-check mr-1"></i>Low Risk
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $testimonial->created_at->format('M j, Y g:i A') }}
                                        </div>
                                    </div>

                                    <p class="text-gray-600 text-sm mb-3 bg-orange-50 px-3 py-1 rounded-lg inline-flex items-center">
                                        <i class="fas fa-envelope mr-2 text-orange-500"></i>
                                        {{ $testimonial->email }}
                                    </p>
                                    
                                    <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-100">
                                        <p class="text-gray-700 leading-relaxed">{{ $testimonial->message }}</p>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-lg font-medium">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                Spam Score: {{ number_format($testimonial->spam_score * 100, 1) }}%
                                            </span>
                                            @if($testimonial->approved_at)
                                                <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-lg font-medium">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Approved {{ $testimonial->approved_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2">
                                            @if(!$testimonial->is_approved)
                                                <button onclick="approveTestimonial({{ $testimonial->id }})" 
                                                        class="bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                            @else
                                                <button onclick="rejectTestimonial({{ $testimonial->id }})" 
                                                        class="bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Reject
                                                </button>
                                            @endif

                                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                                               class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>

                                            <button onclick="deleteTestimonial({{ $testimonial->id }})" 
                                                    class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $testimonials->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-quote-left text-6xl text-orange-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">No Testimonials Found</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">User testimonials will appear here for moderation and review.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.testimonial-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const selected = document.querySelectorAll('.testimonial-checkbox:checked');
        const count = selected.length;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `${count} testimonial${count > 1 ? 's' : ''} selected`;
            
            // Add hidden inputs for selected testimonials
            const existingInputs = bulkForm.querySelectorAll('input[name="testimonials[]"]');
            existingInputs.forEach(input => input.remove());
            
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'testimonials[]';
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
            if (!confirm('Are you sure you want to delete the selected testimonials? This action cannot be undone.')) {
                e.preventDefault();
                return;
            }
        }
    });
});

function approveTestimonial(testimonialId) {
    fetch(`/admin/testimonials/${testimonialId}/approve`, {
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

function rejectTestimonial(testimonialId) {
    fetch(`/admin/testimonials/${testimonialId}/reject`, {
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

function deleteTestimonial(testimonialId) {
    if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/testimonials/${testimonialId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
