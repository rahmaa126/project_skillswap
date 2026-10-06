<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BattleResource;
use App\Models\Battle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BattleController extends Controller
{
    /**
     * Display a listing of skill battles.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Battle::with(['skill.category', 'player1.profile', 'player2.profile', 'winner.profile']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('skill_id')) {
            $query->where('skill_id', $request->input('skill_id'));
        }

        if ($request->filled('user_id')) {
            $userId = $request->input('user_id');
            $query->where(function ($q) use ($userId): void {
                $q->where('player1_id', $userId)
                    ->orWhere('player2_id', $userId);
            });
        }

        $battles = $query->latest('id')->paginate((int) $request->input('per_page', 15));

        return BattleResource::collection($battles);
    }

    /**
     * Store a newly created battle.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'player1_id' => ['required', 'exists:users,id'],
            'player2_id' => ['required', 'exists:users,id', 'different:player1_id'],
            'match_id' => ['nullable', 'exists:matches,id'],
        ]);

        $battle = Battle::create([
            ...$validated,
            'status' => 'waiting',
            'created_at' => now(),
        ]);

        return (new BattleResource($battle->load(['skill', 'player1', 'player2'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified battle.
     */
    public function show(Battle $battle): BattleResource
    {
        $battle->load([
            'skill.category',
            'player1.profile',
            'player2.profile',
            'winner.profile',
            'answers.question',
            'answers.user',
        ]);

        return new BattleResource($battle);
    }

    /**
     * Update the specified battle (e.g. record winner, complete battle).
     */
    public function update(Request $request, Battle $battle): BattleResource
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'in:waiting,ongoing,finished,abandoned'],
            'winner_id' => ['nullable', 'exists:users,id'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date'],
        ]);

        if (isset($validated['status']) && $validated['status'] === 'finished' && ! isset($validated['finished_at'])) {
            $validated['finished_at'] = now();
        }

        $battle->update($validated);

        return new BattleResource($battle->fresh(['skill', 'player1', 'player2', 'winner']));
    }
}
