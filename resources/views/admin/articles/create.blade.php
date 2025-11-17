<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah Artikel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="bg-gray-50">
  <div class="max-w-3xl mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Tambah Artikel</h1>
      <a href="{{ route('admin.articles.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
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

    <form action="{{ route('admin.articles.store') }}" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Judul</label>
        <input name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Ringkasan (maks 500)</label>
        <input name="excerpt" value="{{ old('excerpt') }}" class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Konten (HTML diperbolehkan)</label>
        <textarea name="content" rows="10" class="w-full border rounded px-3 py-2">{{ old('content') }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Cover (opsional)</label>
        <input type="file" name="cover" accept="image/*">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_published" value="1"> <span>Publish sekarang</span></label>
        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Publish (opsional)</label>
          <input type="datetime-local" name="published_at" class="w-full border rounded px-3 py-2" value="{{ old('published_at') }}">
        </div>
      </div>

      <div class="pt-2">
        <button class="px-4 py-2 rounded text-white" style="background:#FF6F61">Simpan</button>
      </div>
    </form>
  </div>
</body>
</html>