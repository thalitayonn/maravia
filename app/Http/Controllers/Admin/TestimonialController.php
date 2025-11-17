<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        // Middleware handled by route group
    }

    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_approved', $request->status);
        }

        // Filter by spam score
        if ($request->has('spam') && $request->spam !== '') {
            if ($request->spam === 'high') {
                $query->where('spam_score', '>=', 0.7);
            } elseif ($request->spam === 'medium') {
                $query->whereBetween('spam_score', [0.3, 0.69]);
            } elseif ($request->spam === 'low') {
                $query->where('spam_score', '<', 0.3);
            }
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'spam':
                $query->orderBy('spam_score', 'desc');
                break;
            default:
                $query->latest();
        }

        $testimonials = $query->paginate(15)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->is_approved = true;
        $testimonial->approved_at = now();
        $testimonial->approved_by = auth()->id();
        $testimonial->save();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial approved successfully!'
        ]);
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->is_approved = false;
        $testimonial->approved_at = null;
        $testimonial->approved_by = null;
        $testimonial->save();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial rejected successfully!'
        ]);
    }

    public function destroy(Testimonial $testimonial)
    {
        try {
            $testimonial->delete();

            return redirect()->route('admin.testimonials.index')
                           ->with('success', 'Testimonial deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete testimonial: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'testimonials' => 'required|array',
            'testimonials.*' => 'exists:testimonials,id'
        ]);

        $testimonials = Testimonial::whereIn('id', $request->testimonials);
        $count = $testimonials->count();

        try {
            switch ($request->action) {
                case 'approve':
                    $testimonials->update([
                        'is_approved' => true,
                        'approved_at' => now(),
                        'approved_by' => auth()->id()
                    ]);
                    $message = "{$count} testimonials approved successfully!";
                    break;
                    
                case 'reject':
                    $testimonials->update([
                        'is_approved' => false,
                        'approved_at' => null,
                        'approved_by' => null
                    ]);
                    $message = "{$count} testimonials rejected successfully!";
                    break;
                    
                case 'delete':
                    $testimonials->delete();
                    $message = "{$count} testimonials deleted successfully!";
                    break;
            }

            return redirect()->route('admin.testimonials.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}
