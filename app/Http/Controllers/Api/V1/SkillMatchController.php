<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SkillMatchResource;
use App\Models\SkillMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkillMatchController extends Controller
{
    /**
     * Display a listing of skill matches.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SkillMatch::with(['userA.profile', 'userB.profile', 'skillA', 'skillB']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('user_id')) {
            $userId = $request->input('user_id');
            $query->where(function ($q) use ($userId): void {
                $q->where('user_a_id', $userId)
                    ->orWhere('user_b_id', $userId);
            });
        }

        if ($request->filled('match_source')) {
            $query->where('match_source', $request->input('match_source'));
        }

        $matches = $query->latest('id')->paginate((int) $request->input('per_page', 15));

        return SkillMatchResource::collection($matches);
    }

    /**
     * Store a newly created skill match.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_a_id' => ['required', 'exists:users,id'],
            'user_b_id' => ['required', 'exists:users,id', 'different:user_a_id'],
            'skill_a_id' => ['required', 'exists:skills,id'],
            'skill_b_id' => ['required', 'exists:skills,id'],
            'match_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'match_source' => ['required', 'in:manual,ai_knn,ai_llm'],
            'ai_reason' => ['nullable', 'string'],
            'score_breakdown' => ['nullable', 'array'],
        ]);

        $match = SkillMatch::create([
            ...$validated,
            'status' => 'suggested',
            'created_at' => now(),
        ]);

        return (new SkillMatchResource($match->load(['userA', 'userB', 'skillA', 'skillB'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified skill match.
     */
    public function show(SkillMatch $match): SkillMatchResource
    {
        $match->load(['userA.profile', 'userB.profile', 'skillA', 'skillB', 'swapSessions', 'battles']);

        return new SkillMatchResource($match);
    }

    /**
     * Update the specified skill match status.
     */
    public function update(Request $request, SkillMatch $match): SkillMatchResource
    {
        $validated = $request->validate([
            'status' => ['required', 'in:suggested,accepted,rejected,expired'],
        ]);

        $match->update($validated);

        return new SkillMatchResource($match->fresh(['userA', 'userB', 'skillA', 'skillB']));
    }
}
