<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin - Articles</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="bg-gray-50">
  <div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Articles</h1>
      <a href="{{ route('admin.articles.create') }}" class="px-4 py-2 rounded-lg text-white" style="background:#FF6F61">+ Tambah Artikel</a>
    </div>

    @if(session('success'))
      <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <form method="get" class="mb-4">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/konten..." class="border rounded px-3 py-2 w-64">
      <button class="ml-2 px-3 py-2 rounded bg-gray-800 text-white">Cari</button>
    </form>

    <div class="bg-white rounded-xl shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publish</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($articles as $a)
            <tr>
              <td class="px-4 py-3">
                @if($a->cover_url)
                  <img src="{{ $a->cover_url }}" class="w-16 h-10 object-cover rounded" />
                @else
                  <div class="w-16 h-10 bg-gray-200 rounded"></div>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="font-semibold">{{ $a->title }}</div>
                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $a->excerpt }}</div>
              </td>
              <td class="px-4 py-3">
                @if($a->is_published)
                  <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Published</span>
                  <span class="ml-1 text-xs text-gray-500">{{ optional($a->published_at)->format('d M Y') }}</span>
                @else
                  <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-700">Draft</span>
                @endif
              </td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('admin.articles.edit',$a) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                <form action="{{ route('admin.articles.destroy', $a) }}" method="post" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td class="px-4 py-6 text-center text-gray-500" colspan="4">Belum ada artikel</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $articles->links() }}</div>
  </div>
</body>
</html>