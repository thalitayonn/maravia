<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoComment;
use App\Models\CommentReaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $commentData = [
            'photo_id' => $photo->id,
            'comment' => $request->comment,
            'status' => 'approved' // Auto-approve for now, can be changed to 'pending'
        ];

        if (Auth::check()) {
            $commentData['user_id'] = Auth::id();
            $commentData['name'] = Auth::user()->name;
            $commentData['email'] = Auth::user()->email;
        } else {
            $commentData['name'] = $request->name;
            $commentData['email'] = $request->email;
        }

        $comment = PhotoComment::create($commentData);
        $comment->load(['user', 'reactions']);

        // Record activity for authenticated users
        if (Auth::check()) {
            Auth::user()->recordActivity('comment', $photo);
        }

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan!',
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'author_name' => $comment->author_name,
                'author_avatar' => $comment->author_avatar,
                'created_at' => $comment->created_at->diffForHumans(),
                'reaction_counts' => $comment->getReactionCounts(),
                'user_reaction' => Auth::check() ? $comment->getUserReaction(Auth::id()) : null,
                'total_reactions' => $comment->getTotalReactionsCount()
            ]
        ]);
    }

    public function getComments(Photo $photo): JsonResponse
    {
        $comments = $photo->comments()
            ->approved()
            ->with(['user', 'reactions'])
            ->latest()
            ->paginate(10);

        $commentsData = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'author_name' => $comment->author_name,
                'author_avatar' => $comment->author_avatar,
                'created_at' => $comment->created_at->diffForHumans(),
                'reaction_counts' => $comment->getReactionCounts(),
                'user_reaction' => Auth::check() ? $comment->getUserReaction(Auth::id()) : null,
                'total_reactions' => $comment->getTotalReactionsCount()
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $commentsData,
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'total' => $comments->total()
            ]
        ]);
    }

    public function toggleReaction(Request $request, PhotoComment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk memberikan reaksi'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'reaction_type' => 'required|string|in:like,love,laugh,wow,sad,angry'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $reactionType = $request->reaction_type;

        // Check if user already has a reaction on this comment
        $existingReaction = CommentReaction::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->reaction_type === $reactionType) {
                // Remove reaction if same type
                $existingReaction->delete();
                $message = 'Reaksi dihapus';
                $userReaction = null;
            } else {
                // Update reaction type
                $existingReaction->update(['reaction_type' => $reactionType]);
                $message = 'Reaksi diperbarui';
                $userReaction = $reactionType;
            }
        } else {
            // Create new reaction
            CommentReaction::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
                'reaction_type' => $reactionType
            ]);
            $message = 'Reaksi ditambahkan';
            $userReaction = $reactionType;
        }

        // Record activity
        $user->recordActivity('react_comment', $comment, ['reaction_type' => $reactionType]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'reaction_counts' => $comment->getReactionCounts(),
            'user_reaction' => $userReaction,
            'total_reactions' => $comment->getTotalReactionsCount()
        ]);
    }

    public function destroy(PhotoComment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();

        // Check if user owns the comment or is admin
        if ($comment->user_id !== $user->id && !$user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus'
        ]);
    }
}
