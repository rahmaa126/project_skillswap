<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\Review;
use App\Models\Skill;
use App\Models\SkillMatch;
use App\Models\SwapSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user (pengguna) dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $user->load([
            'profile',
            'offeredSkills.skill.category',
            'wantedSkills.skill.category',
            'skillScores.skill',
        ]);

        // Swap sessions for this user
        $activeSessions = SwapSession::with(['partner', 'requester', 'requesterSkill', 'partnerSkill'])
            ->where(function ($q) use ($user): void {
                $q->where('requester_id', $user->id)
                    ->orWhere('partner_id', $user->id);
            })
            ->whereIn('status', ['accepted', 'ongoing'])
            ->latest('id')
            ->take(5)
            ->get();

        $pendingSessions = SwapSession::with(['requester', 'partner', 'requesterSkill', 'partnerSkill'])
            ->where('partner_id', $user->id)
            ->where('status', 'pending')
            ->latest('id')
            ->get();

        // AI Match Recommendations for this user
        $matches = SkillMatch::with(['userA.profile', 'userB.profile', 'skillA', 'skillB'])
            ->where(function ($q) use ($user): void {
                $q->where('user_a_id', $user->id)
                    ->orWhere('user_b_id', $user->id);
            })
            ->where('status', 'suggested')
            ->orderByDesc('match_score')
            ->take(4)
            ->get();

        // Recent battles
        $battles = Battle::with(['skill', 'player1', 'player2', 'winner'])
            ->where(function ($q) use ($user): void {
                $q->where('player1_id', $user->id)
                    ->orWhere('player2_id', $user->id);
            })
            ->latest('id')
            ->take(4)
            ->get();

        // Recent reviews received
        $reviews = Review::with('reviewer.profile')
            ->where('reviewee_id', $user->id)
            ->where('is_hidden', false)
            ->latest('id')
            ->take(3)
            ->get();

        $totalSkillsAvailable = Skill::where('is_active', true)->count();

        return view('portal.dashboard', compact(
            'user',
            'activeSessions',
            'pendingSessions',
            'matches',
            'battles',
            'reviews',
            'totalSkillsAvailable'
        ));
    }
}
