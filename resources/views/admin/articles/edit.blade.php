@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')
<div class="px-6 py-6 max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Artikel</h1>
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

    <form action="{{ route('admin.articles.update', $article) }}" method="post" enctype="multipart/form-data" class="modern-card p-6 space-y-5">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium mb-1">Judul</label>
        <input name="title" value="{{ old('title', $article->title) }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Ringkasan</label>
        <input name="excerpt" value="{{ old('excerpt', $article->excerpt) }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Konten</label>
        <input id="content" type="hidden" name="content" value="{{ old('content', $article->content) }}">
        <trix-editor input="content" class="trix-content bg-white border rounded-lg"></trix-editor>
      </div>

      @if($article->cover_image)
      <div>
        <label class="block text-sm font-medium mb-1">Cover Saat Ini</label>
        <div class="rounded-lg overflow-hidden border bg-gray-50 mb-2">
          <img src="{{ url('/api/articles/'.$article->id.'/cover') }}" alt="{{ $article->title }}" class="w-full max-h-64 object-cover">
        </div>
        <p class="text-xs text-gray-500">Cover di atas adalah gambar yang sedang digunakan untuk artikel ini.</p>
      </div>
      @endif

      <div>
        <label class="block text-sm font-medium mb-1">Upload Cover Baru</label>
        <div class="rounded-lg border border-dashed bg-gray-50 px-3 py-3 flex items-center justify-between gap-3">
          <input type="file" name="cover" accept="image/*" class="w-full text-sm focus:outline-none">
          <span class="text-[11px] text-gray-500 whitespace-nowrap">JPG/PNG, maks 10MB</span>
        </div>
        @error('cover')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid grid-cols-2 gap-4">
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ $article->is_published ? 'checked' : '' }}> <span>Published</span></label>
        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Publish</label>
          <input type="datetime-local" name="published_at" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\\TH:i')) }}">
        </div>
      </div>

      <div class="sticky bottom-0 -mx-6 -mb-6 px-6 py-4 bg-white border-t rounded-b-xl flex items-center justify-end gap-3">
        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Batal</a>
        <button class="px-5 py-2 rounded text-white" style="background: var(--color-primary-600);">Simpan Perubahan</button>
      </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush