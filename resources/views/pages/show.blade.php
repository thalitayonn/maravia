@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: $page->excerpt)

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.page-hero {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
    padding: 4rem 0;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

.page-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.15"/><circle cx="80" cy="30" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(1deg); }
}

.page-hero h1 {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    margin-bottom: 1rem;
    animation: slideInUp 1s ease-out;
}

.page-hero .lead {
    font-size: 1.3rem;
    color: rgba(255,255,255,0.9);
    font-weight: 300;
    animation: slideInUp 1s ease-out 0.2s both;
}

.page-meta {
    color: rgba(255,255,255,0.8);
    font-size: 1rem;
    animation: slideInUp 1s ease-out 0.4s both;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.page-container {
    background: white;
    border-radius: 30px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    margin: -2rem auto 3rem;
    position: relative;
    overflow: hidden;
    animation: slideInUp 1s ease-out 0.6s both;
}

.page-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
    background-size: 300% 100%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.content-wrapper {
    padding: 3rem;
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
}

.content-wrapper h1,
.content-wrapper h2,
.content-wrapper h3,
.content-wrapper h4,
.content-wrapper h5,
.content-wrapper h6 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    margin: 2.5rem 0 1.5rem 0;
    position: relative;
}

.content-wrapper h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
}

.content-wrapper p {
    margin-bottom: 1.8rem;
    text-align: justify;
}

.content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    margin: 2rem 0;
    transition: transform 0.3s ease;
}

.content-wrapper img:hover {
    transform: scale(1.02);
}

.content-wrapper blockquote {
    border-left: 5px solid #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
    padding: 2rem;
    margin: 2.5rem 0;
    border-radius: 15px;
    font-style: italic;
    color: #555;
    position: relative;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.1);
}

.content-wrapper blockquote::before {
    content: '"';
    font-size: 4rem;
    color: #667eea;
    position: absolute;
    top: -10px;
    left: 20px;
    font-family: serif;
}

.content-wrapper ul,
.content-wrapper ol {
    padding-left: 0;
    margin-bottom: 2rem;
}

.content-wrapper li {
    margin-bottom: 0.8rem;
    padding-left: 2rem;
    position: relative;
}

.content-wrapper ul li::before {
    content: '✨';
    position: absolute;
    left: 0;
    top: 0;
}

.content-wrapper ol {
    counter-reset: custom-counter;
}

.content-wrapper ol li {
    counter-increment: custom-counter;
}

.content-wrapper ol li::before {
    content: counter(custom-counter);
    position: absolute;
    left: 0;
    top: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
}

.page-footer {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
    padding: 2.5rem 3rem;
    border-radius: 0 0 30px 30px;
    border-top: 1px solid rgba(102, 126, 234, 0.1);
}

.back-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2rem;
    color: white;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    color: white;
}

.share-section {
    text-align: center;
}

.share-title {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.share-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.share-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.share-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s ease;
}

.share-btn:hover::before {
    left: 100%;
}

.share-btn:hover {
    transform: translateY(-3px) scale(1.1);
    color: white;
}

.share-facebook {
    background: linear-gradient(135deg, #3b5998 0%, #8b9dc3 100%);
    box-shadow: 0 5px 15px rgba(59, 89, 152, 0.4);
}

.share-twitter {
    background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%);
    box-shadow: 0 5px 15px rgba(29, 161, 242, 0.4);
}

.share-whatsapp {
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
}

.floating-elements {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
}

.floating-circle {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    animation: floatRandom 15s ease-in-out infinite;
}

.floating-circle:nth-child(1) {
    width: 80px;
    height: 80px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.floating-circle:nth-child(2) {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 10%;
    animation-delay: 5s;
}

.floating-circle:nth-child(3) {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: 10s;
}

@keyframes floatRandom {
    0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
    25% { transform: translateY(-20px) translateX(10px) rotate(90deg); }
    50% { transform: translateY(-10px) translateX(-15px) rotate(180deg); }
    75% { transform: translateY(-30px) translateX(5px) rotate(270deg); }
}

@media (max-width: 768px) {
    .page-hero h1 {
        font-size: 2.5rem;
    }
    
    .page-hero .lead {
        font-size: 1.1rem;
    }
    
    .content-wrapper {
        padding: 2rem 1.5rem;
        font-size: 1rem;
    }
    
    .page-footer {
        padding: 2rem 1.5rem;
        text-align: center;
    }
    
    .share-buttons {
        margin-top: 1.5rem;
    }
}

.scroll-indicator {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: rgba(255,255,255,0.2);
    z-index: 1000;
}

.scroll-progress {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    width: 0%;
    transition: width 0.1s ease;
}
</style>
@endpush

@section('content')
<div class="floating-elements">
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
</div>

<div class="scroll-indicator">
    <div class="scroll-progress" id="scrollProgress"></div>
</div>

<div class="page-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1>{{ $page->title }}</h1>
                
                @if($page->excerpt)
                    <p class="lead">{{ $page->excerpt }}</p>
                @endif
                
                <div class="page-meta">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Published {{ $page->created_at->format('F j, Y') }}
                    @if($page->updated_at->gt($page->created_at))
                        • Updated {{ $page->updated_at->format('F j, Y') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <article class="page-container">
                <div class="content-wrapper">
                    {!! $page->content !!}
                </div>

                <footer class="page-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <a href="{{ route('home') }}" class="back-btn">
                                <i class="fas fa-arrow-left me-2"></i> Back to Gallery
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="share-section">
                                <div class="share-title">Share this page</div>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                                       target="_blank" 
                                       class="share-btn share-facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($page->title) }}" 
                                       target="_blank" 
                                       class="share-btn share-twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($page->title . ' - ' . request()->fullUrl()) }}" 
                                       target="_blank" 
                                       class="share-btn share-whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </article>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Scroll progress indicator
window.addEventListener('scroll', function() {
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrollProgress = (scrollTop / scrollHeight) * 100;
    document.getElementById('scrollProgress').style.width = scrollProgress + '%';
});

// Smooth reveal animation for content elements
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe content elements
document.addEventListener('DOMContentLoaded', function() {
    const contentElements = document.querySelectorAll('.content-wrapper p, .content-wrapper h1, .content-wrapper h2, .content-wrapper h3, .content-wrapper ul, .content-wrapper ol, .content-wrapper blockquote, .content-wrapper img');
    
    contentElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

// Add sparkle effect on scroll
let sparkleTimer;
window.addEventListener('scroll', function() {
    clearTimeout(sparkleTimer);
    sparkleTimer = setTimeout(createSparkle, 100);
});

function createSparkle() {
    const sparkle = document.createElement('div');
    sparkle.innerHTML = '✨';
    sparkle.style.position = 'fixed';
    sparkle.style.left = Math.random() * window.innerWidth + 'px';
    sparkle.style.top = Math.random() * window.innerHeight + 'px';
    sparkle.style.fontSize = '20px';
    sparkle.style.pointerEvents = 'none';
    sparkle.style.zIndex = '1000';
    sparkle.style.animation = 'sparkleFloat 2s ease-out forwards';
    
    document.body.appendChild(sparkle);
    
    setTimeout(() => {
        sparkle.remove();
    }, 2000);
}

// Add sparkle animation CSS
const sparkleStyle = document.createElement('style');
sparkleStyle.textContent = `
    @keyframes sparkleFloat {
        0% {
            opacity: 1;
            transform: translateY(0) scale(0);
        }
        50% {
            opacity: 1;
            transform: translateY(-50px) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateY(-100px) scale(0);
        }
    }
`;
document.head.appendChild(sparkleStyle);
</script>
@endpush
@endsection
