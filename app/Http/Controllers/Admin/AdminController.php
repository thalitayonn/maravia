<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\PhotoView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function showLogin()
    {
        // If user is already authenticated, redirect to admin dashboard
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }

    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_photos' => Photo::count(),
            'photos_this_month' => Photo::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
            'total_categories' => Category::active()->count(),
            'total_views' => PhotoView::count(),
            'views_today' => PhotoView::whereDate('viewed_at', today())->count(),
            'total_testimonials' => Testimonial::count(),
            'pending_testimonials' => Testimonial::pending()->count(),
            'featured_photos' => Photo::where('is_featured', true)->count(),
        ];

        // Get recent photos
        $recent_photos = Photo::with(['category'])
                             ->latest()
                             ->limit(5)
                             ->get();

        // Get chart data for the last 7 days
        $chart_data = $this->getViewsChartData();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact('stats', 'recent_photos', 'chart_data', 'recentActivities'));
    }

    private function getViewsChartData()
    {
        $labels = [];
        $views = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');
            
            $dayViews = PhotoView::whereDate('viewed_at', $date->toDateString())
                               ->count();
            $views[] = $dayViews;
        }
        
        return [
            'labels' => $labels,
            'views' => $views
        ];
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Get recent photos (last 10)
        $recentPhotos = Photo::with('uploader')->latest()->limit(10)->get();
        foreach ($recentPhotos as $photo) {
            $activities[] = [
                'title' => 'Foto baru ditambahkan: ' . $photo->title,
                'time' => $photo->created_at->diffForHumans(),
                'icon' => 'fa-image',
                'color' => 'blue',
                'created_at' => $photo->created_at,
            ];
        }

        // Get recent categories
        $recentCategories = Category::latest()->limit(5)->get();
        foreach ($recentCategories as $category) {
            $activities[] = [
                'title' => 'Kategori baru: ' . $category->name,
                'time' => $category->created_at->diffForHumans(),
                'icon' => 'fa-folder',
                'color' => 'green',
                'created_at' => $category->created_at,
            ];
        }

        // Get recent testimonials
        $recentTestimonials = Testimonial::latest()->limit(5)->get();
        foreach ($recentTestimonials as $testimonial) {
            $activities[] = [
                'title' => 'Testimoni baru dari: ' . $testimonial->name,
                'time' => $testimonial->created_at->diffForHumans(),
                'icon' => 'fa-comment',
                'color' => 'yellow',
                'created_at' => $testimonial->created_at,
            ];
        }

        // Sort by created_at descending and take 10
        usort($activities, function($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_slice($activities, 0, 10);
    }

    public function admins()
    {
        $admins = User::orderBy('name')->get();
        return view('admin.admins', compact('admins'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins')->with('success', 'Admin created successfully!');
    }

    public function deleteAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.admins')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('admin.admins')->with('success', 'Admin deleted successfully!');
    }
}
