<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah Video</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="bg-gray-50">
  <div class="max-w-3xl mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Tambah Video</h1>
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

    <form action="{{ route('admin.videos.store') }}" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Judul</label>
        <input name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Deskripsi (opsional)</label>
        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Kategori (opsional)</label>
        <select name="category_id" class="w-full border rounded px-3 py-2">
          <option value="">- Pilih -</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">File Video (MP4/WebM/Ogg, maks 200MB)</label>
        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg" required>
      </div>

      <div class="flex items-center gap-6">
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> <span>Aktif</span></label>
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_featured" value="1"> <span>Featured</span></label>
      </div>

      <div class="pt-2">
        <button class="px-4 py-2 rounded text-white" style="background:#FF6F61">Simpan</button>
      </div>
    </form>
  </div>
</body>
</html>