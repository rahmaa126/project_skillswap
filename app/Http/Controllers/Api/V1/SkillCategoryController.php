<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SkillCategoryResource;
use App\Models\SkillCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class SkillCategoryController extends Controller
{
    /**
     * Display a listing of skill categories.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = SkillCategory::withCount('skills')->get();

        return SkillCategoryResource::collection($categories);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:skill_categories,name'],
        ]);

        $category = SkillCategory::create($validated);

        return (new SkillCategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified category with its skills.
     */
    public function show(SkillCategory $category): SkillCategoryResource
    {
        $category->load('skills');
        $category->loadCount('skills');

        return new SkillCategoryResource($category);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, SkillCategory $category): SkillCategoryResource
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('skill_categories')->ignore($category->id)],
        ]);

        $category->update($validated);

        return new SkillCategoryResource($category);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(SkillCategory $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Skill category deleted successfully.',
        ]);
    }
}
