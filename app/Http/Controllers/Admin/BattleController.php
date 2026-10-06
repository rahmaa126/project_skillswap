<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BattleController extends Controller
{
    /**
     * Display a listing of skill battles.
     */
    public function index(Request $request): View
    {
        $query = Battle::with(['skill.category', 'player1.profile', 'player2.profile', 'winner.profile']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('skill_id')) {
            $query->where('skill_id', $request->input('skill_id'));
        }

        $battles = $query->latest('id')->paginate(10)->withQueryString();
        $totalBattles = Battle::count();
        $finishedBattles = Battle::where('status', 'finished')->count();
        $ongoingBattles = Battle::where('status', 'ongoing')->count();

        return view('admin.battles.index', compact('battles', 'totalBattles', 'finishedBattles', 'ongoingBattles'));
    }

    /**
     * Display the specified battle details.
     */
    public function show(Battle $battle): View
    {
        $battle->load([
            'skill.category',
            'player1.profile',
            'player2.profile',
            'winner.profile',
            'answers.question',
            'answers.user',
        ]);

        return view('admin.battles.show', compact('battle'));
    }

    /**
     * Remove the specified battle.
     */
    public function destroy(Battle $battle): RedirectResponse
    {
        $id = $battle->id;
        $battle->delete();

        return redirect()->route('admin.battles.index')
            ->with('success', "Battle #{$id} deleted successfully.");
    }
}
