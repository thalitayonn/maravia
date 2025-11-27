@extends('layouts.admin')

@section('title', 'Tambah Artikel')

@section('content')
<div class="px-6 py-6 max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Artikel</h1>
        <a href="{{ route('admin.articles.index') }}" class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">Kembali</a>
    </div>

    @if ($errors->any())
      <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
        <div class="font-semibold mb-1">Periksa input:</div>
        <ul class="list-disc ml-5 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.articles.store') }}" method="post" enctype="multipart/form-data" class="modern-card p-6 space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Judul</label>
        <input name="title" value="{{ old('title') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Ringkasan (maks 500)</label>
        <input name="excerpt" value="{{ old('excerpt') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Konten</label>
        <input id="content" type="hidden" name="content" value="{{ old('content') }}">
        <trix-editor input="content" placeholder="Tulis konten artikel..." class="trix-content bg-white border rounded-lg focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600); min-height: 220px;"></trix-editor>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Cover Image (opsional)</label>
        <input type="file" name="cover" accept="image/*" class="w-full border rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);">
        <p class="mt-1 text-xs text-gray-500">Format JPG/PNG/WEBP, ukuran maks 10MB.</p>
        @error('cover')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid grid-cols-2 gap-4">
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_published" value="1"> <span>Publish sekarang</span></label>
        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Publish (opsional)</label>
          <input type="datetime-local" name="published_at" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);" value="{{ old('published_at') }}">
        </div>
      </div>

      <div class="sticky bottom-0 -mx-6 -mb-6 px-6 py-4 bg-white border-t rounded-b-xl flex items-center justify-end gap-3">
        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Batal</a>
        <button class="px-5 py-2 rounded text-white" style="background: var(--color-primary-600);">Simpan</button>
      </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.css">
<style>
  /* Wrapper card alignment */
  .trix-content {
    border-color: #e5e7eb;
    padding: 12px;
    line-height: 1.7;
  }
  /* Toolbar polish */
  trix-toolbar {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 8px 10px;
    margin-bottom: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  }
  trix-toolbar .trix-button-group {
    border: none;
  }
  trix-toolbar .trix-button-group:not(:last-child) {
    margin-right: 8px;
  }
  trix-toolbar .trix-button {
    border-radius: 0.5rem;
    padding: 6px 8px;
  }
  trix-toolbar .trix-button:not([disabled]):hover {
    background: #f3f4f6;
  }
  trix-toolbar .trix-button:not([disabled]):active {
    background: #e5e7eb;
  }
  /* Active formatting state while mengetik */
  trix-toolbar .trix-button[data-trix-attribute][data-trix-activated] {
    background: var(--color-primary-600);
    color: #fff;
  }
  /* Hide rarely used tools to make it clean */
  /* Hide rarely used buttons: heading, quote, strike, code, nesting, and file upload */
  trix-toolbar [data-trix-attribute="heading1"],
  trix-toolbar [data-trix-attribute="quote"],
  trix-toolbar [data-trix-attribute="strike"],
  trix-toolbar [data-trix-attribute="code"],
  trix-toolbar [data-trix-action="decreaseNestingLevel"],
  trix-toolbar [data-trix-action="increaseNestingLevel"],
  trix-toolbar .trix-button-group--file-tools {
    display: none !important;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush