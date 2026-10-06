<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MySkillController extends Controller
{
    /**
     * Display a listing of user's skills.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $offeredSkills = $user->offeredSkills()->with('skill.category')->get();
        $wantedSkills = $user->wantedSkills()->with('skill.category')->get();

        $allSkills = Skill::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('portal.skills.my-skills', compact('user', 'offeredSkills', 'wantedSkills', 'allSkills'));
    }

    /**
     * Store a skill in user's profile (offered or wanted).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'type' => ['required', 'in:offered,wanted'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $exists = UserSkill::where('user_id', $user->id)
            ->where('skill_id', $validated['skill_id'])
            ->where('type', $validated['type'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Skill ini sudah terdaftar dalam daftar keahlian Anda.');
        }

        UserSkill::create([
            'user_id' => $user->id,
            'skill_id' => $validated['skill_id'],
            'type' => $validated['type'],
        ]);

        $skill = Skill::find($validated['skill_id']);
        $typeName = $validated['type'] === 'offered' ? 'keahlian yang diajarkan' : 'keahlian yang ingin dipelajari';

        return back()->with('success', "Skill '{$skill->name}' berhasil ditambahkan ke {$typeName}.");
    }

    /**
     * Remove a skill from user's profile.
     */
    public function destroy(UserSkill $userSkill): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($userSkill->user_id !== $user->id) {
            abort(403);
        }

        $userSkill->delete();

        return back()->with('success', 'Skill berhasil dihapus dari daftar Anda.');
    }
}
