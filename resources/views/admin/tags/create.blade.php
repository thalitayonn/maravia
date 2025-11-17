@extends('layouts.admin')

@section('title', 'Create Tag')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Create New Tag</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Add a new tag to organize your photo collections</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.tags.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Tags
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 pb-8">
        <!-- Create Form -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-tag mr-2" style="color: #FEEA77;"></i>
                                Tag Details
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Configure your new tag settings</p>
                        </div>
                        <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                            <i class="fas fa-plus mr-1"></i>
                            New Tag
                        </div>
                    </div>
                </div>

                <div>
                    <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Tag Name -->
                        <div class="group">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-orange-400 rounded-full mr-3"></div>
                                    <i class="fas fa-tag mr-2 text-orange-500"></i>
                                    Tag Name
                                </div>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Enter a descriptive tag name..."
                                       class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300 group-hover:border-orange-200 text-lg shadow-sm"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i class="fas fa-edit text-gray-400"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-sm mt-2 flex items-center bg-red-50 p-2 rounded-lg">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Color Selection -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-amber-400 rounded-full mr-3"></div>
                                    <i class="fas fa-palette mr-2 text-amber-500"></i>
                                    Tag Color
                                </div>
                            </label>
                            <div class="grid grid-cols-8 gap-3">
                                @php
                                    $colors = [
                                        '#ef4444', '#f97316', '#eab308', '#22c55e', 
                                        '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899',
                                        '#f59e0b', '#10b981', '#14b8a6', '#6366f1',
                                        '#8b5cf6', '#d946ef', '#f43f5e', '#64748b'
                                    ];
                                @endphp
                                @foreach($colors as $color)
                                    <label class="cursor-pointer group/color">
                                        <input type="radio" name="color" value="{{ $color }}" class="sr-only" {{ old('color', '#f97316') == $color ? 'checked' : '' }}>
                                        <div class="w-12 h-12 rounded-xl shadow-lg transform transition-all duration-300 group-hover/color:scale-110 group-hover/color:shadow-xl border-4 border-transparent group-focus-within/color:border-gray-300"
                                             style="background: linear-gradient(135deg, {{ $color }}CC, {{ $color }});">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('color')
                                <p class="text-red-500 text-sm mt-2 flex items-center bg-red-50 p-2 rounded-lg">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                                    <i class="fas fa-toggle-on mr-2 text-yellow-500"></i>
                                    Status
                                </div>
                            </label>
                            <div class="flex items-center space-x-8">
                                <label class="flex items-center cursor-pointer group/radio">
                                    <div class="relative">
                                        <input type="radio"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                               class="sr-only">
                                        <div class="w-6 h-6 rounded-full border-2 border-amber-300 bg-white flex items-center justify-center transition-all duration-300 group-hover/radio:border-amber-400">
                                            <div class="w-3 h-3 rounded-full bg-amber-400 opacity-0 transition-opacity duration-300"></div>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-700 font-medium bg-amber-50 px-3 py-1 rounded-lg">
                                        <i class="fas fa-check-circle mr-1 text-amber-500"></i>
                                        Active
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer group/radio">
                                    <div class="relative">
                                        <input type="radio"
                                               name="is_active"
                                               value="0"
                                               {{ old('is_active') == '0' ? 'checked' : '' }}
                                               class="sr-only">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center transition-all duration-300 group-hover/radio:border-gray-400">
                                            <div class="w-3 h-3 rounded-full bg-gray-400 opacity-0 transition-opacity duration-300"></div>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-700 font-medium bg-gray-50 px-3 py-1 rounded-lg">
                                        <i class="fas fa-pause-circle mr-1 text-gray-500"></i>
                                        Inactive
                                    </span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-2 flex items-center bg-red-50 p-2 rounded-lg">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-100">
                            <a href="{{ route('admin.tags.index') }}"
                               class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg bg-gray-200 hover:bg-gray-300 text-gray-700">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                    style="background: #A3D5FF; color: #1C1C1C;">
                                <i class="fas fa-plus mr-2"></i>
                                Create Tag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom radio button styling */
input[type="radio"]:checked + div .w-3 {
    opacity: 1;
}

input[name="color"]:checked + div {
    border-color: #6b7280 !important;
    transform: scale(1.1);
}
</style>
@endsection
