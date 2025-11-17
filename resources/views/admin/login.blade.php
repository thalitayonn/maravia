<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Maravia</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'display': ['Space Grotesk', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff5f5',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#F62731',
                            600: '#EE5158',
                            700: '#d41f28',
                            800: '#be1e2d',
                            900: '#881923',
                        },
                        school: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#8FBAB1',
                            600: '#7aa59d',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 5px rgba(246, 39, 49, 0.5)' },
                            '100%': { boxShadow: '0 0 20px rgba(246, 39, 49, 0.8)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-pink-800 via-red-900 to-orange-800"></div>
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Animated background elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-white/10 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-40 left-20 w-24 h-24 bg-white/10 rounded-full animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-20 right-10 w-12 h-12 bg-white/10 rounded-full animate-float" style="animation-delay: 0.5s;"></div>
        </div>
        
        <!-- Login Card -->
        <div class="relative z-10 w-full max-w-md mx-4">
            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center animate-glow ring-2 ring-white/30">
                        <i class="fas fa-user-shield text-3xl text-white"></i>
                    </div>
                    <h1 class="font-display font-bold text-3xl text-white">Masuk Admin</h1>
                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs text-gray-100">Dashboard Galeri Sekolah</div>
                </div>
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-white/80"></i>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-white/30 border border-white/40 rounded-xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/60 focus:border-transparent transition-all duration-300"
                                   placeholder="admin@sekolah.sch.id">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-white/80"></i>
                            </span>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-11 pr-10 py-3 bg-white/30 border border-white/40 rounded-xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/60 focus:border-transparent transition-all duration-300"
                                   placeholder="••••••••">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                <i class="fas fa-eye text-white/80 hover:text-white transition-colors"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" 
                               class="w-4 h-4 text-primary-600 bg-white/20 border-white/30 rounded focus:ring-white/50 focus:ring-2">
                        <label for="remember" class="ml-2 text-sm text-white">
                            Ingat saya
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 px-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white/40 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2 text-white/90"></i>
                        Masuk Admin
                    </button>
                </form>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-white/80 hover:text-white transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="mt-6 text-center">
                <div class="glass-effect rounded-2xl p-4">
                    <div class="flex items-center justify-center text-yellow-300 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="text-sm font-medium">Keamanan</span>
                    </div>
                    <p class="text-xs text-white/90">
                        Halaman ini dilindungi dengan enkripsi SSL. 
                        Jangan bagikan kredensial login Anda kepada siapa pun.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Add loading state to form
        document.querySelector('form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            button.disabled = true;
        });
    </script>
</body>
</html>
