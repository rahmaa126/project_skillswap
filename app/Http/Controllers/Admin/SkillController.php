<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SkillController extends Controller
{
    /**
     * Display a listing of skills.
     */
    public function index(Request $request): View
    {
        $query = Skill::with('category')->withCount(['userSkills', 'scores']);

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $skills = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = SkillCategory::orderBy('name')->get();
        $totalSkills = Skill::count();
        $activeSkills = Skill::where('is_active', true)->count();

        return view('admin.skills.index', compact('skills', 'categories', 'totalSkills', 'activeSkills'));
    }

    /**
     * Show the form for creating a new skill.
     */
    public function create(): View
    {
        $categories = SkillCategory::orderBy('name')->get();

        return view('admin.skills.create', compact('categories'));
    }

    /**
     * Store a newly created skill.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:skills,name'],
            'category_id' => ['nullable', 'exists:skill_categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'min_score' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ]);

        Skill::create([
            ...$validated,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.skills.index')
            ->with('success', "Skill '{$validated['name']}' created successfully.");
    }

    /**
     * Show the form for editing the specified skill.
     */
    public function edit(Skill $skill): View
    {
        $categories = SkillCategory::orderBy('name')->get();

        return view('admin.skills.edit', compact('skill', 'categories'));
    }

    /**
     * Update the specified skill.
     */
    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('skills')->ignore($skill->id)],
            'category_id' => ['nullable', 'exists:skill_categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'min_score' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ]);

        $skill->update($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', "Skill '{$skill->name}' updated successfully.");
    }

    /**
     * Toggle skill active status.
     */
    public function toggleStatus(Skill $skill): RedirectResponse
    {
        $skill->update(['is_active' => ! $skill->is_active]);

        $status = $skill->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Skill {$skill->name} has been {$status}.");
    }

    /**
     * Remove the specified skill.
     */
    public function destroy(Skill $skill): RedirectResponse
    {
        $name = $skill->name;
        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('success', "Skill '{$name}' deleted successfully.");
    }
}
