@extends('layouts.admin')

@section('title', 'Pages Management')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Pages Management</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Create and manage your website pages with style</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.pages.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                       style="background: #FF6F61; color: white;">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Page
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Create and manage beautiful pages for your website</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-file-alt mr-2" style="color: #FEEA77;"></i>
                        Content Pages
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-eye mr-2" style="color: #FEEA77;"></i>
                        Live Preview
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>

        @if(session('success'))
            <div class="modern-card mb-8 border-0 bg-gradient-to-br from-white to-green-50">
                <div class="modern-card-content p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-400 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-green-800 font-semibold">Success!</h4>
                            <p class="text-green-600 mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($pages->count() > 0)
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
                <div class="modern-card-header border-b border-orange-100 pb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="modern-card-title text-xl font-bold flex items-center">
                                <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                                All Pages
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Manage your website pages and content</p>
                        </div>
                        <div class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-file-alt mr-1"></i>
                            {{ $pages->count() }} Pages
                        </div>
                    </div>
                </div>

                <div class="modern-card-content pt-8">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-orange-100">
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-heading mr-2 text-orange-400"></i>Title
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-link mr-2 text-orange-400"></i>Slug
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-toggle-on mr-2 text-orange-400"></i>Status
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-bars mr-2 text-orange-400"></i>Menu
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-sort-numeric-down mr-2 text-orange-400"></i>Order
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-user mr-2 text-orange-400"></i>Creator
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-calendar mr-2 text-orange-400"></i>Date
                                    </th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">
                                        <i class="fas fa-cogs mr-2 text-orange-400"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                    <tr class="border-b border-gray-100 hover:bg-orange-50/50 transition-all duration-300 group">
                                        <td class="py-4 px-4">
                                            <div>
                                                <div class="font-semibold text-gray-800 group-hover:text-orange-600 transition-colors duration-300">{{ $page->title }}</div>
                                                @if($page->excerpt)
                                                    <div class="text-sm text-gray-500 italic mt-1">{{ Str::limit($page->excerpt, 50) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <code class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg text-sm font-mono">{{ $page->slug }}</code>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($page->is_published)
                                                <span class="bg-gradient-to-r from-amber-400 to-yellow-400 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                                    <i class="fas fa-eye mr-1"></i>Published
                                                </span>
                                            @else
                                                <span class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                                    <i class="fas fa-eye-slash mr-1"></i>Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($page->show_in_menu)
                                                <span class="bg-gradient-to-r from-orange-400 to-amber-400 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                                    <i class="fas fa-check mr-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">
                                                    <i class="fas fa-times mr-1"></i>No
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-sm font-medium">{{ $page->menu_order }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-amber-400 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-white font-bold text-xs">{{ substr($page->creator->name ?? 'U', 0, 1) }}</span>
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $page->creator->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-gray-600 bg-gray-100 px-3 py-1 rounded-lg text-sm">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $page->created_at->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('page.show', $page->slug) }}" 
                                                   target="_blank"
                                                   class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-lg"
                                                   title="View Page">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                                <a href="{{ route('admin.pages.show', $page) }}" 
                                                   class="bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-lg"
                                                   title="View Details">
                                                    <i class="fas fa-info text-xs"></i>
                                                </a>
                                                <a href="{{ route('admin.pages.edit', $page) }}" 
                                                   class="bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-lg"
                                                   title="Edit">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                                <form action="{{ route('admin.pages.destroy', $page) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this page?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-lg"
                                                            title="Delete">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($pages->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $pages->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-alt text-6xl text-orange-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">No Pages Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Start creating beautiful pages for your website</p>
                <a href="{{ route('admin.pages.create') }}" class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Page
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
