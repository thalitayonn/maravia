<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::approved()
            ->latest()
            ->paginate(12);
            
        return view('testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ]);

        $testimonial = Testimonial::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_approved' => false,
        ]);

        // Calculate spam score
        $testimonial->calculateSpamScore();

        return redirect()->back()->with('success', 'Terima kasih! Testimoni Anda telah dikirim dan akan ditinjau oleh admin.');
    }
}
