<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin - Videos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="bg-gray-50">
  <div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Videos</h1>
      <a href="{{ route('admin.videos.create') }}" class="px-4 py-2 rounded-lg text-white" style="background:#FF6F61">+ Tambah Video</a>
    </div>

    @if(session('success'))
      <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    <form method="get" class="mb-4">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/deskripsi..." class="border rounded px-3 py-2 w-64">
      <button class="ml-2 px-3 py-2 rounded bg-gray-800 text-white">Cari</button>
    </form>

    <div class="bg-white rounded-xl shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poster</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($videos as $video)
            <tr>
              <td class="px-4 py-3">
                @if($video->poster_url)
                  <img src="{{ $video->poster_url }}" alt="poster" class="w-16 h-10 object-cover rounded" />
                @else
                  <div class="w-16 h-10 bg-gray-200 rounded"></div>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="font-semibold">{{ $video->title }}</div>
                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $video->description }}</div>
              </td>
              <td class="px-4 py-3">{{ $video->category->name ?? '-' }}</td>
              <td class="px-4 py-3">{{ $video->duration ? gmdate('i:s', (int)$video->duration) : '-' }}</td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 text-xs rounded {{ $video->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">{{ $video->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                @if($video->is_featured)
                  <span class="ml-1 px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Featured</span>
                @endif
              </td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('admin.videos.edit',$video) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                <form action="{{ route('admin.videos.destroy', $video) }}" method="post" class="inline" onsubmit="return confirm('Hapus video ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td class="px-4 py-6 text-center text-gray-500" colspan="6">Belum ada video</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $videos->links() }}</div>
  </div>
</body>
</html>