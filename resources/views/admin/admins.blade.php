@extends('layouts.admin')

@section('title', 'Admin Management')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Admin Management</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Manage administrator accounts and permissions</p>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="openCreateModal()" 
                            class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                            style="background: #FF6F61; color: white;">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Admin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Manage administrator accounts and permissions</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-shield-alt mr-2" style="color: #FEEA77;"></i>
                        Secure Access
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-users-cog mr-2" style="color: #FEEA77;"></i>
                        {{ $admins->count() }} Administrators
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-users-cog"></i>
            </div>
        </div>
    </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-green-800 font-semibold text-sm">Success</h4>
                        <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-red-400 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-semibold text-sm">Error</h4>
                        <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Administrator List -->
            <div class="lg:col-span-2">
                <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
                    <div class="modern-card-header border-b border-orange-100 pb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="modern-card-title text-xl font-bold flex items-center">
                                    <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                                    Administrator List
                                </h3>
                                <p class="text-gray-500 text-sm mt-1">Manage system administrators</p>
                            </div>
                            <div class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-users mr-1"></i>
                                {{ $admins->count() }} Admins
                            </div>
                        </div>
                    </div>

                    <div class="modern-card-content pt-8">
                        @forelse($admins as $admin)
                            <div class="admin-item group relative mb-4 {{ auth()->id() === $admin->id ? 'current-admin' : '' }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-200 to-amber-200 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                                <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-6 border {{ auth()->id() === $admin->id ? 'border-yellow-200 bg-gradient-to-br from-yellow-50 to-orange-50' : 'border-orange-100 hover:border-orange-200' }} transition-all duration-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 {{ auth()->id() === $admin->id ? 'bg-gradient-to-br from-yellow-400 to-orange-400' : 'bg-gradient-to-br from-orange-400 to-amber-500' }} rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                                <span class="text-white text-xl font-bold">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="text-gray-800 font-bold text-lg flex items-center">
                                                    {{ $admin->name }}
                                                    @if(auth()->id() === $admin->id)
                                                        <span class="ml-3 bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs font-medium">
                                                            <i class="fas fa-crown mr-1"></i>You
                                                        </span>
                                                    @endif
                                                </h4>
                                                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-500">
                                                    <span class="bg-gray-100 px-3 py-1 rounded-lg flex items-center">
                                                        <i class="fas fa-envelope mr-1"></i>
                                                        {{ $admin->email }}
                                                    </span>
                                                    <span class="bg-gray-100 px-3 py-1 rounded-lg flex items-center">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $admin->created_at->format('M j, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @if(auth()->id() !== $admin->id)
                                            <form action="{{ route('admin.admins.delete', $admin) }}" 
                                                  method="POST" 
                                                  class="delete-form"
                                                  onsubmit="return confirmDelete(event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-users text-6xl text-orange-400"></i>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-800 mb-4">No Administrators Found</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Add your first administrator to get started.</p>
                                <button onclick="openCreateModal()" class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add First Administrator
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Admin Statistics -->
                <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-amber-50">
                    <div class="modern-card-header border-b border-amber-100 pb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="modern-card-title text-xl font-bold flex items-center">
                                    <div class="w-3 h-3 bg-amber-400 rounded-full mr-3"></div>
                                    Statistics
                                </h3>
                                <p class="text-gray-500 text-sm mt-1">Admin overview</p>
                            </div>
                            <div class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Overview
                            </div>
                        </div>
                    </div>

                    <div class="modern-card-content pt-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <span class="text-white text-3xl font-bold">{{ $admins->count() }}</span>
                        </div>
                        <h4 class="text-gray-800 font-bold text-lg">Total Administrators</h4>
                        <p class="text-gray-600 text-sm">Active system administrators</p>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-yellow-50">
                    <div class="modern-card-header border-b border-yellow-100 pb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="modern-card-title text-xl font-bold flex items-center">
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full mr-3"></div>
                                    Quick Info
                                </h3>
                                <p class="text-gray-500 text-sm mt-1">Important notes</p>
                            </div>
                            <div class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-info-circle mr-1"></i>
                                Info
                            </div>
                        </div>
                    </div>

                    <div class="modern-card-content pt-8">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-gray-800 font-semibold text-sm">Full Access</h4>
                                    <p class="text-gray-600 text-sm">All admins have complete system access</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-lock text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-gray-800 font-semibold text-sm">Self Protection</h4>
                                    <p class="text-gray-600 text-sm">Cannot delete your own account</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-yellow-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-gray-800 font-semibold text-sm">Unique Email</h4>
                                    <p class="text-gray-600 text-sm">Each admin needs unique email</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-amber-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-key text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-gray-800 font-semibold text-sm">Secure Password</h4>
                                    <p class="text-gray-600 text-sm">Minimum 8 characters required</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Admin Modal -->
<div id="createAdminModal" class="fixed inset-0 bg-gradient-to-br from-black/60 via-purple-900/30 to-black/60 backdrop-blur-md z-50 hidden transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="modalContent" class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-500 scale-95 opacity-0">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full opacity-20 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            
            <!-- Header with White Background -->
            <div class="relative overflow-hidden rounded-t-3xl bg-white border-b-2 border-gray-100">
                <div class="relative p-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg" style="background: #FEEA77;">
                            <i class="fas fa-user-shield text-gray-800 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Add New Administrator
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">Create a new admin account</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('admin.admins.create') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Full Name -->
                    <div class="space-y-2 group">
                        <label for="name" class="block text-sm font-semibold text-gray-700 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2" style="background: #FEEA77;">
                                <i class="fas fa-user text-gray-800 text-xs"></i>
                            </div>
                            Full Name
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-300 hover:bg-white @error('name') border-red-300 ring-2 ring-red-200 @enderror"
                                   placeholder="Enter administrator's full name"
                                   required>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('name')
                            <div class="flex items-center mt-2 text-red-600 text-sm bg-red-50 p-2 rounded-lg">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2 group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2" style="background: #FEEA77;">
                                <i class="fas fa-envelope text-gray-800 text-xs"></i>
                            </div>
                            Email Address
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-300 hover:bg-white @error('email') border-red-300 ring-2 ring-red-200 @enderror"
                                   placeholder="Enter email address"
                                   required>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        @error('email')
                            <div class="flex items-center mt-2 text-red-600 text-sm bg-red-50 p-2 rounded-lg">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2 group">
                        <label for="password" class="block text-sm font-semibold text-gray-700 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2" style="background: #FEEA77;">
                                <i class="fas fa-lock text-gray-800 text-xs"></i>
                            </div>
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-300 hover:bg-white @error('password') border-red-300 ring-2 ring-red-200 @enderror"
                                   placeholder="Enter secure password (min. 8 characters)"
                                   required>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                        @error('password')
                            <div class="flex items-center mt-2 text-red-600 text-sm bg-red-50 p-2 rounded-lg">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2 group">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 flex items-center">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2" style="background: #FEEA77;">
                                <i class="fas fa-shield-alt text-gray-800 text-xs"></i>
                            </div>
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-300 hover:bg-white"
                                   placeholder="Confirm the password"
                                   required>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                <i class="fas fa-shield-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 border-t-2 border-gray-100">
                        <button type="button" 
                                onclick="closeCreateModal()"
                                class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center"
                                style="background: #A3D5FF; color: #1C1C1C;">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        
                        <button type="submit" 
                                class="px-8 py-3 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center"
                                style="background: #A3D5FF; color: #1C1C1C;">
                            <i class="fas fa-user-plus mr-2"></i>
                            <span>Create Administrator</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    const modal = document.getElementById('createAdminModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation after a small delay
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeCreateModal() {
    const modal = document.getElementById('createAdminModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animate out
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function confirmDelete(event) {
    event.preventDefault();
    
    if (confirm('⚠️ Are you sure you want to delete this administrator?\n\nThis action cannot be undone and will permanently remove their access to the admin panel.')) {
        event.target.submit();
    }
    
    return false;
}

// Auto-open modal if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openCreateModal();
    });
@endif

// Close modal when clicking outside
document.getElementById('createAdminModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});
</script>
@endpush
@endsection
