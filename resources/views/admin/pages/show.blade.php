@extends('layouts.admin')

@section('title', 'Page: ' . $page->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $page->title }}</h3>
                    <div>
                        <a href="{{ route('page.show', $page->slug) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-eye"></i> View Page
                        </a>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Pages
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>Page Content</h5>
                                <div class="border p-3 rounded bg-light">
                                    {!! $page->content !!}
                                </div>
                            </div>

                            @if($page->excerpt)
                                <div class="mb-4">
                                    <h5>Excerpt</h5>
                                    <p class="text-muted">{{ $page->excerpt }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Page Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($page->is_published)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>In Menu:</strong></td>
                                            <td>
                                                @if($page->show_in_menu)
                                                    <span class="badge bg-info">Yes</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Menu Order:</strong></td>
                                            <td>{{ $page->menu_order }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Slug:</strong></td>
                                            <td><code>{{ $page->slug }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $page->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created by:</strong></td>
                                            <td>{{ $page->creator->name ?? 'Unknown' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($page->meta_title || $page->meta_description)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">SEO Information</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($page->meta_title)
                                            <div class="mb-3">
                                                <strong>Meta Title:</strong>
                                                <p class="mb-0">{{ $page->meta_title }}</p>
                                            </div>
                                        @endif
                                        
                                        @if($page->meta_description)
                                            <div class="mb-0">
                                                <strong>Meta Description:</strong>
                                                <p class="mb-0">{{ $page->meta_description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
