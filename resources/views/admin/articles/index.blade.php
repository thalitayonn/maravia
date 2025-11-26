@extends('layouts.admin')

@section('title', 'Articles')

@section('content')
<div class="px-6 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white shadow-md hover:shadow-lg transition-all" style="background: var(--color-primary-600);">
            <i class="fas fa-plus"></i>
            Tambah Artikel
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 modern-card p-3 text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="modern-card p-4 mb-4">
        <form method="get" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/konten..." class="w-72 px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-primary-600);">
            <button class="px-4 py-2 rounded-lg text-white" style="background: var(--color-primary-600);">Cari</button>
        </form>
    </div>

    <div class="modern-card overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="text-xs uppercase text-gray-500">
                    <th class="px-5 py-3 text-left">Judul</th>
                    <th class="px-5 py-3 text-left">Publish</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($articles as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $a->title }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xl">{{ $a->excerpt }}</div>
                    </td>
                    <td class="px-5 py-4">
                        @if($a->is_published)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-50 text-green-700 border border-green-200">Published</span>
                            <span class="ml-2 text-xs text-gray-500">{{ optional($a->published_at)->format('d M Y') }}</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700 border border-gray-200">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right space-x-2">
                        <a href="{{ route('admin.articles.edit',$a) }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-white shadow-sm hover:shadow transition" 
                           style="background: #3B82F6;">
                            <i class="fas fa-pen"></i>
                            Edit
                        </a>
                        <form action="{{ route('admin.articles.destroy', $a) }}" method="post" class="inline js-delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-gray-900 shadow-sm hover:shadow transition btn-delete" 
                                    style="background: #FACC15;" data-title="{{ $a->title }}">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-5 py-8 text-center text-gray-500">Belum ada artikel</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $articles->links() }}</div>
</div>

<!-- Delete Confirm Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div id="modalBackdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative mx-auto mt-40 w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-900">Hapus Artikel</h3>
            </div>
            <div class="px-6 py-5 text-gray-700">
                <p id="deleteMessage">Yakin ingin menghapus artikel ini?</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                <button id="btnCancelDelete" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Batal</button>
                <button id="btnConfirmDelete" class="px-4 py-2 rounded text-gray-900" style="background: #FACC15;">Hapus</button>
            </div>
        </div>
    </div>
    <span class="sr-only" aria-hidden="true">Modal</span>
</div>
@push('scripts')
<script>
    (function(){
        let modal = document.getElementById('deleteModal');
        let msg = document.getElementById('deleteMessage');
        let formToSubmit = null;
        function openModal(text){
            msg.textContent = text;
            modal.classList.remove('hidden');
        }
        function closeModal(){
            modal.classList.add('hidden');
            formToSubmit = null;
        }
        document.addEventListener('click', function(e){
            const btn = e.target.closest('.btn-delete');
            if(btn){
                const title = btn.getAttribute('data-title') || 'artikel ini';
                formToSubmit = btn.closest('form');
                openModal(`Hapus "${title}"?`);
            }
        });
        document.getElementById('btnCancelDelete').addEventListener('click', closeModal);
        document.getElementById('modalBackdrop').addEventListener('click', closeModal);
        document.getElementById('btnConfirmDelete').addEventListener('click', function(){
            if(formToSubmit){ formToSubmit.submit(); }
            closeModal();
        });
        document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeModal(); }});
    })();
</script>
@endpush
@endsection