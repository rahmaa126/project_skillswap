<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AiLogResource;
use App\Models\AiLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AiLogController extends Controller
{
    /**
     * Display a listing of AI engine logs.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AiLog::with(['user.profile', 'skill']);

        if ($request->filled('feature')) {
            $query->where('feature', $request->input('feature'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $logs = $query->latest('id')->paginate((int) $request->input('per_page', 20));

        return AiLogResource::collection($logs);
    }

    /**
     * Store a newly created AI log.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'feature' => ['required', 'in:smart_matching,battle_question,skill_recommendation,moderation'],
            'user_id' => ['nullable', 'exists:users,id'],
            'skill_id' => ['nullable', 'exists:skills,id'],
            'model' => ['required', 'string', 'max:100'],
            'prompt_tokens' => ['nullable', 'integer', 'min:0'],
            'completion_tokens' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:success,failed'],
            'error_message' => ['nullable', 'string'],
        ]);

        $log = AiLog::create([
            ...$validated,
            'created_at' => now(),
        ]);

        return (new AiLogResource($log->load(['user', 'skill'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified AI log.
     */
    public function show(AiLog $aiLog): AiLogResource
    {
        $aiLog->load(['user.profile', 'skill']);

        return new AiLogResource($aiLog);
    }
}
