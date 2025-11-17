@extends('layouts.admin')

@section('title', 'Backup & Restore')

@section('content')
<div class="admin-page space-y-8">
    <!-- Header -->
    <div class="admin-header">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl lg:text-3xl font-bold">Backup & Restore</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Create and manage system backups</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="createBackup()" 
                            class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                            style="background: #FF6F61; color: white;">
                        <i class="fas fa-cloud-download-alt mr-2"></i>
                        Create Backup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg mb-3">Secure your data with automated backups and easy restore</p>
                <div class="flex items-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-shield-alt mr-2" style="color: #FEEA77;"></i>
                        Data Protection
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-2" style="background: #FEEA77;"></div>
                        <i class="fas fa-history mr-2" style="color: #FEEA77;"></i>
                        Easy Restore
                    </div>
                </div>
            </div>
            <div class="text-5xl" style="color: #FEEA77;">
                <i class="fas fa-database"></i>
            </div>
        </div>
    </div>

        <!-- Backup Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
                <div class="modern-card-content p-6 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-database text-white text-2xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold text-lg mb-2">Database Backup</h3>
                    <p class="text-gray-600 text-sm">All data including photos, categories, testimonials, and user accounts</p>
                </div>
            </div>
            
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-amber-50">
                <div class="modern-card-content p-6 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-folder text-white text-2xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold text-lg mb-2">Files Backup</h3>
                    <p class="text-gray-600 text-sm">All uploaded images, thumbnails, and storage files</p>
                </div>
            </div>
            
            <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-yellow-50">
                <div class="modern-card-content p-6 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-shield-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold text-lg mb-2">Complete Backup</h3>
                    <p class="text-gray-600 text-sm">Full system backup including database and all files</p>
                </div>
            </div>
        </div>

        <!-- Restore Section -->
        <div class="modern-card mb-8 hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
            <div class="modern-card-header border-b border-orange-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="modern-card-title text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                            Restore from Backup
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Upload and restore a previous backup</p>
                    </div>
                    <div class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-upload mr-1"></i>
                        File Upload
                    </div>
                </div>
            </div>
            <div class="modern-card-content pt-8">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-1">
                        <input type="file" id="restore-file" accept=".zip" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-400 file:text-white hover:file:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-300">
                    </div>
                    <button onclick="restoreBackup()" class="bg-gradient-to-r from-orange-400 to-red-400 hover:from-orange-500 hover:to-red-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center">
                        <i class="fas fa-history mr-2"></i>
                        Restore
                    </button>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                        </div>
                        <div>
                            <h4 class="text-yellow-800 font-semibold text-sm">Warning</h4>
                            <p class="text-yellow-700 text-sm mt-1">Restoring will overwrite all current data. Make sure to create a backup first!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Backups -->
        <div class="modern-card hover:shadow-xl transition-all duration-300 border-0 bg-gradient-to-br from-white to-orange-50">
            <div class="modern-card-header border-b border-orange-100 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="modern-card-title text-xl font-bold flex items-center">
                            <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                            Existing Backups
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Manage your backup files</p>
                    </div>
                    @if(count($backups) > 0)
                        <div class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-archive mr-1"></i>
                            {{ count($backups) }} Backups
                        </div>
                    @endif
                </div>
            </div>

            @if(count($backups) > 0)
                <div class="modern-card-content pt-8">
                    <div class="space-y-4">
                        @foreach($backups as $backup)
                            <div class="backup-item group relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-200 to-amber-200 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                                <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-6 border border-orange-100 hover:border-orange-200 transition-all duration-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-cloud-download-alt text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-gray-800 font-bold text-lg">{{ $backup['filename'] }}</h4>
                                                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-500">
                                                    <span class="bg-gray-100 px-3 py-1 rounded-lg">
                                                        <i class="fas fa-weight-hanging mr-1"></i>
                                                        {{ $backup['size'] }}
                                                    </span>
                                                    <span class="bg-gray-100 px-3 py-1 rounded-lg">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $backup['created_at'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.backup.download', $backup['filename']) }}" 
                                               class="bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                <i class="fas fa-download mr-1"></i>
                                                Download
                                            </a>
                                            <button onclick="deleteBackup('{{ $backup['filename'] }}')" 
                                                    class="bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="bg-gradient-to-br from-orange-100 to-amber-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-cloud-download-alt text-6xl text-orange-400"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">No Backups Found</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Create your first backup to secure your data.</p>
                    <button onclick="createBackup()" class="bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Backup
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="modern-card border-0 bg-white/90 backdrop-blur-xl p-8 text-center max-w-md mx-4">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-amber-400 rounded-full flex items-center justify-center mx-auto mb-6">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
            </div>
            <h3 class="text-gray-800 font-bold text-xl mb-2" id="loading-title">Processing...</h3>
            <p class="text-gray-600" id="loading-message">Please wait while we process your request.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLoading(title, message) {
    document.getElementById('loading-title').textContent = title;
    document.getElementById('loading-message').textContent = message;
    document.getElementById('loading-modal').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-modal').classList.add('hidden');
}

function createBackup() {
    showLoading('Creating Backup', 'Please wait while we create a complete backup of your system...');
    
    fetch('{{ route("admin.backup.create") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert('Backup created successfully!');
            location.reload();
        } else {
            alert('Failed to create backup: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('An error occurred while creating backup');
    });
}

function restoreBackup() {
    const fileInput = document.getElementById('restore-file');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Please select a backup file to restore');
        return;
    }
    
    if (!confirm('Are you sure you want to restore this backup? This will overwrite all current data and cannot be undone!')) {
        return;
    }
    
    showLoading('Restoring Backup', 'Please wait while we restore your backup. This may take several minutes...');
    
    const formData = new FormData();
    formData.append('backup_file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    fetch('{{ route("admin.backup.restore") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert('Backup restored successfully! The page will now refresh.');
            location.reload();
        } else {
            alert('Failed to restore backup: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('An error occurred while restoring backup');
    });
}

function deleteBackup(filename) {
    if (!confirm('Are you sure you want to delete this backup? This action cannot be undone.')) {
        return;
    }
    
    fetch(`/admin/backup/${filename}/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Backup deleted successfully!');
            location.reload();
        } else {
            alert('Failed to delete backup: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting backup');
    });
}
</script>
@endpush
@endsection
