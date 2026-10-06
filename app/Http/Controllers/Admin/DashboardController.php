<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\Review;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SwapSession;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the AdminLTE 4 dashboard.
     */
    public function index(): View
    {
        $usersCount = User::count();
        $skillsCount = Skill::count();
        $categoriesCount = SkillCategory::count();
        $sessionsCount = SwapSession::count();
        $battlesCount = Battle::count();
        $reviewsCount = Review::count();

        $recentUsers = User::latest('id')->take(6)->get();
        $recentSkills = Skill::with('category')->latest('id')->take(6)->get();
        $recentSessions = SwapSession::with(['requester', 'partner'])->latest('id')->take(5)->get();
        $categories = SkillCategory::withCount('skills')->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'skillsCount',
            'categoriesCount',
            'sessionsCount',
            'battlesCount',
            'reviewsCount',
            'recentUsers',
            'recentSkills',
            'recentSessions',
            'categories'
        ));
    }
}
