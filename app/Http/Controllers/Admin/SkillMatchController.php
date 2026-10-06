<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillMatchController extends Controller
{
    /**
     * Display a listing of skill matches.
     */
    public function index(Request $request): View
    {
        $query = SkillMatch::with(['userA.profile', 'userB.profile', 'skillA', 'skillB']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('source')) {
            $query->where('match_source', $request->input('source'));
        }

        $matches = $query->latest('id')->paginate(10)->withQueryString();
        $totalMatches = SkillMatch::count();
        $acceptedMatches = SkillMatch::where('status', 'accepted')->count();
        $suggestedMatches = SkillMatch::where('status', 'suggested')->count();

        return view('admin.matches.index', compact('matches', 'totalMatches', 'acceptedMatches', 'suggestedMatches'));
    }

    /**
     * Update match status.
     */
    public function updateStatus(Request $request, SkillMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:suggested,accepted,rejected,expired'],
        ]);

        $match->update($validated);

        return back()->with('success', "Match #{$match->id} status updated to '{$validated['status']}'.");
    }

    /**
     * Remove the specified skill match.
     */
    public function destroy(SkillMatch $match): RedirectResponse
    {
        $id = $match->id;
        $match->delete();

        return redirect()->route('admin.matches.index')
            ->with('success', "Match #{$id} deleted successfully.");
    }
}
