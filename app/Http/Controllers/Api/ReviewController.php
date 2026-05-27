<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'swap_request_id' => ['required', 'exists:swap_requests,id'],
            'reviewee_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $swap = \App\Models\SwapRequest::findOrFail($validated['swap_request_id']);

        if ($swap->status !== 'completed') {
            return response()->json(['message' => 'You can only review completed swaps.'], 422);
        }

        if (! in_array($user->id, [$swap->requester_id, $swap->receiver_id], true)) {
            abort(403, 'You are not part of this swap.');
        }

        if (! in_array($validated['reviewee_id'], [$swap->requester_id, $swap->receiver_id], true)) {
            return response()->json(['message' => 'Reviewee must be the other participant.'], 422);
        }

        if ($validated['reviewee_id'] === $user->id) {
            return response()->json(['message' => 'You cannot review yourself.'], 422);
        }

        $review = Review::create([
            ...$validated,
            'reviewer_id' => $user->id,
        ]);

        $review->load(['reviewer', 'reviewee', 'swapRequest']);

        return response()->json($review, 201);
    }

    public function userReviews(User $user): JsonResponse
    {
        $reviews = $user->reviewsReceived()
            ->with(['reviewer', 'swapRequest'])
            ->latest()
            ->get();

        return response()->json([
            'user_id' => $user->id,
            'average_rating' => $user->averageRating(),
            'reviews' => $reviews,
        ]);
    }
}
