<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BattleController extends Controller
{
    /**
     * Display skill battles arena for the user.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $myBattles = Battle::with(['skill', 'player1', 'player2', 'winner'])
            ->where(function ($q) use ($user): void {
                $q->where('player1_id', $user->id)
                    ->orWhere('player2_id', $user->id);
            })
            ->latest('id')
            ->get();

        $openChallenges = Battle::with(['skill', 'player1'])
            ->where('player1_id', '!=', $user->id)
            ->where('status', 'waiting')
            ->latest('id')
            ->get();

        $availableSkills = Skill::where('is_active', true)->orderBy('name')->get();
        $otherUsers = User::where('id', '!=', $user->id)->where('is_active', true)->get();

        return view('portal.battles.index', compact('user', 'myBattles', 'openChallenges', 'availableSkills', 'otherUsers'));
    }

    /**
     * Create a new battle challenge.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'player2_id' => ['nullable', 'exists:users,id', 'different:player1_id'],
        ]);

        $battle = Battle::create([
            'skill_id' => $validated['skill_id'],
            'player1_id' => $user->id,
            'player2_id' => $validated['player2_id'] ?? null,
            'status' => 'waiting',
            'created_at' => now(),
        ]);

        return redirect()->route('portal.battles.show', $battle)
            ->with('success', 'Tantangan battle skill berhasil dibuat! Menunggu lawan bertanding.');
    }

    /**
     * Display the battle arena.
     */
    public function show(Battle $battle): View
    {
        $battle->load(['skill', 'player1.profile', 'player2.profile', 'winner', 'answers.question']);

        return view('portal.battles.show', compact('battle'));
    }

    /**
     * Join an open battle challenge.
     */
    public function join(Battle $battle): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($battle->player1_id === $user->id || $battle->player2_id !== null) {
            return back()->with('error', 'Tidak dapat bergabung dalam tantangan ini.');
        }

        $battle->update([
            'player2_id' => $user->id,
            'status' => 'ongoing',
            'started_at' => now(),
        ]);

        return redirect()->route('portal.battles.show', $battle)
            ->with('success', 'Anda telah bergabung dalam battle!');
    }
}
