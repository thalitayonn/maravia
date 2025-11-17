@extends('layouts.admin')

@section('title', 'Edit Page: ' . $page->title)

@push('styles')
<style>
.edit-page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
}

.form-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.form-section h5 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.form-section h5 i {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-label i {
    margin-right: 0.5rem;
    color: #667eea;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 0.8rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
}

.form-text {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    font-style: italic;
}

.form-check {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.form-check:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    font-weight: 600;
    color: #2c3e50;
    margin-left: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    color: white;
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
}

.btn-info:hover {
    transform: translateY(-2px);
    color: white;
}

.back-btn {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2rem;
    color: #8b4513;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    transform: translateY(-2px);
    color: #8b4513;
}

.invalid-feedback {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    color: #721c24;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    margin-top: 0.5rem;
    font-weight: 500;
}

.card-footer {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
    border-top: 1px solid rgba(102, 126, 234, 0.1);
    padding: 2rem;
}

.content-editor {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.content-editor:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.ck-editor__editable {
    min-height: 300px;
    border: none !important;
    border-radius: 0 !important;
}

.floating-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
    z-index: -1;
}

.shape {
    position: absolute;
    opacity: 0.1;
    animation: floatShape 20s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    top: 10%;
    left: 5%;
    animation-delay: 0s;
}

.shape-2 {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 20px;
    top: 60%;
    right: 10%;
    animation-delay: 7s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    transform: rotate(45deg);
    bottom: 20%;
    left: 15%;
    animation-delay: 14s;
}

@keyframes floatShape {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(120deg); }
    66% { transform: translateY(-10px) rotate(240deg); }
}

.page-info-card {
    background: linear-gradient(135deg, #e8f5e8 0%, #f0fff0 100%);
    border: 2px solid rgba(40, 167, 69, 0.2);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.page-info-card h6 {
    color: #155724;
    font-weight: 700;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(40, 167, 69, 0.1);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #155724;
}

.info-value {
    color: #28a745;
    font-weight: 500;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-published {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.status-draft {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-row .form-group {
    flex: 1;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .edit-page-header h1 {
        font-size: 1.8rem;
    }
    
    .form-section {
        padding: 1rem;
    }
}

.required-asterisk {
    color: #dc3545;
    margin-left: 3px;
}

.form-group {
    margin-bottom: 1.5rem;
}

.settings-grid {
    display: grid;
    gap: 1rem;
}

@media (min-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 576px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid position-relative">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="edit-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-edit me-3"></i>
                    Edit Page
                </h1>
                <p class="mb-0 opacity-75">{{ $page->title }}</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="btn back-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Pages
            </a>
        </div>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="card-body p-4">
                        <div class="form-section">
                            <h5><i class="fas fa-edit"></i>Page Content</h5>
                            
                            <div class="form-group">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i>Page Title<span class="required-asterisk">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $page->title) }}" 
                                       placeholder="Enter an engaging page title"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="excerpt" class="form-label">
                                    <i class="fas fa-quote-left"></i>Page Excerpt
                                </label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                          id="excerpt" 
                                          name="excerpt" 
                                          rows="3" 
                                          placeholder="Write a compelling summary of your page content">{{ old('excerpt', $page->excerpt) }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    This excerpt will be used in page previews and search results (max 500 characters)
                                </div>
                                @error('excerpt')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content" class="form-label">
                                    <i class="fas fa-file-alt"></i>Page Content<span class="required-asterisk">*</span>
                                </label>
                                <div class="content-editor">
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              required>{{ old('content', $page->content) }}</textarea>
                                </div>
                                @error('content')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="page-info-card">
                    <h6><i class="fas fa-info-circle me-2"></i>Page Information</h6>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="status-badge {{ $page->is_published ? 'status-published' : 'status-draft' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Slug:</span>
                        <span class="info-value">{{ $page->slug }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created:</span>
                        <span class="info-value">{{ $page->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Updated:</span>
                        <span class="info-value">{{ $page->updated_at->format('M j, Y') }}</span>
                    </div>
                    @if($page->is_published)
                    <div class="info-item">
                        <span class="info-label">View Page:</span>
                        <a href="{{ route('pages.show', $page->slug) }}" 
                           target="_blank" 
                           class="info-value text-decoration-none">
                            <i class="fas fa-external-link-alt me-1"></i>Visit
                        </a>
                    </div>
                    @endif
                </div>

                <div class="form-card">
                    <div class="card-body p-4">
                        <div class="form-section">
                            <h5><i class="fas fa-cog"></i>Page Settings</h5>
                            
                            <div class="settings-grid">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_published" 
                                           name="is_published" 
                                           value="1" 
                                           {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        <i class="fas fa-eye me-2"></i>Published
                                    </label>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Make this page visible to visitors
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="show_in_menu" 
                                           name="show_in_menu" 
                                           value="1" 
                                           {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_in_menu">
                                        <i class="fas fa-bars me-2"></i>Show in Menu
                                    </label>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Display in navigation menu
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="menu_order" class="form-label">
                                    <i class="fas fa-sort-numeric-down"></i>Menu Order
                                </label>
                                <input type="number" 
                                       class="form-control @error('menu_order') is-invalid @enderror" 
                                       id="menu_order" 
                                       name="menu_order" 
                                       value="{{ old('menu_order', $page->menu_order) }}" 
                                       min="0"
                                       placeholder="0">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Lower numbers appear first in menu
                                </div>
                                @error('menu_order')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section">
                            <h5><i class="fas fa-search"></i>SEO Settings</h5>
                            
                            <div class="form-group">
                                <label for="meta_title" class="form-label">
                                    <i class="fas fa-tag"></i>Meta Title
                                </label>
                                <input type="text" 
                                       class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" 
                                       name="meta_title" 
                                       value="{{ old('meta_title', $page->meta_title) }}" 
                                       maxlength="255"
                                       placeholder="SEO-friendly title">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Leave empty to use page title
                                </div>
                                @error('meta_title')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="meta_description" class="form-label">
                                    <i class="fas fa-align-left"></i>Meta Description
                                </label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" 
                                          name="meta_description" 
                                          rows="3" 
                                          maxlength="255"
                                          placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Appears in search engine results
                                </div>
                                @error('meta_description')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card mt-3">
            <div class="card-footer text-center">
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Page
                    </button>
                    @if($page->is_published)
                    <a href="{{ route('pages.show', $page->slug) }}" 
                       target="_blank" 
                       class="btn btn-info">
                        <i class="fas fa-eye me-2"></i> View Page
                    </a>
                    @endif
                    <a href="{{ route('admin.pages.show', $page) }}" class="btn btn-secondary">
                        <i class="fas fa-info-circle me-2"></i> Page Details
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'link', 'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .then(editor => {
            // Custom styling for CKEditor
            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '300px', editor.editing.view.document.getRoot());
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Character counter for meta description
    const metaDesc = document.getElementById('meta_description');
    if (metaDesc) {
        const counter = document.createElement('div');
        counter.className = 'form-text text-end';
        counter.style.marginTop = '0.25rem';
        metaDesc.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = 255 - metaDesc.value.length;
            counter.textContent = `${metaDesc.value.length}/255 characters`;
            counter.style.color = remaining < 50 ? '#dc3545' : '#6c757d';
        }
        
        metaDesc.addEventListener('input', updateCounter);
        updateCounter();
    }
</script>
@endpush
@endsection
