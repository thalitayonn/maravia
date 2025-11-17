<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Maravia - Feel. Explore. Remember')</title>
    <meta name="description" content="@yield('description', 'Galeri foto dan dokumentasi kegiatan sekolah, prestasi siswa, dan momen bersejarah')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
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
                        },
                        coral: {
                            400: '#ff7f7f',
                            500: '#F62731',
                            600: '#EE5158',
                        },
                        yellow: {
                            500: '#FBE449',
                            600: '#e8d136',
                        },
                        taupe: {
                            500: '#BCB3AA',
                            600: '#a89f96',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'fade-in': 'fadeIn 0.6s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 5px rgba(246, 39, 49, 0.5)' },
                            '100%': { boxShadow: '0 0 20px rgba(246, 39, 49, 0.8)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    
    @stack('styles')
</head>
<body class="font-sans">
    <!-- Decorative Background Circles -->
    <div class="page-decoration decoration-1"></div>
    <div class="page-decoration decoration-2"></div>
    <div class="page-decoration decoration-3"></div>
    
    <!-- Navigation dengan Glassmorphism Futuristic -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl shadow-lg border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-md border-2 border-white">
                        <!-- Logo Foto Bunga Mawar -->
                        <img src="data:image/svg+xml;base64,{{ base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><defs><radialGradient id="rose" cx="50%" cy="40%" r="60%"><stop offset="0%" stop-color="#fbbf24"/><stop offset="30%" stop-color="#f59e0b"/><stop offset="60%" stop-color="#dc2626"/><stop offset="80%" stop-color="#b91c1c"/><stop offset="100%" stop-color="#7f1d1d"/></radialGradient><radialGradient id="leaf" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#65a30d"/><stop offset="100%" stop-color="#166534"/></radialGradient></defs><ellipse cx="50" cy="85" rx="20" ry="10" fill="url(#leaf)" opacity="0.8"/><ellipse cx="30" cy="80" rx="15" ry="8" fill="url(#leaf)" opacity="0.6"/><path d="M50 15 C25 20, 20 40, 30 65 C35 55, 40 50, 50 55 C60 50, 65 55, 70 65 C80 40, 75 20, 50 15 Z" fill="url(#rose)" opacity="0.95"/><path d="M50 20 C30 25, 28 42, 35 58 C38 50, 42 47, 50 50 C58 47, 62 50, 65 58 C72 42, 70 25, 50 20 Z" fill="url(#rose)" opacity="0.9"/><path d="M50 25 C38 28, 36 40, 42 52 C44 47, 46 45, 50 47 C54 45, 56 47, 58 52 C64 40, 62 28, 50 25 Z" fill="url(#rose)"/><circle cx="50" cy="42" r="6" fill="#fbbf24" opacity="0.9"/><circle cx="50" cy="42" r="3" fill="#f59e0b"/><ellipse cx="46" cy="38" rx="3" ry="4" fill="#fecaca" opacity="0.7"/><ellipse cx="52" cy="40" rx="2" ry="3" fill="#fecaca" opacity="0.5"/></svg>') }}" 
                             alt="Maravia Logo" 
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="font-display font-bold text-xl text-gray-900">Maravia</h1>
                        <p class="text-xs text-gray-600">Galeri Foto</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-coral-500 font-medium transition-colors smooth-scroll">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="#categories" class="text-gray-700 hover:text-coral-500 font-medium transition-colors smooth-scroll">
                        <i class="fas fa-th-large mr-2"></i>Kategori
                    </a>
                    <a href="#recent" class="text-gray-700 hover:text-coral-500 font-medium transition-colors smooth-scroll">
                        <i class="fas fa-clock mr-2"></i>Terbaru
                    </a>
                    <a href="#news" class="text-gray-700 hover:text-coral-500 font-medium transition-colors smooth-scroll">
                        <i class="fas fa-newspaper mr-2"></i>Berita
                    </a>
                    <a href="#contact" class="text-gray-700 hover:text-coral-500 font-medium transition-colors smooth-scroll">
                        <i class="fas fa-envelope mr-2"></i>Kontak
                    </a>
                    
                    @auth
                        <div class="relative group">
                            <button class="flex items-center text-gray-700 hover:text-coral-500 font-medium transition-colors">
                                <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->name }}
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-coral-500 hover:bg-coral-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="mobile-menu-btn text-gray-700 hover:text-primary-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden bg-white/90 backdrop-blur-md border-t border-gray-200">
            <div class="px-4 py-3 space-y-3">
                <a href="#home" class="block text-gray-700 hover:text-coral-500 font-medium smooth-scroll">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="#categories" class="block text-gray-700 hover:text-coral-500 font-medium smooth-scroll">
                    <i class="fas fa-th-large mr-2"></i>Kategori
                </a>
                <a href="#recent" class="block text-gray-700 hover:text-coral-500 font-medium smooth-scroll">
                    <i class="fas fa-clock mr-2"></i>Terbaru
                </a>
                <a href="#news" class="block text-gray-700 hover:text-coral-500 font-medium smooth-scroll">
                    <i class="fas fa-newspaper mr-2"></i>Berita
                </a>
                <a href="#contact" class="block text-gray-700 hover:text-coral-500 font-medium smooth-scroll">
                    <i class="fas fa-envelope mr-2"></i>Kontak
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer id="contact" class="bg-gradient-to-br from-pink-800 via-red-900 to-orange-800 text-white relative overflow-hidden">
        <!-- Decorative pattern overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-pink-700/20 via-transparent to-orange-700/20"></div>
        <!-- Decorative circles -->
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-pink-500/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-orange-500/15 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-2xl">Maravia</h3>
                            <p class="text-white/80">Galeri Foto Digital</p>
                        </div>
                    </div>
                    <p class="text-white/80 mb-4">
                        Platform digital untuk mendokumentasikan dan berbagi momen-momen berharga. 
                        Setiap foto, setiap kenangan, terekam dengan indah di sini.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white/80 hover:text-white transition-colors bg-white/10 w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/20">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-white/80 hover:text-white transition-colors bg-white/10 w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/20">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-white/80 hover:text-white transition-colors bg-white/10 w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/20">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="text-white/80 hover:text-white transition-colors bg-white/10 w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/20">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-home mr-2"></i>Beranda</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-images mr-2"></i>Galeri</a></li>
                        <li><a href="{{ route('testimonials') }}" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-comments mr-2"></i>Testimoni</a></li>
                        <li><a href="{{ route('admin.login') }}" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-lock mr-2"></i>Admin</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-4">Kontak</h4>
                    <ul class="space-y-2 text-white/80">
                        <li class="flex items-start"><i class="fas fa-map-marker-alt mr-2 mt-1"></i><span>Jl. Contoh No. 123, Jakarta</span></li>
                        <li class="flex items-center"><i class="fas fa-phone mr-2"></i>(021) 1234-5678</li>
                        <li class="flex items-center"><i class="fas fa-envelope mr-2"></i>info@maravia.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/20 mt-8 pt-8 text-center text-white/80">
                <p>&copy; {{ date('Y') }} Maravia Gallery. Made with <span class="text-red-300">❤️</span> for everyone.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                document.querySelector('.mobile-menu').classList.toggle('hidden');
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target) {
                    const offset = 80; // Navbar height
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    const mobileMenu = document.querySelector('.mobile-menu');
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
        
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('bg-white/95');
            } else {
                nav.classList.remove('bg-white/95');
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" onerror="(function(){var s=document.createElement('script');s.src='https://unpkg.com/swiper@11/swiper-bundle.min.js';document.head.appendChild(s);}())"></script>
    
    @stack('scripts')
</body>
</html>
