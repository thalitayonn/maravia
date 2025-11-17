@extends('layouts.app')

@section('title', 'Register - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-sky-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-400 to-sky-500 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Bergabung dengan Kami!</h2>
            <p class="text-gray-600">Buat akun baru dan mulai petualangan foto Anda</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 @enderror" 
                               placeholder="Masukkan nama lengkap Anda"
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                        @enderror
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror" 
                               placeholder="Masukkan email Anda"
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
                        <i class="fas fa-lock mr-2 text-blue-500"></i>
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" 
                               placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="flex items-center space-x-2 text-xs">
                            <div id="length-check" class="flex items-center text-gray-400">
                                <i class="fas fa-circle mr-1"></i>
                                <span>Min. 8 karakter</span>
                            </div>
                            <div id="uppercase-check" class="flex items-center text-gray-400">
                                <i class="fas fa-circle mr-1"></i>
                                <span>Huruf besar</span>
                            </div>
                            <div id="number-check" class="flex items-center text-gray-400">
                                <i class="fas fa-circle mr-1"></i>
                                <span>Angka</span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-500"></i>
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" 
                               placeholder="Ulangi password Anda">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password_confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    <div id="password-match" class="mt-2 text-xs text-gray-400 flex items-center">
                        <i class="fas fa-circle mr-1"></i>
                        <span>Password harus sama</span>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        Saya setuju dengan 
                        <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">Syarat & Ketentuan</a> 
                        dan 
                        <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">Kebijakan Privasi</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-rocket text-blue-200 group-hover:text-blue-100"></i>
                        </span>
                        Mulai Petualangan Saya!
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            Masuk ke dashboard Anda
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Benefits Preview -->
        <div class="grid grid-cols-1 gap-4 mt-8">
            <div class="bg-gradient-to-r from-blue-500 to-sky-600 rounded-2xl p-6 text-white">
                <h3 class="font-bold text-lg mb-3 flex items-center">
                    <i class="fas fa-gift mr-2"></i>
                    Keuntungan Bergabung
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-heart text-blue-200"></i>
                        <span class="text-sm">Simpan foto favorit</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-folder text-blue-200"></i>
                        <span class="text-sm">Buat koleksi pribadi</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-trophy text-blue-200"></i>
                        <span class="text-sm">Raih achievement</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-star text-blue-200"></i>
                        <span class="text-sm">Rating & review</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const passwordIcon = document.getElementById(fieldId + '-icon');
        
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

    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        
        // Length check
        const lengthCheck = document.getElementById('length-check');
        if (password.length >= 8) {
            lengthCheck.classList.remove('text-gray-400');
            lengthCheck.classList.add('text-green-500');
            lengthCheck.querySelector('i').classList.remove('fa-circle');
            lengthCheck.querySelector('i').classList.add('fa-check-circle');
        } else {
            lengthCheck.classList.remove('text-green-500');
            lengthCheck.classList.add('text-gray-400');
            lengthCheck.querySelector('i').classList.remove('fa-check-circle');
            lengthCheck.querySelector('i').classList.add('fa-circle');
        }
        
        // Uppercase check
        const uppercaseCheck = document.getElementById('uppercase-check');
        if (/[A-Z]/.test(password)) {
            uppercaseCheck.classList.remove('text-gray-400');
            uppercaseCheck.classList.add('text-green-500');
            uppercaseCheck.querySelector('i').classList.remove('fa-circle');
            uppercaseCheck.querySelector('i').classList.add('fa-check-circle');
        } else {
            uppercaseCheck.classList.remove('text-green-500');
            uppercaseCheck.classList.add('text-gray-400');
            uppercaseCheck.querySelector('i').classList.remove('fa-check-circle');
            uppercaseCheck.querySelector('i').classList.add('fa-circle');
        }
        
        // Number check
        const numberCheck = document.getElementById('number-check');
        if (/\d/.test(password)) {
            numberCheck.classList.remove('text-gray-400');
            numberCheck.classList.add('text-green-500');
            numberCheck.querySelector('i').classList.remove('fa-circle');
            numberCheck.querySelector('i').classList.add('fa-check-circle');
        } else {
            numberCheck.classList.remove('text-green-500');
            numberCheck.classList.add('text-gray-400');
            numberCheck.querySelector('i').classList.remove('fa-check-circle');
            numberCheck.querySelector('i').classList.add('fa-circle');
        }
    });

    // Password confirmation checker
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        const matchCheck = document.getElementById('password-match');
        
        if (confirmPassword && password === confirmPassword) {
            matchCheck.classList.remove('text-gray-400');
            matchCheck.classList.add('text-green-500');
            matchCheck.querySelector('i').classList.remove('fa-circle');
            matchCheck.querySelector('i').classList.add('fa-check-circle');
            matchCheck.querySelector('span').textContent = 'Password cocok!';
        } else if (confirmPassword) {
            matchCheck.classList.remove('text-gray-400', 'text-green-500');
            matchCheck.classList.add('text-red-500');
            matchCheck.querySelector('i').classList.remove('fa-circle', 'fa-check-circle');
            matchCheck.querySelector('i').classList.add('fa-times-circle');
            matchCheck.querySelector('span').textContent = 'Password tidak cocok';
        } else {
            matchCheck.classList.remove('text-green-500', 'text-red-500');
            matchCheck.classList.add('text-gray-400');
            matchCheck.querySelector('i').classList.remove('fa-check-circle', 'fa-times-circle');
            matchCheck.querySelector('i').classList.add('fa-circle');
            matchCheck.querySelector('span').textContent = 'Password harus sama';
        }
    });

    // Add floating animation
    document.addEventListener('DOMContentLoaded', function() {
        const benefitsCard = document.querySelector('.bg-gradient-to-r');
        benefitsCard.classList.add('animate-float');
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
        animation: float 4s ease-in-out infinite;
    }
</style>
@endpush
