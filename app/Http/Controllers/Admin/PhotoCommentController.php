<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoComment;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PhotoComment::with(['photo', 'photo.category']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'approved':
                    $query->approved();
                    break;
                case 'rejected':
                    $query->rejected();
                    break;
                case 'spam':
                    $query->where('is_spam', true);
                    break;
            }
        }

        // Filter by photo
        if ($request->has('photo') && $request->photo) {
            $query->where('photo_id', $request->photo);
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
            default:
                $query->latest();
        }

        $comments = $query->paginate(20)->withQueryString();
        $photos = Photo::active()->orderBy('title')->get();

        // Get stats
        $stats = [
            'total' => PhotoComment::count(),
            'pending' => PhotoComment::pending()->count(),
            'approved' => PhotoComment::approved()->count(),
            'rejected' => PhotoComment::rejected()->count(),
            'spam' => PhotoComment::where('is_spam', true)->count(),
        ];

        return view('admin.comments.index', compact('comments', 'photos', 'stats'));
    }

    public function show(PhotoComment $comment)
    {
        $comment->load(['photo', 'photo.category']);
        return view('admin.comments.show', compact('comment'));
    }

    public function approve(PhotoComment $comment)
    {
        $comment->status = 'approved';
        $comment->approved_at = now();
        $comment->approved_by = auth()->id();
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment approved successfully!'
        ]);
    }

    public function reject(PhotoComment $comment)
    {
        $comment->status = 'rejected';
        $comment->approved_at = null;
        $comment->approved_by = null;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment rejected successfully!'
        ]);
    }

    public function markSpam(PhotoComment $comment)
    {
        $comment->is_spam = true;
        $comment->status = 'rejected';
        $comment->approved_at = null;
        $comment->approved_by = null;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment marked as spam!'
        ]);
    }

    public function unmarkSpam(PhotoComment $comment)
    {
        $comment->is_spam = false;
        $comment->status = 'pending';
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment unmarked as spam!'
        ]);
    }

    public function destroy(PhotoComment $comment)
    {
        try {
            $comment->delete();

            return redirect()->route('admin.comments.index')
                           ->with('success', 'Comment deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete comment: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,spam,delete',
            'comments' => 'required|array',
            'comments.*' => 'exists:photo_comments,id'
        ]);

        $comments = PhotoComment::whereIn('id', $request->comments);
        $count = $comments->count();

        try {
            switch ($request->action) {
                case 'approve':
                    $comments->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'approved_by' => auth()->id(),
                        'is_spam' => false
                    ]);
                    $message = "{$count} comments approved successfully!";
                    break;
                    
                case 'reject':
                    $comments->update([
                        'status' => 'rejected',
                        'approved_at' => null,
                        'approved_by' => null
                    ]);
                    $message = "{$count} comments rejected successfully!";
                    break;
                    
                case 'spam':
                    $comments->update([
                        'is_spam' => true,
                        'status' => 'rejected',
                        'approved_at' => null,
                        'approved_by' => null
                    ]);
                    $message = "{$count} comments marked as spam!";
                    break;
                    
                case 'delete':
                    $comments->delete();
                    $message = "{$count} comments deleted successfully!";
                    break;
            }

            return redirect()->route('admin.comments.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    public function autoModerate()
    {
        try {
            $spamKeywords = ['spam', 'scam', 'fake', 'bot', 'click here', 'buy now', 'free money'];
            $spamCount = 0;
            
            // Mark comments with spam keywords
            foreach ($spamKeywords as $keyword) {
                $spamComments = PhotoComment::where('comment', 'like', "%{$keyword}%")
                                          ->where('is_spam', false)
                                          ->get();
                
                foreach ($spamComments as $comment) {
                    $comment->is_spam = true;
                    $comment->status = 'rejected';
                    $comment->save();
                    $spamCount++;
                }
            }

            // Auto-approve comments from trusted users (users with multiple approved comments)
            $trustedUsers = PhotoComment::select('email')
                                      ->where('status', 'approved')
                                      ->groupBy('email')
                                      ->havingRaw('COUNT(*) >= 3')
                                      ->pluck('email');

            $autoApproved = PhotoComment::whereIn('email', $trustedUsers)
                                      ->where('status', 'pending')
                                      ->where('is_spam', false)
                                      ->update([
                                          'status' => 'approved',
                                          'approved_at' => now(),
                                          'approved_by' => auth()->id()
                                      ]);

            return response()->json([
                'success' => true,
                'message' => "Auto-moderation complete! {$spamCount} spam comments detected, {$autoApproved} comments auto-approved."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auto-moderation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
