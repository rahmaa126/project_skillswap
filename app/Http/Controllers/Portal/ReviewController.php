<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Review;
use App\Models\SwapSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Store a new review for a completed swap session.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'session_id' => ['required', 'exists:swap_sessions,id'],
            'reviewee_id' => ['required', 'exists:users,id', 'different:reviewer_id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $session = SwapSession::findOrFail($validated['session_id']);

        if ($session->requester_id !== $user->id && $session->partner_id !== $user->id) {
            abort(403);
        }

        DB::transaction(function () use ($validated, $user): void {
            Review::create([
                'session_id' => $validated['session_id'],
                'reviewer_id' => $user->id,
                'reviewee_id' => $validated['reviewee_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_hidden' => false,
                'created_at' => now(),
            ]);

            $avgRating = Review::where('reviewee_id', $validated['reviewee_id'])
                ->where('is_hidden', false)
                ->avg('rating');

            Profile::where('user_id', $validated['reviewee_id'])->update([
                'avg_rating' => round($avgRating ?? 0, 2),
            ]);
        });

        return back()->with('success', 'Ulasan dan rating Anda telah berhasil dikirimkan!');
    }
}
