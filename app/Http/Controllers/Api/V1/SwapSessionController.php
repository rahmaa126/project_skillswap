<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SwapSessionResource;
use App\Models\SwapSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SwapSessionController extends Controller
{
    /**
     * Display a listing of swap sessions.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SwapSession::with(['requester.profile', 'partner.profile', 'requesterSkill', 'partnerSkill']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('user_id')) {
            $userId = $request->input('user_id');
            $query->where(function ($q) use ($userId): void {
                $q->where('requester_id', $userId)
                    ->orWhere('partner_id', $userId);
            });
        }

        $sessions = $query->latest('id')->paginate((int) $request->input('per_page', 15));

        return SwapSessionResource::collection($sessions);
    }

    /**
     * Store a newly created swap session.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'match_id' => ['nullable', 'exists:matches,id'],
            'requester_id' => ['required', 'exists:users,id'],
            'partner_id' => ['required', 'exists:users,id', 'different:requester_id'],
            'requester_skill_id' => ['required', 'exists:skills,id'],
            'partner_skill_id' => ['required', 'exists:skills,id'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $session = SwapSession::create([
            ...$validated,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return (new SwapSessionResource($session->load(['requester', 'partner', 'requesterSkill', 'partnerSkill'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified swap session.
     */
    public function show(SwapSession $swapSession): SwapSessionResource
    {
        $swapSession->load([
            'requester.profile',
            'partner.profile',
            'requesterSkill',
            'partnerSkill',
            'videoCalls',
            'reviews.reviewer',
            'reports',
        ]);

        return new SwapSessionResource($swapSession);
    }

    /**
     * Update the specified swap session.
     */
    public function update(Request $request, SwapSession $swapSession): SwapSessionResource
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'in:pending,accepted,ongoing,completed,cancelled'],
            'scheduled_at' => ['nullable', 'date'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'ongoing' && ! $swapSession->started_at && ! isset($validated['started_at'])) {
                $validated['started_at'] = now();
            }
            if ($validated['status'] === 'completed' && ! $swapSession->completed_at && ! isset($validated['completed_at'])) {
                $validated['completed_at'] = now();
            }
        }

        $swapSession->update($validated);

        return new SwapSessionResource($swapSession->fresh(['requester', 'partner', 'requesterSkill', 'partnerSkill']));
    }

    /**
     * Remove the specified swap session.
     */
    public function destroy(SwapSession $swapSession): JsonResponse
    {
        $swapSession->delete();

        return response()->json([
            'message' => 'Swap session deleted successfully.',
        ]);
    }
}
