<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Video</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="bg-gray-50">
  <div class="max-w-3xl mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Edit Video</h1>
      <a href="{{ route('admin.videos.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
    </div>

    @if ($errors->any())
      <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
        <div class="font-semibold mb-1">Periksa input:</div>
        <ul class="list-disc ml-5 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="mb-4">
      @if($video->poster_url)
        <img src="{{ $video->poster_url }}" alt="poster" class="w-full max-w-md rounded shadow" />
      @endif
    </div>

    <form action="{{ route('admin.videos.update', $video) }}" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow space-y-5">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium mb-1">Judul</label>
        <input name="title" value="{{ old('title', $video->title) }}" class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Deskripsi (opsional)</label>
        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description', $video->description) }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Kategori (opsional)</label>
        <select name="category_id" class="w-full border rounded px-3 py-2">
          <option value="">- Pilih -</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ (old('category_id', $video->category_id)==$cat->id)?'selected':'' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Ganti File Video (opsional)</label>
        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg">
        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti. Maks 200MB.</p>
      </div>

      <div class="flex items-center gap-6">
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $video->is_active ? 'checked' : '' }}> <span>Aktif</span></label>
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" {{ $video->is_featured ? 'checked' : '' }}> <span>Featured</span></label>
      </div>

      <div class="pt-2">
        <button class="px-4 py-2 rounded text-white" style="background:#FF6F61">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</body>
</html>