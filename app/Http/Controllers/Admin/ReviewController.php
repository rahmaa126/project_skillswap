<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of user reviews.
     */
    public function index(Request $request): View
    {
        $query = Review::with(['reviewer.profile', 'reviewee.profile', 'session']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        if ($request->filled('visibility')) {
            $query->where('is_hidden', $request->input('visibility') === 'hidden');
        }

        $reviews = $query->latest('id')->paginate(10)->withQueryString();
        $totalReviews = Review::count();
        $avgRating = round(Review::avg('rating') ?? 0, 1);
        $hiddenReviews = Review::where('is_hidden', true)->count();

        return view('admin.reviews.index', compact('reviews', 'totalReviews', 'avgRating', 'hiddenReviews'));
    }

    /**
     * Toggle visibility (is_hidden) of a review.
     */
    public function toggleHide(Review $review): RedirectResponse
    {
        $review->update(['is_hidden' => ! $review->is_hidden]);

        // Recalculate average rating for reviewee
        $newAvg = Review::where('reviewee_id', $review->reviewee_id)
            ->where('is_hidden', false)
            ->avg('rating');

        Profile::where('user_id', $review->reviewee_id)->update([
            'avg_rating' => round($newAvg ?? 0, 2),
        ]);

        $state = $review->is_hidden ? 'hidden' : 'visible';

        return back()->with('success', "Review #{$review->id} is now {$state}.");
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $revieweeId = $review->reviewee_id;
        $review->delete();

        // Recalculate average rating for reviewee
        $newAvg = Review::where('reviewee_id', $revieweeId)
            ->where('is_hidden', false)
            ->avg('rating');

        Profile::where('user_id', $revieweeId)->update([
            'avg_rating' => round($newAvg ?? 0, 2),
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
