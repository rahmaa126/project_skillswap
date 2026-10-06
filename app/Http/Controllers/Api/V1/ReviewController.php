<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReviewResource;
use App\Models\Profile;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Review::with(['reviewer.profile', 'reviewee.profile']);

        if ($request->filled('reviewee_id')) {
            $query->where('reviewee_id', $request->input('reviewee_id'));
        }

        if ($request->filled('reviewer_id')) {
            $query->where('reviewer_id', $request->input('reviewer_id'));
        }

        if ($request->filled('session_id')) {
            $query->where('session_id', $request->input('session_id'));
        }

        if (! $request->has('include_hidden')) {
            $query->where('is_hidden', false);
        }

        $reviews = $query->latest('id')->paginate((int) $request->input('per_page', 15));

        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => ['required', 'exists:swap_sessions,id'],
            'reviewer_id' => ['required', 'exists:users,id'],
            'reviewee_id' => ['required', 'exists:users,id', 'different:reviewer_id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = DB::transaction(function () use ($validated): Review {
            $review = Review::create([
                ...$validated,
                'is_hidden' => false,
                'created_at' => now(),
            ]);

            // Update user profile average rating
            $avgRating = Review::where('reviewee_id', $validated['reviewee_id'])
                ->where('is_hidden', false)
                ->avg('rating');

            Profile::where('user_id', $validated['reviewee_id'])->update([
                'avg_rating' => round($avgRating ?? 0, 2),
            ]);

            return $review;
        });

        return (new ReviewResource($review->load(['reviewer', 'reviewee'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): ReviewResource
    {
        $review->load(['reviewer.profile', 'reviewee.profile', 'session']);

        return new ReviewResource($review);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): JsonResponse
    {
        $revieweeId = $review->reviewee_id;
        $review->delete();

        // Recalculate average rating
        $avgRating = Review::where('reviewee_id', $revieweeId)
            ->where('is_hidden', false)
            ->avg('rating');

        Profile::where('user_id', $revieweeId)->update([
            'avg_rating' => round($avgRating ?? 0, 2),
        ]);

        return response()->json([
            'message' => 'Review deleted successfully.',
        ]);
    }
}
