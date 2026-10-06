<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillExploreController extends Controller
{
    /**
     * Browse skills available in SkillSwap.
     */
    public function index(Request $request): View
    {
        $query = Skill::with(['category', 'userSkills.user.profile'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $skills = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = SkillCategory::withCount('skills')->orderBy('name')->get();

        return view('portal.skills.index', compact('skills', 'categories'));
    }

    /**
     * Show detailed skill info and members teaching it.
     */
    public function show(Skill $skill): View
    {
        $skill->load(['category', 'userSkills.user.profile']);

        $teachers = $skill->userSkills()
            ->where('type', 'offered')
            ->with('user.profile')
            ->get()
            ->pluck('user')
            ->unique('id');

        $learners = $skill->userSkills()
            ->where('type', 'wanted')
            ->with('user.profile')
            ->get()
            ->pluck('user')
            ->unique('id');

        return view('portal.skills.show', compact('skill', 'teachers', 'learners'));
    }
}
