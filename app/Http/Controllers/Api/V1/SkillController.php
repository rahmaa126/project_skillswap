<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SkillResource;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    /**
     * Display a listing of skills.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Skill::with('category');

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $skills = $query->orderBy('name')->paginate((int) $request->input('per_page', 20));

        return SkillResource::collection($skills);
    }

    /**
     * Store a newly created skill.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:skills,name'],
            'category_id' => ['nullable', 'exists:skill_categories,id'],
            'description' => ['nullable', 'string'],
            'min_score' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $skill = Skill::create($validated);

        return (new SkillResource($skill->load('category')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified skill.
     */
    public function show(Skill $skill): SkillResource
    {
        $skill->load('category');

        return new SkillResource($skill);
    }

    /**
     * Update the specified skill.
     */
    public function update(Request $request, Skill $skill): SkillResource
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('skills')->ignore($skill->id)],
            'category_id' => ['nullable', 'exists:skill_categories,id'],
            'description' => ['nullable', 'string'],
            'min_score' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $skill->update($validated);

        return new SkillResource($skill->load('category'));
    }

    /**
     * Remove the specified skill.
     */
    public function destroy(Skill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json([
            'message' => 'Skill deleted successfully.',
        ]);
    }
}
