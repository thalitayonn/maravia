@extends('layouts.admin')

@section('title', 'Create New Page')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Create New Page</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Build beautiful pages with our intuitive editor</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.pages.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #A3D5FF; color: #1C1C1C;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Pages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 pb-8">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Page Content -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-file-alt mr-2" style="color: #FEEA77;"></i>
                                        Page Content
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">Enter your page details</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FEEA77; color: #1C1C1C;">
                                    <i class="fas fa-asterisk mr-1"></i>
                                    Required
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Page Title -->
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Page Title *
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-300"
                                       placeholder="Enter an engaging page title">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Page Excerpt -->
                            <div>
                                <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Page Excerpt
                                </label>
                                <textarea id="excerpt" 
                                          name="excerpt" 
                                          rows="3"
                                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-300"
                                          placeholder="Write a compelling summary of your page content">{{ old('excerpt') }}</textarea>
                                <p class="text-gray-500 text-xs mt-2">This excerpt will be used in page previews and search results (max 500 characters)</p>
                                @error('excerpt')
                                    <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Page Content -->
                            <div>
                                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Page Content *
                                </label>
                                <textarea id="content" 
                                          name="content" 
                                          rows="15"
                                          required
                                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-300"
                                          placeholder="Write your page content here...">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-2 bg-red-50 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Settings -->
                <div class="space-y-6">
                    <!-- Page Settings -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-cog mr-2" style="color: #FEEA77;"></i>
                                Page Settings
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Configure visibility</p>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Published -->
                            <div class="flex items-start space-x-4 bg-green-50 p-4 rounded-xl border border-green-200">
                                <input type="checkbox" 
                                       id="is_published" 
                                       name="is_published" 
                                       value="1" 
                                       {{ old('is_published') ? 'checked' : '' }}
                                       class="mt-1 rounded bg-white border-green-300 text-green-600 focus:ring-green-500 focus:ring-2">
                                <div class="flex-1">
                                    <label for="is_published" class="text-gray-800 font-semibold cursor-pointer">
                                        <i class="fas fa-eye text-green-500 mr-2"></i>Published
                                    </label>
                                    <p class="text-sm text-gray-600 mt-1">Make this page visible to visitors</p>
                                </div>
                            </div>

                            <!-- Show in Menu -->
                            <div class="flex items-start space-x-4 bg-blue-50 p-4 rounded-xl border border-blue-200">
                                <input type="checkbox" 
                                       id="show_in_menu" 
                                       name="show_in_menu" 
                                       value="1" 
                                       {{ old('show_in_menu') ? 'checked' : '' }}
                                       class="mt-1 rounded bg-white border-blue-300 text-blue-600 focus:ring-blue-500 focus:ring-2">
                                <div class="flex-1">
                                    <label for="show_in_menu" class="text-gray-800 font-semibold cursor-pointer">
                                        <i class="fas fa-bars text-blue-500 mr-2"></i>Show in Menu
                                    </label>
                                    <p class="text-sm text-gray-600 mt-1">Display in navigation menu</p>
                                </div>
                            </div>

                            <!-- Menu Order -->
                            <div>
                                <label for="menu_order" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Menu Order
                                </label>
                                <input type="number" 
                                       id="menu_order" 
                                       name="menu_order" 
                                       value="{{ old('menu_order', 0) }}" 
                                       min="0"
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-300"
                                       placeholder="0">
                                <p class="text-gray-500 text-xs mt-2">Lower numbers appear first in menu</p>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-search mr-2" style="color: #FEEA77;"></i>
                                SEO Settings
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Optimize for search engines</p>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Meta Title -->
                            <div>
                                <label for="meta_title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Meta Title
                                </label>
                                <input type="text" 
                                       id="meta_title" 
                                       name="meta_title" 
                                       value="{{ old('meta_title') }}" 
                                       maxlength="255"
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-300"
                                       placeholder="SEO-friendly title">
                                <p class="text-gray-500 text-xs mt-2">Leave empty to use page title</p>
                            </div>

                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Meta Description
                                </label>
                                <textarea id="meta_description" 
                                          name="meta_description" 
                                          rows="3" 
                                          maxlength="255"
                                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all duration-300"
                                          placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                                <p class="text-gray-500 text-xs mt-2">Appears in search engine results</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-3">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                style="background: #A3D5FF; color: #1C1C1C;">
                            <i class="fas fa-save mr-2"></i>
                            Create Page
                        </button>
                        <a href="{{ route('admin.pages.index') }}" 
                           class="w-full inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg bg-gray-200 hover:bg-gray-300 text-gray-700">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
