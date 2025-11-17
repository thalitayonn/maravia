@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 rounded-full overflow-hidden mb-6 shadow-lg border-4 border-white">
                <!-- Logo Foto Bunga Mawar -->
                <img src="data:image/svg+xml;base64,{{ base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><defs><radialGradient id="rose" cx="50%" cy="40%" r="60%"><stop offset="0%" stop-color="#fbbf24"/><stop offset="30%" stop-color="#f59e0b"/><stop offset="60%" stop-color="#dc2626"/><stop offset="80%" stop-color="#b91c1c"/><stop offset="100%" stop-color="#7f1d1d"/></radialGradient><radialGradient id="leaf" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#65a30d"/><stop offset="100%" stop-color="#166534"/></radialGradient></defs><ellipse cx="50" cy="85" rx="20" ry="10" fill="url(#leaf)" opacity="0.8"/><ellipse cx="30" cy="80" rx="15" ry="8" fill="url(#leaf)" opacity="0.6"/><path d="M50 15 C25 20, 20 40, 30 65 C35 55, 40 50, 50 55 C60 50, 65 55, 70 65 C80 40, 75 20, 50 15 Z" fill="url(#rose)" opacity="0.95"/><path d="M50 20 C30 25, 28 42, 35 58 C38 50, 42 47, 50 50 C58 47, 62 50, 65 58 C72 42, 70 25, 50 20 Z" fill="url(#rose)" opacity="0.9"/><path d="M50 25 C38 28, 36 40, 42 52 C44 47, 46 45, 50 47 C54 45, 56 47, 58 52 C64 40, 62 28, 50 25 Z" fill="url(#rose)"/><circle cx="50" cy="42" r="6" fill="#fbbf24" opacity="0.9"/><circle cx="50" cy="42" r="3" fill="#f59e0b"/><ellipse cx="46" cy="38" rx="3" ry="4" fill="#fecaca" opacity="0.7"/><ellipse cx="52" cy="40" rx="2" ry="3" fill="#fecaca" opacity="0.5"/></svg>') }}" 
                     alt="Maravia Logo" 
                     class="w-full h-full object-cover">
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Demo Login</h2>
            <p class="text-gray-600">Masuk untuk mencoba fitur galeri foto</p>
            <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                <p class="text-sm text-red-800">
                    <strong>Demo Mode:</strong> Gunakan email dan password apa saja untuk masuk.
                    <br>
                    <span class="text-xs">Contoh: demo@example.com / demo123</span>
                </p>
            </div>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2" style="color: #F62731;"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror" style="--tw-ring-color: #F62731;" 
                               placeholder="Masukkan email apa saja"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                        @enderror
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2" style="color: #F62731;"></i>
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" style="--tw-ring-color: #F62731;" 
                               placeholder="Password apa saja (min. 8 karakter)">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me & Demo Info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" 
                               class="h-4 w-4 border-gray-300 rounded" style="color: #F62731; --tw-ring-color: #F62731;">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white transition-all duration-300 transform hover:scale-105" style="background: linear-gradient(135deg, #F62731, #EE5158); --tw-ring-color: #F62731;">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-red-200 group-hover:text-red-100"></i>
                        </span>
                        Masuk ke Dashboard
                    </button>
                </div>

                <!-- Demo Info -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        <strong>Demo Mode:</strong> Semua data akan disimpan per user berdasarkan email
                    </p>
                </div>
            </form>
        </div>

        <!-- Features Preview -->
        <div class="grid grid-cols-3 gap-4 mt-8">
            <div class="text-center p-4 bg-white/50 backdrop-blur-sm rounded-xl border border-white/20">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-heart text-white text-sm"></i>
                </div>
                <p class="text-xs text-gray-600 font-medium">Simpan Favorit</p>
            </div>
            <div class="text-center p-4 bg-white/50 backdrop-blur-sm rounded-xl border border-white/20">
                <div class="w-10 h-10 bg-gradient-to-r from-sky-400 to-sky-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-trophy text-white text-sm"></i>
                </div>
                <p class="text-xs text-gray-600 font-medium">Raih Achievement</p>
            </div>
            <div class="text-center p-4 bg-white/50 backdrop-blur-sm rounded-xl border border-white/20">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-sky-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-folder text-white text-sm"></i>
                </div>
                <p class="text-xs text-gray-600 font-medium">Buat Koleksi</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }

    // Add floating animation to features
    document.addEventListener('DOMContentLoaded', function() {
        const features = document.querySelectorAll('.grid > div');
        features.forEach((feature, index) => {
            feature.style.animationDelay = `${index * 0.2}s`;
            feature.classList.add('animate-float');
        });
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    .bg-white\/50 {
        background-color: rgba(255, 255, 255, 0.5);
    }
    
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    .border-white\/20 {
        border-color: rgba(255, 255, 255, 0.2);
    }
</style>
@endpush
